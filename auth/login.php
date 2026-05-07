<?php

session_start();

require('../php/config.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | SPMT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/auth.css">
    <style>
        .auth-card {
            max-width: 480px;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <a href="../index.php" class="auth-logo">SPMT</a>
        <h1 class="auth-title">เข้าสู่ระบบ</h1>

        <?php if (isset($_SESSION['login_notification']) && !empty($_SESSION['login_notification'])): ?>
            <div class="alert alert-danger rounded-3 border-0 shadow-sm p-3 mb-4 text-center">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['login_notification']; ?>
            </div>
        <?php endif; ?>

        <form action="auth_act.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" placeholder="รหัสผ่านของคุณ" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">เข้าสู่ระบบ</button>
        </form>
        <div class="auth-footer">
            <p>ยังไม่มีบัญชี? <a href="register.php">สมัครบัญชีผู้ใช้</a> สิ</p>
        </div>
    </div>
</body>

</html>