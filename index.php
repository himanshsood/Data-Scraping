<?php
session_start();

// Initialize job history array if not exists
if (!isset($_SESSION['jobHistory'])) {
    $_SESSION['jobHistory'] = [];
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Display success message if exists
$message = '';
if (isset($_SESSION['message'])) {
    $message = addslashes($_SESSION['message']);
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertBox = document.getElementById('custom-alert');
            alertBox.innerText = '{$message}';
            alertBox.style.display = 'block';

            // Hide after 3 seconds
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000);
        });
    </script>
    ";
    unset($_SESSION['message']);
}
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
    <div id="custom-alert" style="
        display: none;
        position: fixed;
        top: 20px;
        right: 20px;
        font-weight: 400;
        background: linear-gradient(135deg,rgb(0, 237, 39),rgb(63, 234, 92), #00bcd4); /* more subdued, cool-toned gradient */
        color: white;
        padding: 14px 22px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
    "></div>
    
    <div class="header">
        <h1 class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    

    
    <div class="dashboard-content">
        <!-- Forms Section -->
        <?php include 'views/SET_AUTOMATIC.php'; ?>
        <!-- Job History Section -->
        <?php include 'views/JOB_HISTORY.php'; ?>
    </div>

    <script>
        // Manual Update Function (now uses AJAX)
        function manualUpdate() {
            const btn = event.target;
            const originalText = btn.textContent;
            
            btn.textContent = 'Updating...';
            btn.disabled = true;
            
            // AJAX call to server
            fetch('process_form.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'manual_update=true'
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Update failed');
            })
            .finally(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            });
        }

        // Cancel Job Function
        function cancelJob(jobId) {
            if (confirm('Are you sure you want to cancel this job?')) {
                fetch('process_job.php?action=cancel&id=' + jobId)
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        window.location.reload();
                    });
            }
        }

        // Rollback Job Function
        function rollbackJob(jobId) {
            if (confirm('Are you sure you want to rollback this job?')) {
                fetch('process_job.php?action=rollback&id=' + jobId)
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        window.location.reload();
                    });
            }
        }

        // File name display
        document.getElementById('csvFile').addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const file = e.target.files[0];
            
            if (file) {
                fileNameDisplay.textContent = file.name;
                fileNameDisplay.style.color = '#333';
                fileNameDisplay.style.fontStyle = 'normal';
            } else {
                fileNameDisplay.textContent = 'No file selected';
                fileNameDisplay.style.color = '#666';
                fileNameDisplay.style.fontStyle = 'italic';
            }
        });

        // Enable/disable form fields based on checkbox
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
                daySelect.disabled = true;
                timeSelect.disabled = true;
                submitBtn.disabled = true;
                daySelect.style.opacity = '0.5';
                timeSelect.style.opacity = '0.5';
                submitBtn.style.opacity = '0.5';
                daySelect.value = '';
                timeSelect.value = '';
            }
        });

        // Initialize form state
        document.addEventListener('DOMContentLoaded', function() {
            const autoUpdateCheckbox = document.getElementById('enableAutoUpdate');
            if (autoUpdateCheckbox) {
                autoUpdateCheckbox.dispatchEvent(new Event('change'));
            }
        });
    </script>
</body>
</html>