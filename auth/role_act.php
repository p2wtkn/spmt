<?php

session_start();

require('../php/config.php');

if (isset($_SESSION['role'])) {
    roleCheck();
} else {
    header("Location: ../auth/login.php");
    exit();
}
