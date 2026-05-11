<?php

session_start();

require('../php/config.php');

studentCheck();

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
    $userid = $_SESSION['user_id'];
    $insubject = $_POST['subject'];
    $inlevel = $_POST['level'];
    $indate = $_POST['availabledate'];
    $intime = $_POST['availabletime'];

    $sql = "INSERT INTO teaching_demand (user_id, subject_id, teaching_level, available_day, available_time)
        VALUE ('$userid', '$insubject', '$inlevel', '$indate', '$intime')";
    $result = $conn->query($sql);
    $_SESSION['td_notification'] = "บันทึกความต้องการสอนสำเร็จ!";
    $_SESSION['td_notif_type'] = 'success';
    header('Location: tutor_demand.php');
    exit();
}

if (isset($_POST['tutor_edit'])) {
    $td_id = $_POST['td_id'];
    $user_id = $_SESSION['user_id'];
    $insubject = $_POST['subject'];
    $inlevel = $_POST['level'];
    $indate = $_POST['availabledate'];
    $intime = $_POST['availabletime'];

    $sql = "UPDATE teaching_demand
            SET user_id = '$user_id',
                subject_id = '$insubject',
                teaching_level = '$inlevel',
                available_day = '$indate',
                available_time = '$intime'
                WHERE td_id = '$td_id'";
    $result = $conn->query($sql);
    if ($result) {
        $_SESSION['td_notification'] = "แก้ไขความต้องการสอน td_id: {$td_id} สำเร็จ!";
        $_SESSION['td_notif_type'] = 'success';
        header('Location: tutor_demand.php');
        exit();
    }
}

if (isset($_REQUEST['type'])) {

    $type = $_REQUEST['type'];

    if ($type == "td_detail") {

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
            ON ld.user_id = u.user_id
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
                <title>ตรวจสอบการสอน | SPMT</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link rel="stylesheet" href="../css/student.css">
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                        font-family: 'Inter', sans-serif;
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
                    <h3 class="fw-bold text-center mb-4"><i class="fas fa-check-circle text-primary me-2"></i>ตรวจสอบการสอน</h3>

                    <div class="info-box">
                        <p class="mb-1 text-muted small">รายวิชา</p>
                        <h5 class="fw-bold mb-0"><?= $data['subject_name'] ?></h5>
                        <p class="mb-0 mt-2 text-muted small"><i class="far fa-user me-1"></i> ผู้เรียน: <?= $data['first_name'] . ' ' . $data['last_name'] ?></p>
                        <p class="mb-0 mt-2 text-muted small"><i class="fas fa-school mr-1"></i> ระดับชั้น: มัธยมศึกษาปีที่ <?= $data['teaching_level'] ?></p>
                        <p class="mb-0 mt-2 text-muted small"><i class="far fa-calendar-alt me-1"></i> วันที่สอน: <?= $data['available_day'] ?></p>
                        <p class="mb-0 mt-2 text-muted small"><i class="far fa-clock me-1"></i> เวลา: <?php $timePeriod = $data['available_time'];
                                                                                                        echo timeSwitch($timePeriod); ?></p>
                    </div>

                    <form action="tutor_act.php" method="post">
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
            $_SESSION['td_notif_type'] = 'warning';
            header('Location: tutor_demand.php');
            exit();
        }
    }

    $type = isset($_GET['type']) ? $_GET['type'] : '';

    if ($type == "delete") {

        if (isset($_GET['td_id'])) {
            $td_id = $_GET['td_id'];

            $sql = "DELETE FROM teaching_demand
            WHERE td_id = $td_id";
            $result = $conn->query($sql);
            if ($result) {
                $_SESSION['td_notification'] = "การลบความต้องการสอน td_id: {$td_id} เสร็จสิ้น!";
                $_SESSION['td_notif_type'] = 'success';
                header("Location: tutor_demand.php");
                exit;
            }
        }
    }

    if ($type == "edit") {

        if (isset($_GET['td_id'])) {

            $td_id = $_GET['td_id'];

            $td_sql = "SELECT td.*,
            s.subject_name
        FROM teaching_demand td
        LEFT JOIN subject s
        ON td.subject_id = s.subject_id
        WHERE td.td_id='$td_id'";

            $td_result = $conn->query($td_sql);

            if ($td_result && $td_result->num_rows > 0) {
                $data = $td_result->fetch_assoc();
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
                                <h4 class="text-primary fw-bold mb-4"><i class="fas fa-edit me-1"></i>แก้ไขความต้องการช่วยสอน</h4>
                                <form action="tutor_act.php" method="post">
                                    <div class="table-responsive">
                                        <table class="table table-borderless align-middle">
                                            <thead class="small text-muted">
                                                <tr>
                                                    <th>วิชา</th>
                                                    <th>ระดับชั้น</th>
                                                    <th>วันที่สะดวก</th>
                                                    <th>ช่วงเวลาที่สะดวก</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="td_id" value="<?php echo $data['td_id']; ?>">

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
                                                        <select name="level" class="form-select" required>
                                                            <option value="">ระดับชั้น</option>

                                                            <?php

                                                            $levels = [
                                                                "1" => "มัธยมศึกษาชั้นปีที่ 1",
                                                                "2" => "มัธยมศึกษาชั้นปีที่ 2",
                                                                "3" => "มัธยมศึกษาชั้นปีที่ 3",
                                                                "4" => "มัธยมศึกษาชั้นปีที่ 4",
                                                                "5" => "มัธยมศึกษาชั้นปีที่ 5",
                                                                "6" => "มัธยมศึกษาชั้นปีที่ 6"
                                                            ];
                                                            foreach ($levels as $l => $grade) {
                                                                $selected = ($data['teaching_level'] == $l) ? 'selected' : '';
                                                                echo "<option value='$l' $selected>$grade</option>";
                                                            }

                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="date" name="availabledate" class="form-control" value="<?= $data['available_day'] ?>" required>
                                                    </td>
                                                    <td>
                                                        <select name="availabletime" class="form-select" required>
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
                                                                $selected = ($data['available_time'] == $t) ? 'selected' : '';
                                                                echo "<option value='$t' $selected>$time</option>";
                                                            }

                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="button-group">
                                            <button type="submit" name="tutor_edit" class="btn btn-primary shadow-sm mt-2 px-4">
                                                <i class="fas fa-edit me-1"></i>แก้ไข
                                            </button>
                                            <a href="tutor_demand.php" class="btn btn-secondary shadow-sm mt-2 px-4">
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
                $_SESSION['td_notification'] = "ไม่พบข้อมูล";
                $_SESSION['td_notif_type'] = 'danger';
                header('Location: tutor_demand.php');
                exit();
            }
        }
    }
}
?>