<div class="container">
    <!-- First Table: Automatic Update Form --> 
    <div class="table-container">
        <h3 class="table-title">Automatic Update</h3>

        <!-- Display View -->
        <div id="displayView" class="schedule-display" style="display: <?php echo ($automatic ? 'block' : 'none'); ?>;">
            <div class="schedule-info" style="display:flex;flex-direction:column;row-gap:10px;">
                <div class="schedule-details">
                    <div class="schedule-label" style="font-size:20px;color:black;">Scheduled Day & Time:</div>
                    <div class="schedule-value" id="currentSchedule"><?php echo ucfirst(trim($day)) . " $time"; ?></div>
                </div>
                <button type="button" class="btn btn-edit" onclick="showEditForm()">Edit</button>
            </div>
            <div class="status-indicator">
                <div class="status-dot status-enabled" id="statusDot"></div>
                <span id="statusText">Automatic Update Enabled</span>
            </div>
        </div>

        <!-- Edit Form -->
        <div id="editForm" class="edit-form" style="display: <?php echo ($automatic ? 'none' : 'block'); ?>;">
            <form id="autoUpdateForm" action="process_form.php" method="POST">
                <input type="hidden" name="form_type" value="auto_update" />

                <div class="checkbox-container">
                    <input type="checkbox" id="enableAutoUpdate" name="enableAutoUpdate" <?php echo ($automatic ? 'checked' : ''); ?> />
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
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()" style="display: <?php echo ($automatic) ? 'block' : 'none'; ?>;">Cancel</button>
                </div>
            </form>
        </div>
    </div>