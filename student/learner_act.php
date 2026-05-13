<?php

session_start();

require('../php/config.php');

studentCheck();

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
                       AND available_time = '$time'
                       AND user_id != '$user_id'";
            $td_res = $conn->query($td_sql);

            if ($td_res->num_rows > 0) {
                $td_data = $td_res->fetch_assoc();
                $td_id = $td_data['td_id'];

                $conn->begin_transaction();
                try {
                    $conn->query("INSERT INTO teaching_log (td_id, ld_id, tutor_confirmed, teacher_confirmed) VALUES ('$td_id', '$ld_id', 0, 0)");
                    $conn->commit();
                    $_SESSION['ld_notification'] = "จับคู่สำเร็จ! โปรดรอผู้สอนยืนยัน";
                    $_SESSION['ld_notif_type'] = 'success';
                } catch (Exception $e) {
                    $conn->rollback();
                    $_SESSION['ld_notification'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
                    $_SESSION['ld_notif_type'] = 'danger';
                }
            } else {
                $_SESSION['ld_notification'] = "ขออภัย ไม่พบคลาสเรียนที่ตรงกับความต้องการของคุณในขณะนี้";
                $_SESSION['ld_notif_type'] = 'warning';
            }
        } else {
            $_SESSION['ld_notification'] = "ความต้องการนี้ถูกจับคู่ไปแล้ว";
            $_SESSION['ld_notif_type'] = 'info';
        }
    } else {
        $_SESSION['ld_notification'] = "ไม่พบข้อมูลความต้องการเรียน";
        $_SESSION['ld_notif_type'] = 'danger';
    }

    header('Location: learner_demand.php');
    exit();
}

if (isset($_POST['learner_submit'])) {
    $userid = $_SESSION['user_id'];
    $insubject = $_POST['subject'];
    $indate = $_POST['availabledate'];
    $intime = $_POST['availabletime'];

    $sql = "INSERT INTO learner_demand (user_id, subject_id, convenience_day, convenience_time)
        VALUE ('$userid', '$insubject', '$indate', '$intime')";
    $result = $conn->query($sql);
    $_SESSION['ld_notification'] = "บันทึกความต้องการสอนสำเร็จ!";
    $_SESSION['ld_notif_type'] = 'success';
    header('Location: learner_demand.php');
    exit();
}

if (isset($_REQUEST['type'])) {

    $type = $_REQUEST['type'];

    if ($type == "ld_detail") {

        $tl_id = $_GET['teaching_log_id'];

        $sql = "SELECT tl.*,
                td.*,
                ld.*,
                u.*,
                s.subject_name
            FROM teaching_log tl
            LEFT JOIN teaching_demand td
            ON tl.td_id = td.td_id
            LEFT JOIN learner_demand ld
            ON tl.ld_id = ld.ld_id
            LEFT JOIN users u
            ON td.user_id = u.user_id
            LEFT JOIN subject s
            ON td.subject_id = s.subject_id
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
                <title>ตรวจสอบการเรียน | SPMT</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link rel="stylesheet" href="../css/student.css">
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                        font-family: 'Prompt', sans-serif;
                    }

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
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
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
                    <h3 class="fw-bold text-center mb-4"><i class="fas fa-check-circle text-primary me-2"></i>ตรวจสอบการเรียน</h3>

                    <div class="info-box">
                        <p class="mb-1 text-muted small">รายวิชา</p>
                        <h5 class="fw-bold mb-0"><?= $data['subject_name'] ?></h5>
                        <p class="mb-0 mt-2 text-muted small"><i class="far fa-user me-1"></i> ผู้สอน: <?= $data['first_name'] . ' ' . $data['last_name'] ?></p>
                        <p class="mb-0 mt-2 text-muted small"><i class="fas fa-school mr-1"></i> ระดับชั้น: มัธยมศึกษาปีที่ <?= $data['teaching_level'] ?></p>
                        <p class="mb-0 mt-2 text-muted small"><i class="far fa-calendar-alt me-1"></i> วันที่สอน: <?= $data['available_day'] ?></p>
                        <p class="mb-0 mt-2 text-muted small"><i class="far fa-clock me-1"></i> เวลา: <?php $timePeriod = $data['available_time'];
                                                                                                        echo timeSwitch($timePeriod); ?></p>
                    </div>
                    <a href="learner_demand.php" class="btn btn-link w-100 mt-2 text-decoration-none text-muted">ย้อนกลับ</a>
                </div>
            </body>

            </html>

        <?php
        } else {
            $_SESSION['ld_notification'] = "ยังไม่ถึงกำหนดการเรียน หรือไม่พบข้อมูล";
            $_SESSION['ld_notif_type'] = 'warning';
            header('Location: learner_demand.php');
            exit();
        }
    }
}

