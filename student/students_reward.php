<?php

session_start();

require('../php/config.php');

studentCheck();

$pts_sql = "SELECT points FROM users WHERE user_id = '{$_SESSION['user_id']}'";
$pts_result = $conn->query($pts_sql);
$pts = $pts_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward นักเรียน | SPMT</title>
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
            <div class="merit-banner">
                <small>Merit Points ของคุณ</small>
                <h1 class="fw-bold"><?php echo $pts['points']; ?> pts</h1>
            </div>

            <div class="card card-custom p-4">
                <h4 class="text-primary fw-bold mb-4"><i class="fas fa-gift me-2"></i>แลกของรางวัล</h4>

                <?php if (isset($_SESSION['reward_notification'])): ?>
                    <div class="alert alert-<?= $_SESSION['reward_notif_type'] ?> alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i><?= $_SESSION['reward_notification'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['reward_notification']);
                    unset($_SESSION['reward_notif_type']); ?>
                <?php endif; ?>

                <div class="row g-4">
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM reward");
                    $userpts = $pts['points'];
                    while ($row = mysqli_fetch_assoc($res)) {
                        $can_redeem = ($userpts >= $row['required_points']);
                    ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card reward-card p-4 h-100 shadow-sm d-flex flex-column">
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-2 text-dark"><?php echo $row['reward_name']; ?></h5>
                                    <p class="text-muted small mb-3"><?php echo $row['description']; ?></p>
                                    <div class="fw-bold mb-3" style="color: #f59e0b; font-size: 1.25rem;"><i class="fas fa-coins me-2"></i><?php echo $row['required_points']; ?> pts</div>
                                </div>

                                <form action="student_act.php" method="POST">
                                    <input type="hidden" name="type" value="redeem_reward">
                                    <input type="hidden" name="reward_id" value="<?php echo $row['reward_id']; ?>">
                                    <input type="hidden" name="required_points" value="<?php echo $row['required_points']; ?>">
                                    <button type="submit" class="btn fw-bold shadow-sm w-100 <?php echo $can_redeem ? 'btn-primary' : 'btn-light text-muted'; ?>" <?php echo !$can_redeem ? 'disabled' : ''; ?>>
                                        Redeem
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>