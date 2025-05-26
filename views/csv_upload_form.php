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
            <!-- Gender Dropdown Selector -->
            <select name="gender" id="gender_select" required>
                <option value="">Gender</option>
                <option value="Men">Men</option>
                <option value="Women">Women</option>
            </select>
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
    </form>
</div>
