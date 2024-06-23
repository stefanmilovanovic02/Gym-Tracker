<?php
include 'php/konekcija.php'; // Include database connection

session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['korisnik_id'])) {     
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['korisnik_id'];

// Fetch user's weight logs for the chart
$sql_weight = "SELECT date, weight FROM weights WHERE user_id = ? ORDER BY date ASC";
$stmt_weight = $conn->prepare($sql_weight);
$stmt_weight->bind_param('i', $user_id);
$stmt_weight->execute();
$result_weight = $stmt_weight->get_result();
$weight_logs = [];
while ($row = $result_weight->fetch_assoc()) {
    $weight_logs[] = $row;
}

$stmt_weight->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Charts</title>
    <style>
        body {
            background-color: #333;
            color: #fff;
        }
        .navbar, .card {
            background-color: #444;
        }
        .card {
            margin-bottom: 20px;
        }
        .dropdown-menu {
            background-color: #555;
            color: #fff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="data_page.php">Logo</a>
    <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="add.php">Add Today</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="workout.php">Workouts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="charts.php">Charts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="php/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <div class="btn-group">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Select Chart
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#" onclick="selectChart('weight')">Weight</a>
                <a class="dropdown-item" href="#" onclick="selectChart('exercise')">Exercise</a>
                <!-- Add more chart options here -->
            </div>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" onclick="selectDate()">Select Date</button>
            <input type="date" id="chartDate" class="form-control ml-2" onchange="updateChart()">
        </div>
    </div>

    <div class="card">
        <div class="card-body text-center">
            <div id="overallValues">
                <div class="row">
                    <div class="col">
                        <h5>Start</h5>
                        <p id="startValue">60</p>
                    </div>
                    <div class="col">
                        <h5>Current</h5>
                        <p id="currentValue">60</p>
                    </div>
                    <div class="col">
                        <h5>Change</h5>
                        <p id="changeValue">0kg</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartType = 'weight';
    let chartDate = '';

    function selectChart(type) {
        chartType = type;
        updateChart();
    }

    function selectDate() {
        chartDate = document.getElementById('chartDate').value;
        updateChart();
    }

    function updateChart() {
        // Logic to update chart based on `chartType` and `chartDate`
        // Fetch data from the server and update the chart

        // Example of updating overall values
        if (chartType === 'weight') {
            document.getElementById('startValue').innerText = '60';
            document.getElementById('currentValue').innerText = '60';
            document.getElementById('changeValue').innerText = '0kg';
        } else if (chartType === 'exercise') {
            // Update these values based on exercise data
        }

        // Update chart with new data
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: chartType === 'weight' ? 'Weight Progress' : 'Exercise Progress',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        updateChart();
    });
</script>
</body>
</html>
