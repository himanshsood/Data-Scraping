<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Three Column Forms</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .table-container {
            flex: 1;
            min-width: 300px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .table-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .table-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
            font-weight: 600;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        
        input[type="checkbox"] {
            transform: scale(1.2);
            margin-right: 8px;
        }
        
        select, input[type="time"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        select:focus, input[type="time"]:focus, input[type="file"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(245, 87, 108, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        }
        
        .manual-update-box {
            text-align: center;
            padding: 40px 20px;
            border: 3px dashed #667eea;
            border-radius: 15px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transition: all 0.3s ease;
        }
        
        .manual-update-box:hover {
            border-color: #764ba2;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        
        .update-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 15px;
        }
        
        .file-name-display {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            min-height: 20px;
            color: #666;
            font-style: italic;
        }
        
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-radius: 8px;
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .table-container {
                min-width: 100%;
            }
            
            .job-table th,
            .job-table td {
                padding: 8px 6px;
                font-size: 12px;
            }
            
            .action-btn {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            .status-badge {
                padding: 4px 8px;
                font-size: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .job-table th:nth-child(2),
            .job-table td:nth-child(2) {
                display: none;
            }
            
            .job-table th,
            .job-table td {
                padding: 10px 4px;
                font-size: 11px;
            }
            
            .table-title {
                font-size: 16px;
            }
            
            .starting-time {
                font-size: 10px;
            }
        }
        
        /* Job History Table Styles */
        .table-wrapper {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }
        
        .job-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .job-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .job-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #333;
        }
        
        .job-table tr:hover {
            background-color: #f8f9fa;
        }
        
        .job-table tr:last-child td {
            border-bottom: none;
        }
        
        .no-jobs {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 30px !important;
        }
        
        .job-name {
            font-weight: 600;
            color: #667eea;
        }
        
        .starting-time {
            color: #666;
            font-family: 'Courier New', monospace;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-sent {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }
        
        .status-processing {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }
        
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }
        
        .btn-cancel:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(244, 67, 54, 0.3);
        }
        
        .btn-rollback {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
        }
        
        .btn-rollback:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(255, 152, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- First Table: Automatic Update Form -->
        <div class="table-container">
            <h3 class="table-title">🔄 Automatic Update</h3>
            <form id="autoUpdateForm">
                <div class="checkbox-container">
                    <input type="checkbox" id="enableAutoUpdate" name="enableAutoUpdate">
                    <label for="enableAutoUpdate">Enable Automatic Update</label>
                </div>
                
                <div class="form-group">
                    <label for="daySelect">Select Day:</label>
                    <select id="daySelect" name="day" required>
                        <option value="">Choose a day</option>
                        <option value="monday">Monday</option>
                        <option value="tuesday">Tuesday</option>
                        <option value="wednesday">Wednesday</option>
                        <option value="thursday">Thursday</option>
                        <option value="friday">Friday</option>
                        <option value="saturday">Saturday</option>
                        <option value="sunday">Sunday</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="timeSelect">Select Time:</label>
                    <input type="time" id="timeSelect" name="time" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Confirm Schedule</button>
            </form>
        </div>
        
        <!-- Second Table: Manual Update -->
        <div class="table-container">
            <h3 class="table-title">⚡ Manual Update</h3>
            <div class="manual-update-box">
                <div class="update-icon">🔄</div>
                <p style="margin-bottom: 20px; color: #666;">Click to manually update your data</p>
                <button class="btn btn-secondary" onclick="manualUpdate()">Update Now</button>
            </div>
        </div>
        
        <!-- Third Table: CSV Upload Form -->
        <div class="table-container">
            <h3 class="table-title">📊 CSV Upload</h3>
            <form id="csvUploadForm">
                <div class="form-group">
                    <label for="genderSelect">Select Gender:</label>
                    <select id="genderSelect" name="gender" required>
                        <option value="">Choose gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="csvFile">Choose CSV File:</label>
                    <input type="file" id="csvFile" name="csvFile" accept=".csv" required>
                </div>
                
                <div class="form-group">
                    <label>Selected File:</label>
                    <div class="file-name-display" id="fileNameDisplay">No file selected</div>
                </div>
                
                <button type="submit" class="btn btn-success">Send to Monday</button>
            </form>
        </div>
    </div>

    <!-- Job History Table -->
    <div style="margin-top: 30px;">
        <div class="table-container" style="width: 100%; flex: none;">
            <h3 class="table-title">📋 Job History</h3>
            <div class="table-wrapper">
                <table id="jobHistoryTable" class="job-table">
                    <thead>
                        <tr>
                            <th>Job Name</th>
                            <th>Starting Time</th>
                            <th>Actions</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="jobHistoryBody">
                        <tr>
                            <td colspan="4" class="no-jobs">No jobs found. Upload a CSV to see job history.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Job history data storage
        let jobHistory = [];
        let jobIdCounter = 1;
        
        // Auto Update Form Handler
        document.getElementById('autoUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const checkbox = document.getElementById('enableAutoUpdate');
            const day = document.getElementById('daySelect').value;
            const time = document.getElementById('timeSelect').value;
            
            if (!checkbox.checked) {
                alert('Please enable automatic update first!');
                return;
            }
            
            if (!day || !time) {
                alert('Please select both day and time!');
                return;
            }
            
            // Convert 24-hour time to 12-hour format for display
            const timeObj = new Date('1970-01-01T' + time + ':00');
            const displayTime = timeObj.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
            
            const message = `✅ Automatic update scheduled!\n\nDay: ${day.charAt(0).toUpperCase() + day.slice(1)}\nTime: ${displayTime}\n\nYou will receive an email confirmation shortly.`;
            
            alert(message);
            
            // Simulate email notification
            setTimeout(() => {
                alert('📧 Email sent! You have successfully set automatic fetch update for ' + day.charAt(0).toUpperCase() + day.slice(1) + ' at ' + displayTime);
            }, 1000);
            
            // Reset form
            this.reset();
        });
        
        // Manual Update Function
        function manualUpdate() {
            const btn = event.target;
            const originalText = btn.textContent;
            
            btn.textContent = 'Updating...';
            btn.disabled = true;
            
            setTimeout(() => {
                alert('✅ Manual update completed successfully!');
                
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        }
        
        // CSV File Selection Handler
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
        
        // CSV Upload Form Handler
        document.getElementById('csvUploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const gender = document.getElementById('genderSelect').value;
            const file = document.getElementById('csvFile').files[0];
            
            if (!gender) {
                alert('Please select a gender!');
                return;
            }
            
            if (!file) {
                alert('Please choose a CSV file!');
                return;
            }
            
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            
            btn.textContent = 'Sending...';
            btn.disabled = true;
            
            setTimeout(() => {
                alert(`✅ CSV file "${file.name}" has been successfully sent to Monday!\n\nGender filter: ${gender.charAt(0).toUpperCase() + gender.slice(1)}\nFile size: ${(file.size / 1024).toFixed(2)} KB`);
                
                // Add to job history based on gender selection
                const jobName = `CSV Upload for ${gender.charAt(0).toUpperCase() + gender.slice(1)}`;
                addJobToHistory(jobName, 'Sent to Monday');
                
                // Reset form
                this.reset();
                document.getElementById('fileNameDisplay').textContent = 'No file selected';
                document.getElementById('fileNameDisplay').style.color = '#666';
                document.getElementById('fileNameDisplay').style.fontStyle = 'italic';
                
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        });
        
        // Function to add job to history
        function addJobToHistory(jobName, status) {
            const currentTime = new Date();
            const timeString = currentTime.toLocaleString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            
            const newJob = {
                id: jobIdCounter++,
                name: jobName,
                startTime: timeString,
                status: status,
                timestamp: currentTime
            };
            
            // Add to beginning of array (latest first)
            jobHistory.unshift(newJob);
            
            // Update the table
            updateJobHistoryTable();
        }
        
        // Function to update job history table
        function updateJobHistoryTable() {
            const tbody = document.getElementById('jobHistoryBody');
            
            if (jobHistory.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="no-jobs">No jobs found. Upload a CSV to see job history.</td></tr>';
                return;
            }
            
            let tableHTML = '';
            
            jobHistory.forEach((job, index) => {
                let actionButton = '';
                
                if (index === 0) {
                    // Latest entry gets Cancel button
                    actionButton = `<button class="action-btn btn-cancel" onclick="cancelJob(${job.id})">Cancel</button>`;
                } else if (index === 1 || index === 2) {
                    // Next two entries get Rollback button
                    actionButton = `<button class="action-btn btn-rollback" onclick="rollbackJob(${job.id})">Rollback</button>`;
                } else {
                    // All others are empty
                    actionButton = '-';
                }
                
                tableHTML += `
                    <tr>
                        <td class="job-name">${job.name}</td>
                        <td class="starting-time">${job.startTime}</td>
                        <td>${actionButton}</td>
                        <td><span class="status-badge status-sent">${job.status}</span></td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = tableHTML;
        }
        
        // Function to cancel job
        function cancelJob(jobId) {
            const job = jobHistory.find(j => j.id === jobId);
            if (job) {
                const confirmCancel = confirm(`Are you sure you want to cancel "${job.name}"?`);
                if (confirmCancel) {
                    // Remove job from history
                    jobHistory = jobHistory.filter(j => j.id !== jobId);
                    updateJobHistoryTable();
                    alert('✅ Job cancelled successfully!');
                }
            }
        }
        
        // Function to rollback job
        function rollbackJob(jobId) {
            const job = jobHistory.find(j => j.id === jobId);
            if (job) {
                const confirmRollback = confirm(`Are you sure you want to rollback "${job.name}"?`);
                if (confirmRollback) {
                    alert('🔄 Rollback initiated for: ' + job.name);
                    // You can add actual rollback logic here
                }
            }
        }
        
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
            autoUpdateCheckbox.dispatchEvent(new Event('change'));
            
            // Add some sample data for demonstration
            setTimeout(() => {
                addJobToHistory('CSV Upload for Female', 'Sent to Monday');
                addJobToHistory('CSV Upload for Male', 'Sent to Monday');
                addJobToHistory('Manual Update', 'Sent to Monday');
                addJobToHistory('Auto Update - Monday', 'Sent to Monday');
                addJobToHistory('CSV Upload for Female', 'Sent to Monday');
            }, 1000);
        });
    </script>
</body>
</html>