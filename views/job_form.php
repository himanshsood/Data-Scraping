<?php 
    $File = null ;
// AUTO UPDATE SUBMIT FORM 
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
    <link rel="stylesheet" href="./styles/job_form.css">
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
    <?php include 'csv_upload_form.php'; ?>


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
