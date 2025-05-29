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


$automatic = false;
$day = "";
$time = "";
// Your Flask API endpoint URL
$apiUrl = "http://localhost:5000/api/schedules/active";  // no trailing slash needed, backend has strict_slashes=False
// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Execute request
$response = curl_exec($ch);
if ($response === false) {
    error_log("cURL Error: " . curl_error($ch));
} else {
    $data = json_decode($response, true);
    if (
        isset($data['status']) && $data['status'] === 'success' &&
        isset($data['data']) && is_array($data['data']) && count($data['data']) > 0
    ) {
        $schedule = $data['data'][0];  // Note: 'data' key, not 'schedules'
        // Check is_active explicitly (boolean true or 1)
        if (isset($schedule['is_active']) && ($schedule['is_active'] === true || intval($schedule['is_active']) === 1)) {
            $automatic = true;
        } else {
            $automatic = false;
        }
        $day = $schedule['day_of_week'] ?? "";
        $time = $schedule['schedule_time'] ?? "";
    }
}
curl_close($ch);



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
        <?php include 'views/job_history.php'; ?>
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



        // CANCEL/TERMINATE FUNCTION
        function CancelFunction(id) {
            if (!confirm(`Are you sure you want to cancel job ID ${id}?`)) return;

            fetch('process_form.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'terminate_run=' + encodeURIComponent(id)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                window.location.reload(); // Refresh after termination
            })
            .catch(error => { // this is running i dont know why 
                console.error('Error:', error);
                alert('Termination failed');
            });
        }


    </script>
</body>
</html>