<?php

session_start();

require('../php/config.php');

teacherCheck();

$sql = "SELECT YEAR(ld.convenience_day) AS year,
               s.subject_name,
               COUNT(ld.ld_id) AS total_requests
        FROM learner_demand ld
        JOIN subject s ON ld.subject_id = s.subject_id
        GROUP BY year, s.subject_name
        ORDER BY year ASC, s.subject_name ASC";

$result = mysqli_query($conn, $sql);

$subjects = [];
$dataByYear = [];

while ($row = mysqli_fetch_assoc($result)) {
    $year = $row['year'];
    $subject = $row['subject_name'];
    $count = $row['total_requests'];

    if (!in_array($subject, $subjects)) {
        $subjects[] = $subject;
    }

    if (!isset($dataByYear[$year])) {
        $dataByYear[$year] = [];
    }

    $dataByYear[$year][$subject] = $count;
}

// เติมค่า 0 ถ้าวิชานั้นไม่มีในปีนั้น
foreach ($dataByYear as $year => $subjectCounts) {
    foreach ($subjects as $subject) {
        if (!isset($dataByYear[$year][$subject])) {
            $dataByYear[$year][$subject] = 0;
        }
    }
}

// กำหนดสีประจำวิชา
$subjectColors = [
    "ภาษาไทย" => "#2563EB",
    "คณิตศาสตร์" => "#10B981",
    "วิทยาศาสตร์" => "#F59E0B",
    "สังคม" => "#EF4444",
    "ภาษาอังกฤษ" => "#8B5CF6"
];

// เตรียม datasets เป็น array
$datasets = [];
foreach ($dataByYear as $year => $subjectCounts) {
    $datasets[] = [
        "label" => $year,
        "data" => array_values($subjectCounts),
        "backgroundColor" => array_map(function ($subject) use ($subjectColors) {
            return $subjectColors[$subject] ?? "#999999";
        }, $subjects)
    ];
}

$sql1 = "SELECT MONTH(create_at) AS month, COUNT(*) AS total_users 
         FROM users 
         GROUP BY MONTH(create_at) 
         ORDER BY MONTH(create_at)";

$result1 = $conn->query($sql1);

$months = [];
$users = [];

while ($row = $result1->fetch_assoc()) {

    $months[] = date("F", mktime(0, 0, 0, $row['month'], 10));
    $users[] = $row['total_users'];
}

$sql3 = "SELECT MONTH(confirm_date) AS month, COUNT(*) AS total_matches
         FROM teaching_log
         WHERE teacher_confirmed='1'
         GROUP BY MONTH(confirm_date)
         ORDER BY MONTH(confirm_date)";
$result3 = $conn->query($sql3);
$matchMonths = [];
$matches = [];
while ($row = $result3->fetch_assoc()) {
    $matchMonths[] = date("F", mktime(0, 0, 0, $row['month'], 10));
    $matches[] = $row['total_matches'];
}

$sql4 = "SELECT first_name, points
         FROM users
         ORDER BY points DESC
         LIMIT 5";
$result4 = $conn->query($sql4);
$tutors = [];
$points = [];
while ($row = $result4->fetch_assoc()) {
    $tutors[] = $row['first_name'] . " " . $row['last_name'];
    $points[] = $row['points'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard คุณครู | SPMT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/teacher.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-custom">
        <?php
        
        include('teacher_sidebar.html');
        
        ?>
        <div class="main">
            <div class="profile">
                <h1>ข้อมูลผู้ใช้</h1>
                <p><?= $_SESSION['title'] . " " . $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></p>
                <p><?= "ชั้นมัธยมศึกษาปีที่ " . $_SESSION['grade']; ?></p>
            </div>

            <!-- Dashboard Charts -->
            <div class="dashboard-grid">
                
                <!-- การเติบโตของจำนวนผู้ใช้ -->
                <div class="chart-card">
                    <h2>การเติบโตของจำนวนผู้ใช้</h2>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="userChart"></canvas>
                    </div>
                </div>

                <!-- จำนวนการจับคู่การเรียนการสอนสำเร็จของผู้ใช้ -->
                <div class="chart-card">
                    <h2>จำนวนการจับคู่สำเร็จ</h2>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="matchChart"></canvas>
                    </div>
                </div>

                <!-- กราฟจำนวนคำขอเรียนรายปี (Grouped Bar Chart) -->
                <div class="chart-card full-width">
                    <h2>จำนวนคำขอเรียนแยกตามวิชา (รายปี)</h2>
                    <div style="position: relative; height: 350px; width: 100%;">
                        <canvas id="subjectChart"></canvas>
                    </div>
                </div>

                <!-- อันดับคะแนนติวเตอร์ -->
                <div class="chart-card full-width">
                    <h2>อันดับคะแนนติวเตอร์ (Top 5)</h2>
                    <div style="position: relative; height: 350px; width: 100%;">
                        <canvas id="tutorChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        // 1. User Chart
        const ctxUser = document.getElementById('userChart').getContext('2d');
        new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Total Users',
                    data: <?php echo json_encode($users); ?>,
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244, 63, 94, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 2. Match Chart
        new Chart(document.getElementById('matchChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($matchMonths); ?>,
                datasets: [{
                    label: 'การจับคู่สำเร็จ',
                    data: <?php echo json_encode($matches); ?>,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16,185,129,0.3)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 3. Subject Chart
        const labelsSubject = <?php echo json_encode($subjects, JSON_UNESCAPED_UNICODE); ?>;
        const datasetsSubject = <?php echo json_encode($datasets, JSON_UNESCAPED_UNICODE); ?>;
        new Chart(document.getElementById('subjectChart'), {
            type: 'bar',
            data: {
                labels: labelsSubject,
                datasets: datasetsSubject
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: false }
                },
                scales: { y: { beginAtZero: true } }
            }
        });

        // 4. Tutor Chart
        new Chart(document.getElementById('tutorChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($tutors); ?>,
                datasets: [{
                    label: 'คะแนนความดีสะสม',
                    data: <?php echo json_encode($points); ?>,
                    backgroundColor: '#F59E0B'
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>

</html>