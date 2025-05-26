<?php 
    $Jobname = "" ; 
    $File = null ;
?>

<?php
// Handle form submission
if ($_POST) {
    if (isset($_POST['auto_update']) && $_POST['auto_update'] == '1') {
        $update_date = $_POST['update_date'];
        $update_time = $_POST['update_time'];
        echo "<div class='success-message'>Automatic update scheduled for: " . htmlspecialchars($update_date) . " at " . htmlspecialchars($update_time) . "</div>";
    } elseif (isset($_POST['manual_update'])) {
        echo "<div class='success-message'>Manual update triggered successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Update Management & CSV Upload</title>
    <style>
        /* Container for side-by-side forms */
        .form-row-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
            margin: 20px;
            flex-wrap: wrap;
        }

        /* Each form takes equal space but no less than 300px */
        .update-form-container,
        .csv-upload-form {
            flex: 1;
            min-width: 300px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            padding: 20px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        /* Update Management form styles */
        .form-title {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
        }
        .checkbox-group label {
            font-weight: bold;
            color: #333;
        }
        .datetime-section {
            margin-left: 20px;
            padding: 10px;
            background-color: #e8f4f8;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .datetime-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .datetime-section input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .manual-section {
            text-align: center;
            padding: 20px;
            background-color: #fff3cd;
            border-radius: 5px;
            border: 1px solid #ffeaa7;
        }
        .manual-update-btn {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .manual-update-btn:hover {
            background-color: #0056b3;
        }
        .hidden {
            display: none;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* CSV Upload Form styles */
        .input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            cursor: pointer;
            border-radius: 5px;
            padding: 8px 15px;
            border: none;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        .btn-info:hover {
            background-color: #117a8b;
        }
        .btn-completed {
            background-color: #28a745;
            color: white;
        }
        .btn-completed:hover {
            background-color: #1e7e34;
        }
        .ms-2 {
            margin-left: 0.5rem;
        }
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .status-created {
            background-color: #f0ad4e;
            color: white;
        }
        .status-fetched {
            background-color: #5cb85c;
            color: white;
        }
        .auto-moved-message {
            margin-top: 10px;
            color: green;
            font-weight: bold;
        }
        .workflow-indicator {
            margin-bottom: 15px;
        }
        .workflow-step {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #ddd;
            border-radius: 4px;
            font-size: 13px;
        }
        .workflow-step.completed {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<div class="form-row-wrapper">

    <!-- Update Management Form -->
    <div class="update-form-container">
        <h3 class="form-title">Update Management</h3>
        
        <form method="POST" action="">
            <!-- Automatic Update Section -->
            <div class="checkbox-group">
                <input type="checkbox" id="autoUpdate" name="auto_update" value="1" onchange="toggleUpdateOptions()">
                <label for="autoUpdate">Enable Automatic Update</label>
            </div>
            
            <!-- Date and Time Section (shown when checkbox is checked) -->
             <div id="datetimeSection" class="datetime-section hidden">
            <label for="updateDay">Select Day:</label>
            <select id="updateDay" name="update_day" required>
                <option value="">Choose a day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
            
            <label for="updateTime">Select Time:</label>
            <input type="time" id="updateTime" name="update_time" required>
            
            <button type="submit" class="manual-update-btn" style="margin-top: 10px;" onclick="showMessage('Update scheduled successfully!', 'success')">Schedule Update</button>
        </div>
            
            <!-- Manual Update Section (shown when checkbox is not checked) -->
            <div id="manualSection" class="manual-section">
                <p><strong>Manual Update Mode</strong></p>
                <p>Click the button below to trigger an immediate update</p>
                <button type="submit" name="manual_update" class="manual-update-btn">Update Now</button>
            </div>
        </form>
    </div>

    <!-- CSV Upload Form -->
    <div class="csv-upload-form">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="job_name" id="current_job_name" 
                   value="CSV UPLOAD"
                   placeholder="Enter job name..." required style="display:none;">

            <?php if (!empty($_SESSION['current_job']['job_name'])): ?>
            <div class="workflow-indicator">
                <span class="workflow-step <?php echo $_SESSION['current_job']['data_fetched'] ? 'completed' : ''; ?>">
                    Data Fetch
                </span>
                <span class="workflow-step <?php echo $_SESSION['current_job']['csv_uploaded'] ? 'completed' : ''; ?>">
                    CSV Upload
                </span>
                <span class="workflow-step <?php echo $_SESSION['current_job']['sent_to_monday'] ? 'completed' : ''; ?>">
                    Monday Integration
                </span>
            </div>
            <?php endif; ?>

            <div class="input-group">
                <input type="file" id="csv_upload" name="csv_file" accept=".csv" required style="display:none;">
                <button type="button" id="file_button" class="btn" style="background-color: #ff6600; color: white;">
                    Choose File
                </button>
                <span id="file_name" class="ms-2" style="color:<?= $File === null ? 'red' : 'black'; ?>">
                    <?= $File === null ? 'No File Selected' : htmlspecialchars($File); ?>
                </span>

                <button type="submit" name="action" value="send_to_monday_current"
                    class="btn <?php echo $_SESSION['current_job']['sent_to_monday'] ? 'btn-completed' : 'btn-info'; ?>">
                    Send to Monday
                </button>
            </div>

            <div class="csv-status">
                <?php 
                    $currentStatus = $_SESSION['current_job']['status'] ?? '';
                    $statusClass = 'status-created';
                    if (in_array(strtolower(str_replace(' ', '-', $currentStatus)), ['data-fetched', 'csv-uploaded', 'sent-to-monday'])) {
                        $statusClass = 'status-fetched';
                    }
                ?>
                <span class="status <?php echo $statusClass; ?>">
                    <?php echo htmlspecialchars($currentStatus); ?>
                </span>

                <?php if (!empty($_SESSION['current_job']['sent_to_monday'])): ?>
                <div class="auto-moved-message">
                    ✓ Job completed and moved to history automatically
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

</div>

<script>
function toggleUpdateOptions() {
    const checkbox = document.getElementById('autoUpdate');
    const datetimeSection = document.getElementById('datetimeSection');
    const manualSection = document.getElementById('manualSection');
    
    if (checkbox.checked) {
        datetimeSection.classList.remove('hidden');
        manualSection.classList.add('hidden');
        document.getElementById('updateDate').required = true;
        document.getElementById('updateTime').required = true;
    } else {
        datetimeSection.classList.add('hidden');
        manualSection.classList.remove('hidden');
        document.getElementById('updateDate').required = false;
        document.getElementById('updateTime').required = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleUpdateOptions();
});

// File input script
const fileInput = document.getElementById('csv_upload');
const fileButton = document.getElementById('file_button');
const fileNameSpan = document.getElementById('file_name');

fileButton.onclick = () => fileInput.click();
fileInput.onchange = (e) => {
    const fileName = e.target.files[0]?.name || 'No file selected';
    fileNameSpan.textContent = fileName;
    fileNameSpan.style.color = 'black';
};
</script>

</body>
</html>
