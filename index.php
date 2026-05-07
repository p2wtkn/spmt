<?php

session_start();

require('php/config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก | SPMT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="css/index_page.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-area">
                <img src="img/spmt_logo.png" alt="SPMT Logo" class="logo-img">
                <div class="logo-text">
                    <h1>SPMT</h1>
                    <p>Student Peer-Tutoring and Merit-Point Tracking System</p>
                </div>
            </div>
            <div>
                <a href="auth/login.php" class="btn-login">เข้าสู่ระบบ</a>
            </div>
        </div>
        <div class="main-wrapper">
            <div class="main">
                <h2>สวัสดี <span> <?= $_SESSION['first_name']; ?></span> ยินดีต้อนรับ <br> สู่ระบบ SPMT</h2>
                <a href="student/student_dashboard.php" class="btn-start">เริ่มต้นใช้งาน</a>
            </div>
        </div>
    </div>
</body>
</html>