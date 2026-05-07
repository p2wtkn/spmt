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
                <form action="student_act.php" method="post">
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
                        <button type="submit" name="learner_submit" class="btn btn-primary shadow-sm mt-2 px-4"><i class="fas fa-save me-2"></i>บันทึก</button>
                        <p><?= $_SESSION['ld_notification'] ?></p>
                    </div>
                </form>
            </div>

            <div class="card card-custom">
                <h5 class="fw-bold mb-4"><i class="fas fa-list me-2"></i>ระบบจับคู่การเรียนการสอน</h5>
                <form action="student_act.php" method="POST">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>วิชา</th>
                                    <th>ปี/เดือน/วัน</th>
                                    <th>ช่วงเวลา</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $user_id = $_SESSION['user_id'];
                                $ld_sql = "SELECT ld.*, s.subject_name
                                        FROM learner_demand ld
                                        LEFT JOIN subject s ON ld.subject_id = s.subject_id
                                        WHERE user_id='$user_id'";
                                $result = $conn->query($ld_sql);

                                if (mysqli_num_rows($result) > 0) {
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($result)) {
                                ?>
                                        <tr> 
                                            <td class="text-center fw-bold"><?php echo $i; ?></td>
                                            <td><?php echo $data['subject_name'] ?></td>
                                            <td><?php echo $data['convenience_day']; ?></td>
                                            <td><?php $no = $data['convenience_time'];
                                                timeSwitch($no); ?></td>
                                            <td class="text-center">
                                                <a href="student_act.php?type=match&ld_id=<?php echo $data['ld_id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-hands-helping me-1"></i> จับคู่</a>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">ไม่พบข้อมูลความต้องการเรียน</td>
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