<?php

session_start();

require('../php/config.php');

if (isset($_POST['login'])) {
    $inemail = $_POST['email'];
    $inpass  = $_POST['password'];

    $sql = "SELECT u.*, t.title_name
            FROM users u
            LEFT JOIN titles t ON u.title_id = t.title_id
            WHERE u.email = '$inemail' LIMIT 1";

    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($inpass, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['title'] = $row['title_name'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['grade'] = $row['grade'];
            $_SESSION['role'] = $row['role'];
            header('Location: role_act.php');
            exit();
        } else {
            $_SESSION['login_notification'] = "รหัสผ่านไม่ถูกต้อง!";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['login_notification'] = "ไม่พบผู้ใช้ในระบบ!";
        header('Location: login.php');
        exit();
    }
}

if (isset($_POST['register'])) {
    $intitle = (int)$_POST['title'];
    $inf_name = $_POST['firstname'];
    $inl_name = $_POST['lastname'];
    $inemail = $_POST['email'];
    $inpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $inrole = (int)$_POST['role'];
    $inlevel = (int)$_POST['grade'];
    $inpoint = '0';

    $sql = "SELECT email FROM users WHERE email = '$inemail'";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        $sql = "INSERT INTO users (title_id, first_name, last_name, email, password, role , grade, points, create_at) 
        VALUES ('$intitle', '$inf_name', '$inl_name', '$inemail', '$inpass', '$inrole', '$inlevel', '$inpoint', NOW())";
        $result = $conn->query($sql);

        if ($result === TRUE) {
            $_SESSION['register_notification'] = "บัญชีของคุณ {$inf_name} ถูกสร้างเสร็จสิ้น!";
            header('Location: register.php');
            exit();
        } else {
            $_SESSION['register_notification'] =  "Error: " . $conn->error;
            header('Location: register.php');
            exit();
        }
    } else {
        $_SESSION['register_notification'] = "อีเมลถูกใช้งานแล้ว! โปรดใช้อีเมลอื่นในการสมัครใช้งานระบบ SPMT";
        header('Location: register.php');
        exit();
    }
}

?>