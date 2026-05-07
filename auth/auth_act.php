<?php

session_start();

require('../php/config.php');

if (isset($_POST['login'])) {
    loginCheck($conn);
}

if (isset($_POST['register'])) {
    registerAccount($conn);
}

?>