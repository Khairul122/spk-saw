<?php
session_start();
if(isset($_SESSION['status'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - SPK Pemilihan Pegawai Terbaik</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0d6efd20 0%, #0d6efd05 100%);
        }
        .login-card {
            max-width: 400px;
            width: 90%;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: white;
            border-radius: 10px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-form .form-control {
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
        }
        .btn-login {
            width: 100%;
            padding: 0.8rem;
            font-weight: 500;
        }
        .system-title {
            color: #0d6efd;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="system-title">SPK SAW</div>
                <p class="text-muted">Silakan login untuk melanjutkan</p>
            </div>
            
            <form method="post" action="login.php" class="login-form">
                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                
                <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    Username atau password salah!
                </div>
                <?php endif; ?>
                
                <button type="submit" name="login" class="btn btn-primary btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>
        </div>
    </div>

    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>