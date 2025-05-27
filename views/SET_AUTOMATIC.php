<div class="container">
    <!-- First Table: Automatic Update Form -->
    <div class="table-container">
        <h3 class="table-title">🔄 Automatic Update</h3>
        <form id="autoUpdateForm" action="process_form.php" method="POST">
            <input type="hidden" name="form_type" value="auto_update">
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
        <form id="csvUploadForm" action="process_form.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="form_type" value="csv_upload">
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