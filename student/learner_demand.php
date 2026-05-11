<?php

session_start();

require('../php/config.php');

studentCheck();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ความต้องการเรียน | SPMT</title>
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
                <h4 class="text-primary fw-bold mb-4"><i class="fas fa-book-reader me-2"></i>ความต้องการเรียน</h4>
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
                                        <select name="subject" class="form-select" required>
                                            <option value="">ระบุวิชา</option>
                                            <option value="1">ภาษาไทย</option>
                                            <option value="2">คณิตศาสตร์</option>
                                            <option value="3">วิทยาศาสตร์</option>
                                            <option value="4">สังคมศึกษา</option>
                                            <option value="5">ภาษาอังกฤษ</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="availabledate" class="form-control" required>
                                    </td>
                                    <td>
                                        <select name="availabletime" class="form-select" required>
                                            <option value="">00:00 - 00:00น.</option>
                                            <option value="1">07:00 - 08:00น.</option>
                                            <option value="2">08:00 - 09:00น.</option>
                                            <option value="3">09:00 - 10:00น.</option>
                                            <option value="4">10:00 - 11:00น.</option>
                                            <option value="5">11:00 - 12:00น.</option>
                                            <option value="6">12:00 - 13:00น.</option>
                                            <option value="7">14:00 - 15:00น.</option>
                                            <option value="8">16:00 - 17:00น.</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if (isset($_SESSION['ld_notification'])): ?>
                            <div class="alert alert-<?= $_SESSION['ld_notif_type'] ?> alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <i class="fas fa-info-circle me-2"></i><?php echo $_SESSION['ld_notification']; ?>
                            </div>
                            <?php unset($_SESSION['ld_notification']);
                            unset($_SESSION['ld_notif_type']); ?>
                        <?php endif; ?>
                        <button type="submit" name="learner_submit" class="btn btn-primary shadow-sm mt-2 px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
                    </div>
                </form>
            </div>

            <div class="card card-custom">
                <h5 class="fw-bold mb-4"><i class="fas fa-list me-2"></i>ระบบจับคู่การเรียนการสอน</h5>
                <form action="learner_act.php" method="POST">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">วิชา</th>
                                    <th class="text-center">ปี/เดือน/วัน</th>
                                    <th class="text-center">ช่วงเวลา</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $user_id = $_SESSION['user_id'];
                                $ld_sql = "SELECT ld.*,
                                            s.subject_name,
                                            tl.teaching_log_id,
                                            tl.ld_id AS log_ld_id
                                            FROM learner_demand ld
                                            LEFT JOIN teaching_log tl
                                            ON ld.ld_id = tl.ld_id
                                            LEFT JOIN subject s
                                            ON ld.subject_id = s.subject_id
                                            WHERE ld.user_id = '$user_id'
                                            ORDER BY ld.convenience_day";

                                $ld_result = $conn->query($ld_sql);

                                if (mysqli_num_rows($ld_result) > 0) {
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($ld_result)) {
                                        $subject = $data['subject_name'];
                                        $date = $data['convenience_day'];
                                        $timePeriod = $data['convenience_time'];
                                        $time = timeSwitch($timePeriod);
                                        $ld_id = $data['ld_id'];
                                        $log_ld_id = $data['log_ld_id'];
                                ?>
                                        <tr> 
                                            <td class="text-center"><?php echo $subject; ?></td>
                                            <td class="text-center"><?php echo $date; ?></td>
                                            <td class="text-center"><?php echo $time; ?></td>
                                            <td class="text-center">
                                                <?php if(!$log_ld_id): ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning mb-1">บันทึกสำเร็จ โปรดจับคู่</span><br>
                                                <?php else: ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success mb-1">ผู้สอนยืนยัน</span><br>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if(!$log_ld_id): ?>
                                                        <a href="learner_act.php?type=match&ld_id=<?php echo $data['ld_id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-hands-helping me-1"></i> จับคู่</a>
                                                        <a href="learner_act.php?type=edit&ld_id=<?php echo $ld_id ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i>แก้ไข</a>
                                                        <a href="learner_act.php?type=delete&ld_id=<?php echo $ld_id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('ยืนยันการลบ?')"><i class="fas fa-trash"></i>ลบ</a>
                                                <?php else: ?>
                                                    <a href="learner_act.php?type=ld_detail&teaching_log_id=<?= $data['teaching_log_id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-info-circle me-1"></i>รายละเอียด</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">ไม่พบข้อมูลความต้องการเรียน</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>