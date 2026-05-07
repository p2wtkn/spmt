<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../php/config.php');

$type = $_GET['type'];

if ($type == "check") {
    $id = $_GET['id'];
    $sql = "SELECT tl.*, td.*, u.*
    FROM teaching_log tl 
    LEFT JOIN teaching_demand td ON tl.td_id = td.td_id
    LEFT JOIN users u ON td.user_id = u.user_id
    WHERE teaching_log_id='$id'";
    $sql1 = "SELECT tl.*, ld.*, u.*
    FROM teaching_log tl 
    LEFT JOIN learner_demand ld ON tl.ld_id = ld.ld_id
    LEFT JOIN users u ON ld.user_id = u.user_id
    WHERE teaching_log_id='$id'";
    $result = mysqli_query($conn, $sql);
    $result1 = mysqli_query($conn, $sql1);
    $data = mysqli_fetch_assoc($result);
    $data1 = mysqli_fetch_assoc($result1);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['type'] == 'update_confirm') {
    $id = intval($_POST['teaching_log_id']);
    $status = intval($_POST['teacher_confirmed']);
    $note = mysqli_real_escape_string($conn, $_POST['checkup_note']);

    if ($status == 0 && empty($note)) {
        echo "<script>alert('กรุณากรอกหมายเหตุ'); history.back();</script>";
        exit;
    }

    $sql_log = "UPDATE teaching_log SET 
            teacher_confirmed = '$status',
            confirm_date = NOW(), 
            checkup_note = '$note' 
            WHERE teaching_log_id = '$id'";

    if (mysqli_query($conn, $sql_log)) {

        if ($status == 1) {
            $sql_point = "UPDATE users u
                      JOIN teaching_demand td ON u.user_id = td.user_id
                      JOIN teaching_log tl ON td.td_id = tl.td_id
                      SET u.points = u.points + 10
                      WHERE tl.teaching_log_id = '$id'";

            mysqli_query($conn, $sql_point);
        }

        echo "<script>alert('บันทึกและให้คะแนนสำเร็จ'); window.location='teaching_checkup.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบข้อมูลการสอน | SPMT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/teacher.css">
</head>

<body>
    <div class="container-custom">
        <div class="sidebar">
            <h2><a href="../index.php">SPMT | คุณครู</a></h2>
            <a href="teacher_dashboard.php">Dashboard</a>
            <a href="teaching_checkup.php">Checkup</a>
            <a href="reward_management.php">Reward</a>
            <a href="../auth/logout.php" class="btn-logout">ออกจากระบบ</a>
        </div>

        <div class="main">
            <div class="card card-custom p-4 mx-auto" style="max-width: 800px; width: 100%;">
                <h4 class="text-primary fw-bold mb-4"><i class="fas fa-clipboard-check me-2"></i>รายละเอียดการตรวจสอบ</h4>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded shadow-sm border h-100">
                            <h6 class="fw-bold text-dark"><i class="fas fa-chalkboard-teacher text-primary me-2"></i>ข้อมูลผู้สอน</h6>
                            <p class="mb-1"><strong>ชื่อ-สกุล:</strong> <?php echo $data['first_name'] . " " . $data['last_name']; ?></p>
                            <p class="mb-0"><strong>สถานะการยืนยัน:</strong> 
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle me-1"></i><?php if ($data['tutor_confirmed'] == 1) { echo "ยืนยันแล้ว"; } ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded shadow-sm border h-100">
                            <h6 class="fw-bold text-dark"><i class="fas fa-user-graduate text-primary me-2"></i>ข้อมูลผู้เรียน</h6>
                            <p class="mb-1"><strong>ชื่อ-สกุล:</strong> <?php echo $data1['first_name'] . " " . $data1['last_name']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="mb-4 p-3 bg-light rounded shadow-sm border">
                    <h6 class="fw-bold text-dark"><i class="fas fa-file-alt text-primary me-2"></i>หลักฐานการสอน</h6>
                    <a href="<?php $data['evidence']; ?>" class="btn btn-outline-primary btn-sm mt-2" target="_blank"><i class="fas fa-external-link-alt me-2"></i>กดดูหลักฐานที่แนบไว้</a>
                </div>

                <hr class="text-muted">

                <form action="log_form.php" method="post" onsubmit="return validateForm()">
                    <input type="hidden" name="type" value="update_confirm">
                    <input type="hidden" name="teaching_log_id" value="<?= $id ?>">

                    <div class="mb-3">
                        <label for="teacher_confirmed" class="form-label fw-bold">การอนุมัติหลังการตรวจสอบ</label>
                        <select name="teacher_confirmed" id="teacher_confirmed" class="form-select" onchange="toggleNoteRequired()">
                            <option value="0">ยังไม่ยืนยัน / ไม่อนุมัติ</option>
                            <option value="1">ยืนยันแล้ว (อนุมัติ)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="checkup_note" class="form-label fw-bold">หมายเหตุการตรวจสอบ</label>
                        <input type="text" name="checkup_note" id="checkup_note" class="form-control"
                            value="<?= htmlspecialchars($data['checkup_note'] ?? "") ?>"
                            placeholder="ระบุเหตุผลหากไม่อนุมัติ หรือหมายเหตุเพิ่มเติม">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="teaching_checkup.php" class="btn btn-secondary px-4"><i class="fas fa-arrow-left me-2"></i>ย้อนกลับ</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>บันทึกข้อมูล</button>
                    </div>
                </form>

                <script>
                    function toggleNoteRequired() {
                        const status = document.getElementById('teacher_confirmed').value;
                        const noteField = document.getElementById('checkup_note');
                        noteField.required = (status == "0");
                    }

                    function validateForm() {
                        const status = document.getElementById('teacher_confirmed').value;
                        const note = document.getElementById('checkup_note').value.trim();
                        if (status == "0" && note === "") {
                            alert("กรุณากรอก Note เพื่อระบุเหตุผลที่ยังไม่ยืนยัน");
                            return false;
                        }
                        return true;
                    }
                    toggleNoteRequired();
                </script>
            </div>
        </div>
    </div>
</body>

</html>