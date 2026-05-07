<?php

// เชื่อมต่อฐานข้อมูล

try {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "spmt_db";

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // echo "การเชื่อมต่อสำเร็จ!";
} catch (mysqli_sql_exception $e) {

    echo "การเชื่อมต่อล้มเหลว: " . $e->getMessage();
}

// การแทรกข้อมูลผู้ใช้ลงฐานข้อมูลตราง users

function registerAccount($conn)
{
    $intitle = (int)$_POST['title'];
    $inf_name = $_POST['firstname'];
    $inl_name = $_POST['lastname'];
    $inemail = $_POST['email'];
    $inpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $inrole = (int)$_POST['role'];
    $inlevel = (int)$_POST['level'];
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
        } else {
            $_SESSION['register_notification'] =  "Error: " . $conn->error;
            header('Location: register.php');
        }
    } else {
        $_SESSION['register_notification'] = "อีเมลถูกใช้งานแล้ว! โปรดใช้อีเมลอื่นในการสมัครใช้งานระบบ SPMT";
        header('Location: register.php');
    }
}

// การเลือกข้อมูลจากฐานข้อมูลมาตรวจสอบบัญชีและมอบตัวตนแก่ผู้ใช้ระหว่างใช้งานระบบ

function loginCheck($conn)
{
    $inemail = $_POST['email'];
    $inpass  = $_POST['password'];

    // $sql = "SELECT user_id, title_id, first_name, last_name, grade, email, password, role , points FROM users
    // WHERE email = '$inemail' LIMIT 1";
    $sql = "SELECT u.*, t.title_name
            FROM users u
            LEFT JOIN titles t ON u.title_id = t.title_id
            WHERE u.email = '$inemail' LIMIT 1";

    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        echo "Title is: " . $row['title_name'];
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
            $_SESSION['login_notification'] = "อีเมลหรือรหัสผ่าน ไม่ถูกต้อง!";
            header('Location: login.php');
        }
    } else {
        unset($_SESSION['login_notification']);
        header('Location: login.php');
    }
}

// การตรวจสอบบทบาทของผู้ใช้และนำทางไปยังหน้าเเว็บไซต์ของตัวเอง

function roleCheck()
{
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
    exit();
}

function studentCheck()
{
    if ($_SESSION['role'] == '2') {
        header("Location: ../teacher/teacher_dashboard.php");
        exit;
    } else if (!isset($_SESSION['role'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function teacherCheck()
{
    if ($_SESSION['role'] == '1') {
        header("Location: ../student/student_dashboard.php");
        exit;
    } else if (!isset($_SESSION['role'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function teachingDemand($conn)
{
    $userid = $_SESSION['user_id'];
    $insubject = $_POST['subject'];
    $inlevel = $_POST['level'];
    $indate = $_POST['availabledate'];
    $intime = $_POST['availabletime'];

    $sql = "INSERT INTO teaching_demand (user_id, subject_id, teaching_level, available_day, available_time)
        VALUE ('$userid', '$insubject', '$inlevel', '$indate', '$intime')";
    $result = $conn->query($sql);
    $_SESSION['td_notification'] = "บันทึกความต้องการสอนสำเร็จ!";
    header('Location: tutor_demand.php');
    exit();
}

function learningDemand($conn)
{
    $userid = $_SESSION['user_id'];
    $insubject = $_POST['subject'];
    $indate = $_POST['availabledate'];
    $intime = $_POST['availabletime'];

    $sql = "INSERT INTO learner_demand (user_id, subject_id, convenience_day, convenience_time)
        VALUE ('$userid', '$insubject', '$indate', '$intime')";
    $result = $conn->query($sql);
    $_SESSION['ld_notification'] = "บันทึกความต้องการสอนสำเร็จ!";
    header('Location: learner_demand.php');
    exit();
}

function timeSwitch($no)
{
    switch ($no) {
        case "1":
            echo "07:00 - 08:00น.";
            break;
        case "2":
            echo "08:00 - 09:00น.";
            break;
        case "3":
            echo "09:00 - 10:00น.";
            break;
        case "4":
            echo "10:00 - 11:00น.";
            break;
        case "5":
            echo "11:00 - 12:00น.";
            break;
        case "6":
            echo "12:00 - 13:00น.";
            break;
        case "7":
            echo "14:00 - 15:00น.";
            break;
        case "8":
            echo "16:00 - 17:00น.";
            break;
        default:
            break;
    }
}

function createReward($conn)
{
    $names = $_POST['reward_name'];
    $descs = $_POST['description'];
    $points = $_POST['required_points'];
    $quotas = $_POST['reward_quota'];

    foreach ($names as $key => $val) {
        if (!empty($val)) {
            $n = mysqli_real_escape_string($conn, $val);
            $d = mysqli_real_escape_string($conn, $descs[$key]);
            $p = (int)$points[$key];
            $q = (int)$quotas[$key];
            mysqli_query($conn, "INSERT INTO reward (reward_name, description, required_points, reward_quota) VALUES ('$n', '$d', '$p', '$q')");
        }
    }
    header("Location: reward_management.php");
    exit;
}

function updateReward($conn)
{
    $id = (int)$_POST['reward_id'];
    $name = mysqli_real_escape_string($conn, $_POST['reward_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $points = (int)$_POST['required_points'];
    $quota = (int)$_POST['reward_quota'];

    $sql = "UPDATE reward SET reward_name='$name', description='$desc', required_points='$points', reward_quota='$quota' WHERE reward_id=$id";
    mysqli_query($conn, $sql);
    header("Location: reward_management.php");
    exit;
}

function deleteReward($conn)
{
    $id = (int)$_GET['delete_id'];

    mysqli_query($conn, "DELETE FROM reward WHERE reward_id = $id");
    header("Location: reward_management.php");
    exit;
}
