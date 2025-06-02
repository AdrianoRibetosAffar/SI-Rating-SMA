$(document).ready(function() {
    // Handle star hover effect
    $('.stars-input').on('mouseenter', '.star-label', function() {
        const rating = $(this).data('rating');
        const container = $(this).closest('.stars-input');
        
        container.find('.star-label').each(function() {
            const starRating = $(this).data('rating');
            if (starRating <= rating) {
                $(this).find('i').addClass('text-warning').removeClass('text-muted');
            } else {
                $(this).find('i').addClass('text-muted').removeClass('text-warning');
            }
        });
    });
    
    // Handle mouse leave
    $('.stars-input').on('mouseleave', function() {
        const container = $(this);
        const checkedRating = container.find('input:checked').val() || 0;
        
        container.find('.star-label').each(function() {
            const starRating = $(this).data('rating');
            if (starRating <= checkedRating) {
                $(this).find('i').addClass('text-warning').removeClass('text-muted');
            } else {
                $(this).find('i').addClass('text-muted').removeClass('text-warning');
            }
        });
    });
    
    // Handle star click
    $('.stars-input').on('click', '.star-label', function() {
        const rating = $(this).data('rating');
        const schoolId = $(this).closest('.stars-input').data('school-id');
        const container = $(this).closest('.rating-form');
        
        // Check the corresponding radio button
        $(`#star-${rating}-${schoolId}`).prop('checked', true);
        
        // Show captcha
        showCaptcha(schoolId);
    });
    
    // Handle submit rating
    $('.submit-rating').on('click', function() {
        const schoolId = $(this).data('school-id');
        submitRating(schoolId);
    });
    
    // Handle enter key in captcha input
    $('.captcha-input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            const schoolId = $(this).attr('id').split('-')[1];
            submitRating(schoolId);
        }
    });
});

function showCaptcha(schoolId) {
    const captchaSection = $(`#rating-form-${schoolId} .captcha-section`);
    const questionSpan = $(`#captcha-question-${schoolId}`);
    
    // Generate simple math question
    const num1 = Math.floor(Math.random() * 10) + 1;
    const num2 = Math.floor(Math.random() * 10) + 1;
    const operation = Math.random() > 0.5 ? '+' : '-';
    
    let question, answer;
    if (operation === '+') {
        question = `${num1} + ${num2}`;
        answer = num1 + num2;
    } else {
        question = `${Math.max(num1, num2)} - ${Math.min(num1, num2)}`;
        answer = Math.max(num1, num2) - Math.min(num1, num2);
    }
    
    questionSpan.text(question);
    captchaSection.data('answer', answer).show();
    $(`#captcha-${schoolId}`).focus();
}

function submitRating(schoolId) {
    const rating = $(`input[name="rating_${schoolId}"]:checked`).val();
    const captchaAnswer = $(`#captcha-${schoolId}`).val();
    const correctAnswer = $(`.captcha-section`).data('answer');
    const messageDiv = $(`#message-${schoolId}`);
    
    // Validate rating
    if (!rating) {
        showMessage(schoolId, 'Pilih rating terlebih dahulu!', 'danger');
        return;
    }
    
    // Validate captcha
    if (!captchaAnswer || parseInt(captchaAnswer) !== correctAnswer) {
        showMessage(schoolId, 'Jawaban captcha salah!', 'danger');
        return;
    }
    
    // Show loading
    showMessage(schoolId, '<i class="fas fa-spinner fa-spin"></i> Mengirim rating...', 'info');
    
    // Submit via AJAX
    $.ajax({
        url: 'simpan_rating.php',
        method: 'POST',
        data: {
            id_sekolah: schoolId,
            rating: rating,
            captcha: captchaAnswer
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showMessage(schoolId, response.message, 'success');
                updateRatingDisplay(schoolId, response.stats);
                resetRatingForm(schoolId);
                
                // Update ranking setelah 2 detik
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                showMessage(schoolId, response.message, 'danger');
                if (response.new_captcha) {
                    showCaptcha(schoolId);
                }
            }
        },
        error: function(xhr, status, error) {
            showMessage(schoolId, 'Terjadi kesalahan koneksi!', 'danger');
            console.error('AJAX Error:', error);
        }
    });
}

function showMessage(schoolId, message, type) {
    const messageDiv = $(`#message-${schoolId}`);
    const alertClass = `alert alert-${type} alert-sm py-1 px-2 mb-0`;
    
    messageDiv.html(`<div class="${alertClass}">${message}</div>`);
    
    if (type === 'success') {
        setTimeout(() => {
            messageDiv.fadeOut();
        }, 3000);
    }
}

function updateRatingDisplay(schoolId, stats) {
    // Update rating statistics
    const avgRating = stats.avg_rating;
    const totalRating = stats.total_rating;
    
    // Update stars display
    const starsDisplay = $(`#rating-form-${schoolId}`).siblings('.rating-stats').find('.stars-display');
    starsDisplay.html('');
    
    for (let i = 1; i <= 5; i++) {
        const starClass = i <= Math.round(avgRating) ? 'text-warning' : 'text-muted';
        starsDisplay.append(`<i class="fas fa-star ${starClass}"></i>`);
    }
    
    // Update rating text
    const ratingText = starsDisplay.next();
    ratingText.text(`${avgRating} (${totalRating} ulasan)`);
}

function resetRatingForm(schoolId) {
    // Reset radio buttons
    $(`input[name="rating_${schoolId}"]`).prop('checked', false);
    
    // Reset stars visual
    $(`#rating-form-${schoolId} .star-label i`).removeClass('text-warning').addClass('text-muted');
    
    // Hide captcha
    $(`#rating-form-${schoolId} .captcha-section`).hide();
    $(`#captcha-${schoolId}`).val('');
}