<?php

session_start();

require('../php/config.php');

teacherCheck();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบการเรียนการสอน | SPMT</title>
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
            <div class="card card-custom p-4">
                <h4 class="text-primary fw-bold mb-4"><i class="fas fa-clipboard-check me-2"></i>ตรวจสอบการเรียนการสอน</h4>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>หลักฐานการสอน</th>
                                <th class="text-center">ผู้สอนยืนยัน</th>
                                <th class="text-center">คุณครูยืนยัน</th>
                                <th>หมายเหตุ</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM teaching_log";
                                    // --WHERE teacher_confirmed = 0";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $i=1;
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <tr>
                                        <td class="text-center fw-bold text-muted"><?php echo $i; ?></td>
                                        
                                        <td>
                                            <?php if(isset($row['evidence']) && $row['evidence'] != ''): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fas fa-image me-1"></i>มีหลักฐาน</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary"><i class="fas fa-times me-1"></i>ไม่มีหลักฐาน</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if($row['tutor_confirmed'] == 1): ?>
                                                <span class="text-success"><i class="fas fa-check-circle"></i> ยืนยัน</span>
                                            <?php else: ?>
                                                <span class="text-warning"><i class="fas fa-clock"></i> รอยืนยัน</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php echo $row['teacher_confirmed'] == 1 ? '<span class="text-success fw-bold">อนุมัติ</span>' : '<span class="text-danger fw-bold">ไม่อนุมัติ / รอตรวจสอบ</span>'; ?>
                                        </td>

                                        <td class="text-muted small">
                                            <?php echo (isset($row['checkup_note']) && $row['checkup_note'] != '') ? $row['checkup_note'] : '-'; ?>
                                        </td>

                                        <td class="text-center">
                                            <a href="log_form.php?type=check&id=<?php echo $row['teaching_log_id']; ?>" class="btn btn-sm btn-primary shadow-sm"><i class="fas fa-search me-1"></i>ตรวจสอบ</a>
                                        </td>
                                    </tr>
                            <?php
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center py-4 text-muted'>ไม่มีการเรียนการสอนที่ต้องตรวจสอบ</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>