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
                <form action="student_act.php" method="post">
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
                                            $user = $_SESSION['user_id'];
                                            $sql = "SELECT grade FROM users WHERE user_id='$user'";
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
                        <button type="submit" name="tutor_submit" class="btn btn-primary shadow-sm mt-2 px-4"><i class="fas fa-hands-helping me-2"></i>จับคู่</button>
                        <p><?= $_SESSION['td_notification'] ?></p>
                    </div>
                </form>
            </div>

            <div class="card card-custom">
                <h5 class="fw-bold mb-4"><i class="fas fa-list me-2"></i>รายการความต้องการสอนของคุณ</h5>
                <form action="student_act.php" method="POST">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>วิชา</th>
                                    <th>ระดับชั้น</th>
                                    <th>ปี/เดือน/วัน</th>
                                    <th>ช่วงเวลา</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $user_id = $_SESSION['user_id'];

                                $td_sql = $td_sql = "SELECT 
                                            td.td_id, 
                                            tl.teaching_log_id, 
                                            s.subject_name,
                                            td.teaching_level,
                                            td.available_day,
                                            td.available_time
                                        FROM teaching_demand td
                                        LEFT JOIN teaching_log tl ON td.td_id = tl.td_id
                                        LEFT JOIN subject s ON td.subject_id = s.subject_id
                                        WHERE td.user_id = '$user_id'";

                                $td_result = $conn->query($td_sql);

                                if (mysqli_num_rows($td_result) > 0) {
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($td_result)) {
                                ?>
                                        <tr>
                                            <td class="text-center fw-bold"><?php echo $i; ?></td>
                                            <td><?php echo $data['subject_name'] ?></td>
                                            <td><?php echo "ชั้นมัธยมศึกษาปีที่ " . $data['teaching_level'] ?></td>
                                            <td><?php echo $data['available_day']; ?></td>
                                            <td><?php $no = $data['available_time'];
                                                timeSwitch($no); ?></td>
                                            <td class="text-center">
                                                <?php if($data['teaching_log_id']): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success mb-1">จับคู่สำเร็จ</span><br>
                                                    <a href="student_act.php?type=edit&teaching_log_id=<?= $data['teaching_log_id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i>ยืนยันการสอน</a>
                                                <?php else: ?>
                                                    <span class="badge bg-warning bg-opacity-10 text-warning">รอกับคู่</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">ไม่พบข้อมูลความต้องการเรียน</td>
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