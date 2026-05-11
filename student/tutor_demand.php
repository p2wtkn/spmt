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
    <title>ความต้องการช่วยสอน | SPMT</title>
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
                <h4 class="text-primary fw-bold mb-4"><i class="fas fa-chalkboard-teacher me-2"></i>ความต้องการช่วยสอน</h4>
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
                                        <select name="level" class="form-select" required>
                                            <option value="">ระดับชั้น</option>
                                            <?php

                                            $user_id = $_SESSION['user_id'];

                                            $sql = "SELECT grade
                                                    FROM users
                                                    WHERE user_id='$user_id'";
                                            $result = mysqli_query($conn, $sql);
                                            $row = $result->fetch_assoc();

                                            $i = 1;
                                            while ($i <= $row['grade']) {
                                            ?>
                                                <option value="<?php echo $i ?>">มัธยมศึกษาชั้นปีที่ <?php echo $i ?></option>
                                            <?php
                                                $i++;
                                            }

                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="date" name="availabledate" class="form-control" required>
                                    </td>
                                    <td>
                                        <select name="availabletime" class="form-select" required>
                                            <option value="">ช่วงเวลา</option>
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
                        <?php if (isset($_SESSION['td_notification'])): ?>
                            <div class="alert alert-<?= $_SESSION['td_notif_type'] ?> alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <i class="fas fa-info-circle me-2"></i><?php echo $_SESSION['td_notification']; ?>
                            </div>
                            <?php unset($_SESSION['td_notification']);
                            unset($_SESSION['td_notif_type']); ?>
                        <?php endif; ?>
                        <button type="submit" name="tutor_submit" class="btn btn-primary shadow-sm mt-2 px-4"><i class="fas fa-hands-helping me-2"></i>จับคู่</button>
                    </div>
                </form>
            </div>

            <div class="card card-custom">
                <h5 class="fw-bold mb-4"><i class="fas fa-list me-2"></i>รายการความต้องการสอนของคุณ</h5>
                <form action="tutor_act.php" method="POST">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">วิชา</th>
                                    <th class="text-center">ระดับชั้น</th>
                                    <th class="text-center">ปี/เดือน/วัน</th>
                                    <th class="text-center">ช่วงเวลา</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $user_id = $_SESSION['user_id'];

                                $td_sql = "SELECT 
                                                td.*, 
                                                tl.teaching_log_id,
                                                tl.tutor_confirmed, 
                                                s.subject_name
                                            FROM teaching_demand td
                                            LEFT JOIN teaching_log tl
                                            ON td.td_id = tl.td_id
                                            LEFT JOIN subject s
                                            ON td.subject_id = s.subject_id
                                            WHERE td.user_id = '$user_id'
                                            ORDER BY td.available_day";

                                $td_result = $conn->query($td_sql);

                                if (mysqli_num_rows($td_result) > 0) {
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($td_result)) {
                                        $subject = $data['subject_name'];
                                        $grade = $data['teaching_level'];
                                        $date = $data['available_day'];
                                        $timePeriod = $data['available_time'];
                                        $time = timeSwitch($timePeriod);
                                        $match_id = $data['teaching_log_id'];
                                        $td_id = $data['td_id'];
                                        $tutor_confirmed = $data['tutor_confirmed'];
                                ?>
                                        <tr>
                                            <?php if($tutor_confirmed == 0):?>
                                                <td class="text-center"><?php echo $subject; ?></td>
                                                <td class="text-center"><?php echo "ชั้นมัธยมศึกษาปีที่ " . $grade; ?></td>
                                                <td class="text-center"><?php echo $date; ?></td>
                                                <td class="text-center"><?php echo $time; ?></td>
                                                <td class="text-center">
                                                    <?php if($match_id): ?>
                                                        <span class="badge bg-success bg-opacity-10 text-success mb-1">จับคู่สำเร็จ</span><br>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning bg-opacity-10 text-warning">รอจับคู่</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($match_id): ?>
                                                        <a href="tutor_act.php?type=td_detail&teaching_log_id=<?= $data['teaching_log_id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-info-circle me-1"></i>รายละเอียด</a>
                                                    <?php else: ?>
                                                        <a href="tutor_act.php?type=edit&td_id=<?php echo $td_id ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i>แก้ไข</a>
                                                        <a href="tutor_act.php?type=delete&td_id=<?php echo $td_id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('ยืนยันการลบ?')"><i class="fas fa-trash"></i>ลบ</a>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif;?>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">เพิ่มความต้องการสอนของคุณ</td>
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