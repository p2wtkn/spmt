<?php

session_start();

require('../php/config.php');

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
    case '1':
        header('Location: ../student/student_dashboard.php');
        exit();
        break;
    case '2':
        header('Location: ../teacher/teacher_dashboard.php');
        exit();
        break;
    default:
        header("Location: ../auth/login.php");
        exit();
        break;
    }
} else {
    header("Location: ../auth/login.php");
    exit();
}
