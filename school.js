    // Data sementara untuk sekolah
    const dataSekolah = [
        {
            nama: "SMA Negeri 1 Jayapura",
            alamat: "Jayapura",
            akreditasi: "A",
            prestasi: [
                "Juara 1 Olimpiade Sains Nasional",
                "Juara 2 Lomba Debat Bahasa Inggris",
            ],
        },
        {
            nama: "SMA Negeri 2 Jayapura",
            alamat: "Jayapura",
            akreditasi: "A",
            prestasi: [
                "Juara 1 Lomba Seni Tari",
                "Juara 3 Lomba Fisika",
            ],
        },
        {
            nama: "SMA Negeri 3 Jayapura",
            alamat: "Jayapura",
            akreditasi: "B",
            prestasi: [
                "Juara 1 Lomba Matematika",
                "Juara 2 Lomba Karya Ilmiah",
            ],
        },
        // Tambahkan data sekolah lainnya...
    ];
    // Fungsi untuk menyimpan data sekolah yang dipilih ke localStorage
    function tampilkanDetail(namaSekolah) {
        const sekolahDipilih = dataSekolah.find(sekolah => sekolah.nama === namaSekolah);
        if (sekolahDipilih) {
            localStorage.setItem("sekolahDipilih", JSON.stringify(sekolahDipilih));
            window.location.href = "detail-sekolah.html"; // Arahkan ke halaman daftar-sekolah.html
        }
    }
    