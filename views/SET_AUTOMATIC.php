<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Update Dashboard</title>
    <style>
        /* Add your CSS here */
    </style>
</head>
<body>
    <div class="container">
        <!-- First Table: Automatic Update Form -->
        <div class="table-containerr">
            <h3 class="table-title">Automatic Update</h3>

            <!-- Display View -->
            <div id="displayView" class="schedule-display">
                <div class="schedule-info">
                    <div class="schedule-details">
                        <div class="schedule-label">Scheduled Day & Time:</div>
                        <div class="schedule-value" id="currentSchedule">Wednesday at 5:00 PM</div>
                    </div>
                    <button type="button" class="btn btn-edit" onclick="showEditForm()">Edit</button>
                </div>
                <div class="status-indicator">
                    <div class="status-dot status-enabled" id="statusDot"></div>
                    <span id="statusText">Automatic Update Enabled</span>
                </div>
            </div>

            <!-- Edit Form -->
            <div id="editForm" class="edit-form" style="display: none;">
                <form id="autoUpdateForm" action="process_form.php" method="POST">
                    <input type="hidden" name="form_type" value="auto_update" />

                    <div class="checkbox-container">
                        <input type="checkbox" id="enableAutoUpdate" name="enableAutoUpdate" checked />
                        <label for="enableAutoUpdate">Enable Automatic Update</label>
                    </div>

                    <div class="form-group">
                        <label for="daySelect">Select Day:</label>
                        <select id="daySelect" name="day" required>
                            <option value="">Choose a day</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday" selected>Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="timeSelect">Select Time:</label>
                        <input type="time" id="timeSelect" name="time" value="17:00" required />
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Second Table: Manual Update -->
        <div class="table-container">
            <h3 class="table-title">Manual Update</h3>
            <div class="manual-update-box">
                <div class="update-icon">↻</div>
                <p style="margin-bottom: 20px; color: #666;">Click to manually update your data</p>
                <button class="btn btn-secondary" onclick="manualUpdate()">Update Now</button>
            </div>
        </div>

        <!-- Third Table: CSV Upload Form -->
        <div class="table-container">
            <h3 class="table-title">CSV Upload</h3>
            <form id="csvUploadForm" action="process_form.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="csv_upload" />

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
                    <input type="file" id="csvFile" name="csvFile" accept=".csv" required />
                </div>

                <div class="form-group">
                    <label>Selected File:</label>
                    <div class="file-name-display" id="fileNameDisplay">No file selected</div>
                </div>

                <button type="submit" class="btn btn-success">Send to Monday</button>
            </form>
        </div>
    </div>

    <script>
        function showEditForm() {
            document.getElementById('displayView').style.display = 'none';
            document.getElementById('editForm').style.display = 'block';
        }

        function cancelEdit() {
            document.getElementById('editForm').style.display = 'none';
            document.getElementById('displayView').style.display = 'block';
        }

        function manualUpdate() {
            alert('Manual update triggered!');
            // Add your logic here
        }

        // Optional: Show selected file name
        document.getElementById('csvFile')?.addEventListener('change', function () {
            const fileName = this.files?.[0]?.name || 'No file selected';
            document.getElementById('fileNameDisplay').textContent = fileName;
        });
    </script>
</body>
</html>