$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type == "delete") {

    if (isset($_GET['ld_id'])) {
        $ld_id = $_GET['ld_id'];

        $sql = "DELETE FROM learner_demand
            WHERE ld_id = $ld_id";
        $result = $conn->query($sql);
        if ($result) {
            $_SESSION['ld_notification'] = "การลบความต้องการเรียน ld_id: {$ld_id} เสร็จสิ้น!";
            $_SESSION['ld_notif_type'] = 'success';
            header("Location: learner_demand.php");
            exit;
        }
    }
}

if (isset($_POST['learner_edit'])) {
    $ld_id = $_POST['ld_id'];
    $user_id = $_SESSION['user_id'];
    $insubject = $_POST['subject'];
    $indate = $_POST['convenientday'];
    $intime = $_POST['convenienttime'];

    $sql = "UPDATE learner_demand
            SET user_id = '$user_id',
                subject_id = '$insubject',
                convenience_day = '$indate',
                convenience_time = '$intime'
                WHERE ld_id = '$ld_id'";
    $result = $conn->query($sql);
    if ($result) {
        $_SESSION['ld_notification'] = "แก้ไขความต้องการเรียน ld_id: {$ld_id} สำเร็จ!";
        $_SESSION['ld_notif_type'] = 'success';
        header('Location: learner_demand.php');
        exit();
    }
}

if ($type == "edit") {

    if (isset($_GET['ld_id'])) {

        $ld_id = $_GET['ld_id'];

        $ld_sql = "SELECT ld.*,
            s.subject_name
        FROM learner_demand ld
        LEFT JOIN subject s
        ON ld.subject_id = s.subject_id
        WHERE ld.ld_id='$ld_id'";

        $ld_result = $conn->query($ld_sql);

        if ($ld_result && $ld_result->num_rows > 0) {
            $data = $ld_result->fetch_assoc();
        ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>ยืนยันการสอน | SPMT</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link rel="stylesheet" href="../css/student.css">
            </head>

            <body>
                <div class="container-custom">
                    <?php

                    include('student_sidebar.html');

                    ?>
                    <div class="main">
                        <div class="card card-custom">
                            <h4 class="text-primary fw-bold mb-4"><i class="fas fa-edit me-1"></i>แก้ไขความต้องการช่วยเรียน</h4>
                            <form action="learner_act.php" method="post">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle">
                                        <thead class="small text-muted">
                                            <tr>
                                                <th>วิชา</th>
                                                <th>วันที่สะดวก</th>
                                                <th>ช่วงเวลาที่สะดวก</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="ld_id" value="<?php echo $data['ld_id']; ?>">

                                                    <select name="subject" class="form-select" required>
                                                        <option value="">ระบุวิชา</option>

                                                        <?php

                                                        $subjects = [
                                                            "1" => "ภาษาไทย",
                                                            "2" => "คณิตศาสตร์",
                                                            "3" => "วิทยาศาสตร์",
                                                            "4" => "สังคมศึกษา",
                                                            "5" => "ภาษาอังกฤษ"
                                                        ];
                                                        foreach ($subjects as $s => $subject) {
                                                            $selected = ($data['subject_id'] == $s) ? 'selected' : '';
                                                            echo "<option value='$s' $selected>$subject</option>";
                                                        }

                                                        ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="date" name="convenientday" class="form-control" value="<?= $data['convenience_day'] ?>" required>
                                                </td>
                                                <td>
                                                    <select name="convenienttime" class="form-select" required>
                                                        <option value="">ช่วงเวลา</option>

                                                        <?php

                                                        $times = [
                                                            "1" => "07:00 - 08:00น.",
                                                            "2" => "08:00 - 09:00น.",
                                                            "3" => "09:00 - 10:00น.",
                                                            "4" => "10:00 - 11:00น.",
                                                            "5" => "11:00 - 12:00น.",
                                                            "6" => "12:00 - 13:00น.",
                                                            "7" => "14:00 - 15:00น.",
                                                            "8" => "16:00 - 17:00น."
                                                        ];
                                                        foreach ($times as $t => $time) {
                                                            $selected = ($data['convenience_time'] == $t) ? 'selected' : '';
                                                            echo "<option value='$t' $selected>$time</option>";
                                                        }

                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="button-group">
                                        <button type="submit" name="learner_edit" class="btn btn-primary shadow-sm mt-2 px-4">
                                            <i class="fas fa-edit me-1"></i>แก้ไข
                                        </button>
                                        <a href="learner_demand.php" class="btn btn-secondary shadow-sm mt-2 px-4">
                                            <i class="fas fa-times me-1"></i>ยกเลิก
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </body>

            </html>
<?php

        } else {
            $_SESSION['ld_notification'] = "ไม่พบข้อมูล";
            $_SESSION['ld_notif_type'] = 'danger';
            header('Location: learner_demand.php');
            exit();
        }
    }
}
?>