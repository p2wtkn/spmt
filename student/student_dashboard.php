<?php

session_start();

require('../php/config.php');

studentCheck();

$data = pointRanking($conn);

$user_id = $_SESSION['user_id'];

$userpts = userPoints($conn, $user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard นักเรียน | SPMT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/student.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-custom">
        <?php
        
        include('student_sidebar.html');
        
        ?>
        <div class="main">
            <div class="profile">
                <h1>ข้อมูลผู้ใช้</h1>
                <p><?= $_SESSION['title'] . " " . $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></p>
                <p><?= "ชั้นมัธยมศึกษาปีที่ " . $_SESSION['grade']; ?></p>
                <p>Merit Point ของคุณ<span style="color: #1e3a8a;"><?php echo $userpts; ?> pts</span></p>
            </div>
            <div class="ranking">
                <h2>อันดับคะแนนติวเตอร์ (วันนี้)</h2>
                <canvas id="tutorChart"></canvas>
                <script>
                    new Chart(document.getElementById('tutorChart'), {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($data['tutors']); ?>,
                            datasets: [{
                                label: 'คะแนนความดีสะสม',
                                data: <?php echo json_encode($data['points']); ?>,
                                backgroundColor: '#F59E0B'
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</body>

</html>