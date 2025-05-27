<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Configuration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .form-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }
        
        .form-section {
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: #fafafa;
        }
        
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .checkbox-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 12px;
            cursor: pointer;
        }
        
        .checkbox-container label {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
        }
        
        .schedule-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
            padding: 15px;
            background-color: white;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .schedule-group {
            display: flex;
            flex-direction: column;
        }
        
        .schedule-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
        }
        
        .schedule-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            background-color: white;
        }
        
        .manual-section {
            text-align: center;
        }
        
        .manual-section h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .manual-update-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .manual-update-btn:hover {
            background-color: #0056b3;
        }
        
        .manual-update-btn:active {
            transform: translateY(1px);
        }
        
        .submit-btn {
            width: 100%;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #218838;
        }
        
        .hidden {
            display: none;
        }
        
        .status-message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .info {
            background-color: #cce7ff;
            color: #004085;
            border: 1px solid #99d3ff;
        }

        @media (max-width: 600px) {
            .schedule-options {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php
    $message = '';
    $messageType = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['manual_update'])) {
            // Handle manual update
            $message = 'Manual update has been triggered successfully!';
            $messageType = 'success';
            
            // Add your manual update logic here
            // For example: runManualUpdate();
            
        } elseif (isset($_POST['save_settings'])) {
            // Handle settings save
            $autoUpdate = isset($_POST['auto_update']) ? 1 : 0;
            
            if ($autoUpdate) {
                $day = $_POST['update_day'] ?? '';
                $time = $_POST['update_time'] ?? '';
                
                // Save automatic update settings
                // For example: saveUpdateSettings($day, $time);
                
                $message = "Automatic update scheduled for {$day} at {$time}";
                $messageType = 'success';
            } else {
                // Disable automatic updates
                // For example: disableAutoUpdate();
                
                $message = 'Automatic updates have been disabled. Use manual update when needed.';
                $messageType = 'info';
            }
        }
    }
    
    // Get current settings (replace with actual database queries)
    $currentAutoUpdate = false; // Get from database
    $currentDay = 'monday'; // Get from database
    $currentTime = '09:00'; // Get from database
    ?>

    <div class="form-container">
        <h1 class="form-title">Update Configuration</h1>
        
        <?php if ($message): ?>
            <div class="status-message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <!-- Automatic Update Section -->
            <div class="form-section">
                <div class="checkbox-container">
                    <input type="checkbox" 
                           id="auto_update" 
                           name="auto_update" 
                           value="1"
                           <?php echo $currentAutoUpdate ? 'checked' : ''; ?>
                           onchange="toggleUpdateOptions()">
                    <label for="auto_update">Enable Automatic Updates</label>
                </div>
                
                <div id="schedule_options" class="schedule-options <?php echo !$currentAutoUpdate ? 'hidden' : ''; ?>">
                    <div class="schedule-group">
                        <label for="update_day">Day of Week:</label>
                        <select name="update_day" id="update_day">
                            <option value="monday" <?php echo $currentDay === 'monday' ? 'selected' : ''; ?>>Monday</option>
                            <option value="tuesday" <?php echo $currentDay === 'tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                            <option value="wednesday" <?php echo $currentDay === 'wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                            <option value="thursday" <?php echo $currentDay === 'thursday' ? 'selected' : ''; ?>>Thursday</option>
                            <option value="friday" <?php echo $currentDay === 'friday' ? 'selected' : ''; ?>>Friday</option>
                            <option value="saturday" <?php echo $currentDay === 'saturday' ? 'selected' : ''; ?>>Saturday</option>
                            <option value="sunday" <?php echo $currentDay === 'sunday' ? 'selected' : ''; ?>>Sunday</option>
                        </select>
                    </div>
                    
                    <div class="schedule-group">
                        <label for="update_time">Time:</label>
                        <select name="update_time" id="update_time">
                            <?php
                            for ($hour = 0; $hour < 24; $hour++) {
                                for ($minute = 0; $minute < 60; $minute += 30) {
                                    $timeValue = sprintf('%02d:%02d', $hour, $minute);
                                    $selected = $currentTime === $timeValue ? 'selected' : '';
                                    echo "<option value=\"{$timeValue}\" {$selected}>{$timeValue}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Manual Update Section -->
            <div id="manual_section" class="form-section manual-section <?php echo $currentAutoUpdate ? 'hidden' : ''; ?>">
                <h3>Manual Update</h3>
                <p>Click the button below to trigger an immediate update:</p>
                <button type="submit" name="manual_update" class="manual-update-btn">
                    Run Update Now
                </button>
            </div>
            
            <button type="submit" name="save_settings" class="submit-btn">
                Save Configuration
            </button>
        </form>
    </div>

    <script>
        function toggleUpdateOptions() {
            const checkbox = document.getElementById('auto_update');
            const scheduleOptions = document.getElementById('schedule_options');
            const manualSection = document.getElementById('manual_section');
            
            if (checkbox.checked) {
                scheduleOptions.classList.remove('hidden');
                manualSection.classList.add('hidden');
            } else {
                scheduleOptions.classList.add('hidden');
                manualSection.classList.remove('hidden');
            }
        }
        
        // Initialize the form state on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleUpdateOptions();
        });
    </script>
</body>
</html>