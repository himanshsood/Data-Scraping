<?php
// dashboard.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Include your init.php to initialize session data
require_once 'init.php';

// Your existing dashboard code
require_once 'CSV_UPLOAD_POST.php';
$jobs = array_reverse($_SESSION['jobs']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-text {
            color: #333;
            margin: 0;
        }
        
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        
        .logout-btn:hover {
            background-color: #c82333;
        }
        
        .dashboard-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    
    <div class="dashboard-content">
        <!-- <h2>Dashboard</h2> -->
        <?php include 'views/SET_AUTOMATIC.php'; ?>
        <?php include 'views/csv_upload_form.php'; ?>
        <?php include 'views/header.php'; ?>
        <?php include 'views/job_history.php'; ?>
        <?php include 'views/controls.php'; ?>
    </div>
</body>
</html>
