
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Management Form</title>
    <style>
        .update-form-container {
            width: 400px;
            max-width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
        }
        
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
    </style>
</head>
<body>

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
            <label for="updateDate">Select Date:</label>
            <input type="date" id="updateDate" name="update_date" required>
            
            <label for="updateTime">Select Time:</label>
            <input type="time" id="updateTime" name="update_time" required>
            
            <button type="submit" class="manual-update-btn" style="margin-top: 10px;">Schedule Update</button>
        </div>
        
        <!-- Manual Update Section (shown when checkbox is not checked) -->
        <div id="manualSection" class="manual-section">
            <p><strong>Manual Update Mode</strong></p>
            <p>Click the button below to trigger an immediate update</p>
            <button type="submit" name="manual_update" class="manual-update-btn">Update Now</button>
        </div>
    </form>
</div>

<script>
function toggleUpdateOptions() {
    const checkbox = document.getElementById('autoUpdate');
    const datetimeSection = document.getElementById('datetimeSection');
    const manualSection = document.getElementById('manualSection');
    
    if (checkbox.checked) {
        // Show datetime section, hide manual section
        datetimeSection.classList.remove('hidden');
        manualSection.classList.add('hidden');
        
        // Make date and time required when auto update is enabled
        document.getElementById('updateDate').required = true;
        document.getElementById('updateTime').required = true;
    } else {
        // Show manual section, hide datetime section
        datetimeSection.classList.add('hidden');
        manualSection.classList.remove('hidden');
        
        // Remove required attribute when auto update is disabled
        document.getElementById('updateDate').required = false;
        document.getElementById('updateTime').required = false;
    }
}

// Initialize the form state on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleUpdateOptions();
});
</script>

</body>
</html>