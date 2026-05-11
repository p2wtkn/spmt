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

function timeSwitch($timePeriod)
{
    switch ($timePeriod) {
        case "1":
            $time =  "07:00 - 08:00น.";
            break;
        case "2":
            $time =  "08:00 - 09:00น.";
            break;
        case "3":
            $time =  "09:00 - 10:00น.";
            break;
        case "4":
            $time =  "10:00 - 11:00น.";
            break;
        case "5":
            $time =  "11:00 - 12:00น.";
            break;
        case "6":
            $time =  "12:00 - 13:00น.";
            break;
        case "7":
            $time =  "14:00 - 15:00น.";
            break;
        case "8":
            $time =  "16:00 - 17:00น.";
            break;
        default:
            $time = "ไม่มีข้อมูล";
            break;
    }
    return $time;
}

function userPoints($conn, $user_id)
{
    $pts_sql = "SELECT points
                FROM users
                WHERE user_id = '$user_id'";
    $pts_result = $conn->query($pts_sql);
    $pts = $pts_result->fetch_assoc();

    return $pts['points'];
}

function pointRanking($conn)
{
    $ranking_sql = "SELECT first_name, points
                    FROM users
                    WHERE role=1
                    ORDER BY points DESC
                    LIMIT 5";
    $result = $conn->query($ranking_sql);
    $tutors = [];
    $points = [];
    while ($row = $result->fetch_assoc()) {
        $tutors[] = $row['first_name'];
        $points[] = $row['points'];
    }
    return [
        'tutors' => $tutors,
        'points' => $points
    ];
}
