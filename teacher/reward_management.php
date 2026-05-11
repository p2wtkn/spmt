<?php

session_start();

require('../php/config.php');

teacherCheck();

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];

    mysqli_query($conn, "DELETE FROM reward WHERE reward_id = $id");
    header("Location: reward_management.php");
    exit;
}

if (isset($_POST['update_reward'])) {
    $id = (int)$_POST['reward_id'];
    $name = mysqli_real_escape_string($conn, $_POST['reward_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $points = (int)$_POST['required_points'];
    $quota = (int)$_POST['reward_quota'];

    $sql = "UPDATE reward SET reward_name='$name', description='$desc', required_points='$points', reward_quota='$quota' WHERE reward_id=$id";
    mysqli_query($conn, $sql);
    header("Location: reward_management.php");
    exit;
}

if (isset($_POST['save_new'])) {
    $names = $_POST['reward_name'];
    $descs = $_POST['description'];
    $points = $_POST['required_points'];
    $quotas = $_POST['reward_quota'];

    foreach ($names as $key => $val) {
        if (!empty($val)) {
            $n = mysqli_real_escape_string($conn, $val);
            $d = mysqli_real_escape_string($conn, $descs[$key]);
            $p = (int)$points[$key];
            $q = (int)$quotas[$key];
            mysqli_query($conn, "INSERT INTO reward (reward_name, description, required_points, reward_quota) VALUES ('$n', '$d', '$p', '$q')");
        }
    }
    header("Location: reward_management.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการของรางวัล | SPMT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/teacher.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container-custom">
        <?php
        
        include('teacher_sidebar.html');
        
        ?>

        <div class="main">
            <div class="card card-custom">
                <h4 class="text-primary fw-bold mb-4"><i class="fas fa-gift me-2"></i>จัดการของรางวัล</h4>
                <form action="reward_management.php" method="post">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle" id="dynamic_field">
                            <thead class="small text-muted">
                                <tr>
                                    <th>ชื่อรางวัล</th>
                                    <th>รายละเอียด</th>
                                    <th width="120">คะแนน</th>
                                    <th width="120">จำนวน</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="reward_name[]" class="form-control" required></td>
                                    <td><input type="text" name="description[]" class="form-control"></td>
                                    <td><input type="number" min="1" max="200" name="required_points[]" class="form-control" required></td>
                                    <td><input type="number" min="1" max="20" name="reward_quota[]" class="form-control" required></td>
                                    <td><button type="button" id="add" class="btn btn-success"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" name="save_new" class="btn btn-primary shadow-sm mt-2 px-4"><i class="fas fa-save me-2"></i>บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>

            <div class="card card-custom">
                <h5 class="fw-bold mb-4"><i class="fas fa-list me-2"></i>รายการรางวัลที่มีในระบบ</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ชื่อรางวัล</th>
                                <th>รายละเอียด</th>
                                <th class="text-center">คะแนน</th>
                                <th class="text-center">จำนวน</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($conn, "SELECT *
                                                        FROM reward
                                                        ORDER BY reward_id DESC");
                            while ($row = mysqli_fetch_assoc($res)) {
                            ?>
                                <tr>
                                    <form action="reward_management.php" method="post">
                                        <input type="hidden" name="reward_id" value="<?php echo $row['reward_id']; ?>">

                                        <td class="view-mode"><?php echo $row['reward_name']; ?></td>
                                        <td class="view-mode text-muted small"><?php echo $row['description']; ?></td>
                                        <td class="view-mode text-center"><?php echo number_format($row['required_points']); ?></td>
                                        <td class="view-mode text-center"><?php echo $row['reward_quota']; ?></td>

                                        <td class="edit-mode"><input type="text" name="reward_name" class="form-control form-control-sm" value="<?php echo $row['reward_name']; ?>"></td>
                                        <td class="edit-mode"><input type="text" name="description" class="form-control form-control-sm" value="<?php echo $row['description']; ?>"></td>
                                        <td class="edit-mode"><input min="1" max="200" type="number" name="required_points" class="form-control form-control-sm" value="<?php echo $row['required_points']; ?>"></td>
                                        <td class="edit-mode"><input min="1" max="20" type="number" name="reward_quota" class="form-control form-control-sm" value="<?php echo $row['reward_quota']; ?>"></td>

                                        <td class="text-center">
                                            <div class="view-mode btn-group">
                                                <button type="button" class="btn btn-outline-primary btn-sm btn-edit"><i class="fas fa-edit"></i></button>
                                                <a href="reward_management.php?delete_id=<?php echo $row['reward_id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('ยืนยันการลบ?')"><i class="fas fa-trash"></i></a>
                                            </div>
                                            <div class="edit-mode btn-group">
                                                <button type="submit" name="update_reward" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
                                                <button type="button" class="btn btn-secondary btn-sm btn-cancel"><i class="fas fa-times"></i></button>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#add').click(function() {
                $('#dynamic_field tbody').append('<tr>' +
                    '<td><input type="text" name="reward_name[]" class="form-control" required></td>' +
                    '<td><input type="text" name="description[]" class="form-control"></td>' +
                    '<td><input type="number" min="1" max="200" name="required_points[]" class="form-control" required></td>' +
                    '<td><input type="number" min="1" max="20" name="reward_quota[]" class="form-control" required></td>' +
                    '<td><button type="button" class="btn btn-danger btn_remove"><i class="fas fa-times"></i></button></td></tr>');
            });
            $(document).on('click', '.btn_remove', function() {
                $(this).closest('tr').remove();
            });

            // เปิด/ปิด โหมดแก้ไข
            $(document).on('click', '.btn-edit', function() {
                let row = $(this).closest('tr');
                row.find('.view-mode').hide();
                row.find('.edit-mode').show();
            });
            $(document).on('click', '.btn-cancel', function() {
                let row = $(this).closest('tr');
                row.find('.edit-mode').hide();
                row.find('.view-mode').show();
            });
        });
    </script>
</body>

</html>