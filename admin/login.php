<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username dan password
    if ($username == 'kelompok7' && $password == 'ratingadmin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link href="img/si-logo.png" rel="icon" id="icon-header">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
        margin: 0;
        padding: 0;
        background: url('../img/bg-jember.png');
        background-size: cover;
        font-family: sans-serif;
      }

      .box {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 25rem;
        padding: 2.5rem;
        box-sizing: border-box;
        background: rgba(0, 0, 0, 0.6);
        border-radius: 0.625rem;
      }

      .box h2 {
        margin: 0 0 1.875rem;
        padding: 0;
        color: #fff;
        text-align: center;
      }

      .box .inputBox {
        position: relative;
      }

      .box .inputBox input {
        width: 100%;
        padding: 0.625rem 0;
        font-size: 1rem;
        color: #fff;
        letter-spacing: 0.062rem;
        margin-bottom: 1.875rem;
        border: none;
        border-bottom: 0.065rem solid #fff;
        outline: none;
        background: transparent;
      }

      .box .inputBox label {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0.625rem 0;
        font-size: 1rem;
        color: #fff;
        pointer-events: none;
        transition: 0.5s;
      }

      .box .inputBox input:focus ~ label,
      .box .inputBox input:valid ~ label,
      .box .inputBox input:not([value=""]) ~ label {
        top: -1.125rem;
        left: 0;
        color: #03a9f4;
        font-size: 0.75rem;
      }

      .box input[type="submit"] {
        border: none;
        outline: none;
        color: #fff;
        background-color: #03a9f4;
        padding: 0.625rem 1.25rem;
        cursor: pointer;
        border-radius: 0.312rem;
        font-size: 1rem;
      }

      .box input[type="submit"]:hover {
        background-color: #1cb1f5;
      }

    </style>
</head>
<body class="bg-light">
<div class="container" style="margin-top:100px;">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-4">
                
            <div class="box">
                <h2>Login</h2>
                <form method="POST">
                <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <div class="inputBox">
                    <input type="text" name="username" required autofocus required onkeyup="this.setAttribute('value', this.value);" value="">
                    <label>Username</label>
                    </div>
                    <div class="inputBox">
                    <input type="password" name="password" required value=""
                            onkeyup="this.setAttribute('value', this.value);">
                    <label>Password</label>
                    </div>
                    <div class="d-grid">
                            <button type="submit" class="btn btn-outline-info">Login</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>