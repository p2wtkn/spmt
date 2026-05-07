<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../php/config.php');

studentCheck();

if (isset($_POST['type']) && $_POST['type'] == "redeem_reward") {
    $user_id = $_SESSION['user_id'];
    $reward_id = $_POST['reward_id'];
    $req_pts = (int)$_POST['required_points'];

    $check_sql = "SELECT points FROM users WHERE user_id = '$user_id'";
    $check_res = $conn->query($check_sql);
    $user_data = $check_res->fetch_assoc();

    $reward_sql = "SELECT reward_quota FROM reward WHERE reward_id = '$reward_id'";
    $reward_res = $conn->query($reward_sql);
    $reward_data = $reward_res->fetch_assoc();

    if ($user_data['points'] >= $req_pts && $reward_data['reward_quota'] > 0) {
        $conn->begin_transaction();
        try {
            $new_pts = $user_data['points'] - $req_pts;
            $conn->query("UPDATE users SET points = $new_pts WHERE user_id = '$user_id'");

            $conn->query("UPDATE reward SET reward_quota = reward_quota - 1 WHERE reward_id = '$reward_id'");

            $conn->query("INSERT INTO redemptions (user_id, reward_id, redemption_date, status) VALUES ('$user_id', '$reward_id', NOW(), 1)");

            $conn->commit();
            $_SESSION['reward_notification'] = "แลกของรางวัลสำเร็จ!";
            $_SESSION['reward_notif_type'] = "success";
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['reward_notification'] = "เกิดข้อผิดพลาดในการแลกรางวัล: " . $e->getMessage();
            $_SESSION['reward_notif_type'] = "danger";
        }
    } else {
        if ($reward_data['reward_quota'] <= 0) {
            $_SESSION['reward_notification'] = "ขออภัย ของรางวัลนี้หมดแล้ว";
        } else {
            $_SESSION['reward_notification'] = "คะแนนของคุณไม่เพียงพอสำหรับการแลกรางวัลนี้";
        }
        $_SESSION['reward_notif_type'] = "danger";
    }
    
    header('Location: students_reward.php');
    exit();
}

if (isset($_GET['type']) && $_GET['type'] == "match") {
    $ld_id = $_GET['ld_id'];
    $user_id = $_SESSION['user_id'];

    $ld_sql = "SELECT * FROM learner_demand WHERE ld_id = '$ld_id' AND user_id = '$user_id'";
    $ld_res = $conn->query($ld_sql);
    
    if ($ld_res->num_rows > 0) {
        $ld_data = $ld_res->fetch_assoc();
        $subject_id = $ld_data['subject_id'];
        $day = $ld_data['convenience_day'];
        $time = $ld_data['convenience_time'];

        $check_match = "SELECT teaching_log_id FROM teaching_log WHERE ld_id = '$ld_id'";
        $match_exists = $conn->query($check_match);

        if ($match_exists->num_rows == 0) {
            $td_sql = "SELECT td_id FROM teaching_demand 
                       WHERE subject_id = '$subject_id' 
                       AND available_day = '$day' 
                       AND available_time = '$time'";
            $td_res = $conn->query($td_sql);

            if ($td_res->num_rows > 0) {
                $td_data = $td_res->fetch_assoc();
                $td_id = $td_data['td_id'];

                $conn->begin_transaction();
                try {
                    $conn->query("INSERT INTO teaching_log (td_id, ld_id, tutor_confirmed, teacher_confirmed) VALUES ('$td_id', '$ld_id', 0, 0)");
                    $conn->commit();
                    $_SESSION['ld_notification'] = "จับคู่สำเร็จ! โปรดรอผู้สอนยืนยัน";
                } catch (Exception $e) {
                    $conn->rollback();
                    $_SESSION['ld_notification'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
                }
            } else {
                $_SESSION['ld_notification'] = "ขออภัย ไม่พบคลาสเรียนที่ตรงกับความต้องการของคุณในขณะนี้";
            }
        } else {
            $_SESSION['ld_notification'] = "ความต้องการนี้ถูกจับคู่ไปแล้ว";
        }
    } else {
        $_SESSION['ld_notification'] = "ไม่พบข้อมูลความต้องการเรียน";
    }

    header('Location: learner_demand.php');
    exit();
}


if (isset($_POST['tdsubmit'])) {
    $tl_id = $_POST['teaching_log_id'];
    $evid = $_POST['evidence'];
    
    $sql = "UPDATE teaching_log 
            SET tutor_confirmed = 1, evidence = '$evid'
            WHERE teaching_log_id = '$tl_id'";
    $result = $conn->query($sql);
    
    $_SESSION['td_notification'] = "ยืนยันการสอนเสร็จสิ้น";
    header('Location: tutor_demand.php');
    exit();
}

if (isset($_POST['tutor_submit'])) {
    teachingDemand($conn);
}

if (isset($_POST['learner_submit'])) {
    learningDemand($conn);
}


if (isset($_REQUEST['type'])){

$type = $_REQUEST['type'];

if ($type == "edit") {

    $tl_id = $_GET['teaching_log_id'];

    $sql = "SELECT tl.*, td.*, s.subject_name
    FROM teaching_log tl
    LEFT JOIN teaching_demand td ON tl.td_id = td.td_id
    LEFT JOIN subject s ON td.subject_id = s.subject_id
    WHERE tl.teaching_log_id='$tl_id'
    AND td.available_day <= CURDATE()";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการสอน | SPMT</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Prompt', sans-serif; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('../img/index_ln.webp');
            background-size: cover;
            background-position: center;
            padding: 2rem;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            z-index: -1;
        }
        .confirm-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        .form-control {
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
            border: 1px solid #d1d5db;
        }
        .btn-primary {
            border-radius: 12px;
            padding: 0.8rem;
            font-weight: 600;
            background: #3b82f6;
            border: none;
            width: 100%;
        }
        .info-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #3b82f6;
        }
    </style>
</head>
<body>
    <div class="confirm-card">
        <h3 class="fw-bold text-center mb-4"><i class="fas fa-check-circle text-primary me-2"></i>ยืนยันการสอน</h3>
        
        <div class="info-box">
            <p class="mb-1 text-muted small">รายวิชา</p>
            <h5 class="fw-bold mb-0"><?= $data['subject_name'] ?></h5>
            <p class="mb-0 mt-2 text-muted small"><i class="far fa-calendar-alt me-1"></i> วันที่สอน: <?= $data['available_day'] ?></p>
        </div>

        <form action="student_act.php" method="post">
            <input type="hidden" name="teaching_log_id" value="<?= $tl_id ?>">
            
            <div class="mb-4">
                <label for="evidence" class="form-label fw-bold">หลักฐานการสอน (ลิงก์รูปภาพ/ไฟล์)</label>
                <input type="text" name="evidence" class="form-control" placeholder="วางลิงก์หลักฐานที่นี่..." required>
                <div class="form-text mt-2">กรุณาอัปโหลดหลักฐานขึ้น Cloud (เช่น Google Drive, Dropbox) แล้วนำลิงก์มาวาง</div>
            </div>

            <button type="submit" name="tdsubmit" class="btn btn-primary shadow">ยืนยันการสอน</button>
            <a href="tutor_demand.php" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">ยกเลิก</a>
        </form>
    </div>
</body>
</html>

<?php
    } else {
        $_SESSION['td_notification'] = "ยังไม่ถึงกำหนดการสอน หรือไม่พบข้อมูล";
        header('Location: tutor_demand.php');
        exit();
    }
}
}