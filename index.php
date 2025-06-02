<?php
session_start();




// 1) CHECK AUTH
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// 2) NOTIFICATIONS VARIABLE AND LOGIC ! 
require './PhpController/2_AlertMessage.php' ; 

// 3) FETCH THE SCHEDULED DATA 
require './PhpController/3_FetchScheduledData.php' ; 

// 4) FETCH JOB/PROCESS HISTORY AND CURRENT RUNNING PROCESSES 
require './PhpController/8_FetchJobHistory.php' ; 

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div id="custom-alert" style="display: none;position: fixed;top: 20px;right: 20px;font-weight: 400;background: linear-gradient(135deg,rgb(0, 237, 39),rgb(63, 234, 92), #00bcd4); /* more subdued, cool-toned gradient */color: white;padding: 14px 22px;border-radius: 6px;box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);z-index: 9999;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;"></div>

    <div class="header">
        <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="./PhpController/logout.php" class="logout-btn">Logout</a>
    </div>   

    <div class="dashboard-content">
        <!-- Forms Section -->
        <?php include 'views/1_Schedule.php'; ?>
        <?php include 'views/2_Manual.php'; ?>
        <?php include 'views/3_UploadCSV.php'; ?>
        <!-- Job History Section -->
        <?php include 'views/job_history.php'; ?>
        <?php include 'views/4_Preview.php'; ?>
        <?php include 'views/5_Loading.php' ; ?>
    </div>







    <script>
        // AUTO UPDATE
        // FORM 1 : USED PHP VARIABLES : ENABLE/DISABLE FROM FIELS BASED ON CHECKBOX !
        document.getElementById('enableAutoUpdate').addEventListener('change', function() {
            const daySelect = document.getElementById('daySelect');
            const timeSelect = document.getElementById('timeSelect');
            const submitBtn = document.querySelector('#autoUpdateForm button[type="submit"]');
            
            if (this.checked) {
                daySelect.disabled = false;
                timeSelect.disabled = false;
                submitBtn.disabled = false;
                daySelect.style.opacity = '1';
                timeSelect.style.opacity = '1';
                submitBtn.style.opacity = '1';
            } else {
                let deleteRequest = <?php echo ($automatic) ? 'true' : 'false'; ?>;
                if(deleteRequest) {
                    submitBtn.disabled = false;
                }
                else {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                }
                daySelect.disabled = true;
                timeSelect.disabled = true;
                daySelect.style.opacity = '0.5';
                timeSelect.style.opacity = '0.5';
                daySelect.value = $day;
                timeSelect.value = $time;
            }
        });
    </script>
    <script src="./js/6_setUsersTime.js"></script>
    <script src="./js/1_AutomaticForm.js"></script>
    <script src="./js/2_ManualUpdateForm.js"></script>
    <script src="./js/3_UploadCSV.js"></script> 
    <script src="./js/4_TerminateProcess.js"></script>
    <script src="./js/5_Lock.js"></script>
    <script src="./js/7_SubmitCSVUpload.js"></script>
    <script>
        let isRunning = <?php echo json_encode($ISPROCESSRUNNING); ?>;
        lock(isRunning);
    </script>
</body>
</html>