<?php

session_start();

require('../php/config.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครบัญชีผู้ใช้ | SPMT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/auth.css">
    <style>
        .auth-card {
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="../index.php" class="auth-logo">SPMT</a>
        <h1 class="auth-title">สมัครบัญชีผู้ใช้</h1>
        
        <?php if (isset($_SESSION['register_notification']) && !empty($_SESSION['register_notification'])): ?>
            <div class="alert alert-danger rounded-3 border-0 shadow-sm p-3 mb-4 text-center">
                <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['register_notification']; ?>
            </div>
        <?php endif; ?>
        
        <form action="auth_act.php" method="post">
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label for="title" class="form-label">คำนำหน้า</label>
                    <select name="title" class="form-select" required>
                        <option value="">-- ระบุคำนำหน้า --</option>
                        <option value="1">เด็กชาย</option>
                        <option value="2">เด็กหญิง</option>
                        <option value="3">นาย</option>
                        <option value="4">นางสาว</option>
                        <option value="5">นาง</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="firstname" class="form-label">ชื่อ</label>
                    <input type="text" name="firstname" class="form-control" placeholder="ตัวอย่าง: สมศักดิ์" required>
                </div>
                <div class="col-md-6">
                    <label for="lastname" class="form-label">นามสกุล</label>
                    <input type="text" name="lastname" class="form-control" placeholder="ตัวอย่าง: ยอดชาย" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="grade" class="form-label">ระดับชั้น</label>
                    <select name="grade" class="form-select" required>
                        <option value="">-- ชั้นมัธยมศึกษา --</option>
                        <option value="1">ชั้นมัธยมศึกษาปีที่ 1</option>
                        <option value="2">ชั้นมัธยมศึกษาปีที่ 2</option>
                        <option value="3">ชั้นมัธยมศึกษาปีที่ 3</option>
                        <option value="4">ชั้นมัธยมศึกษาปีที่ 4</option>
                        <option value="5">ชั้นมัธยมศึกษาปีที่ 5</option>
                        <option value="6">ชั้นมัธยมศึกษาปีที่ 6</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="role" class="form-label">บทบาท</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- บทบาทของคุณ --</option>
                        <option value="1">นักเรียน</option>
                        <option value="2">คุณครู</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" placeholder="รหัสผ่านของคุณ" required>
            </div>

            <button class="btn btn-primary" name="register" type="submit">สมัครบัญชีผู้ใช้</button>
        </form>

        <div class="auth-footer">
            <p>มีบัญชีผู้ใช้แล้ว? <a href="login.php">เข้าสู่ระบบ</a> สิ</p>
        </div>
    </div>
</body>
</html>