<?php

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

            $conn->query("INSERT INTO redemptions (user_id, reward_id, redemption_date) VALUES ('$user_id', '$reward_id', NOW())");

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

?>