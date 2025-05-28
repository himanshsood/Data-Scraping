    <div class="container">
        <!-- First Table: Automatic Update Form -->
        <div class="table-containerr">
            <h3 class="table-title">Automatic Update</h3>

            <!-- Display View -->
            <div id="displayView" class="schedule-display" style="display: <?php echo ($automatic ? 'block' : 'none'); ?>;">
                <div class="schedule-info">
                    <div class="schedule-details">
                        <div class="schedule-label">Scheduled Day & Time:</div>
                        <div class="schedule-value" id="currentSchedule"><?php echo "$day $time"?></div>
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

        <!-- Second Table: Manual Update -->
       <div class="table-container">
    <h3 class="table-title">Manual Update</h3>
    <div class="manual-update-box">
        <div class="update-icon">↻</div>
        <p style="margin-bottom: 20px; color: #666;">Click to manually update your data</p>
        <button id="manualUpdateBtn" class="btn btn-secondary">Update Now</button>
        <p id="updateStatus" style="margin-top:10px; color: #007bff; display:none;">Update in progress... ⏳</p>
        <p id="updateComplete" style="margin-top:10px; color: green; display:none;">Update completed! ✅</p>
    </div>
</div>

        <!-- Third Table: CSV Upload Form -->
        <div class="table-container">
            <h3 class="table-title">CSV Upload</h3>
           <form id="csvUploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="genderSelect">Select Gender:</label>
                    <select id="genderSelect" name="board_type" required>
                        <option value="">Choose gender</option>
                        <option value="men">Male</option>
                        <option value="women">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="csvFile">Choose CSV File:</label>
                    <input type="file" id="csvFile" name="file" accept=".csv" required />
                </div>

                
                <button type="submit" class="btn btn-success">Send to Monday</button>
            </form>

            <!-- Progress & Result Message -->
            <div id="progressMessage" style="margin-top: 15px; font-weight: bold;"></div>


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











        const btn = document.getElementById('manualUpdateBtn');
const statusText = document.getElementById('updateStatus');
const completeText = document.getElementById('updateComplete');

btn.addEventListener('click', () => {
    btn.disabled = true;
    statusText.style.display = 'block';
    completeText.style.display = 'none';

    // Start the manual update via POST
    fetch('process_form.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'manual_update=1'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            pollUpdateStatus(data.run_id);  // pass run_id or message if needed
        } else {
            alert('Error starting update: ' + data.message);
            btn.disabled = false;
            statusText.style.display = 'none';
        }
    })
    .catch(() => {
        alert('Failed to start update.');
        btn.disabled = false;
        statusText.style.display = 'none';
    });
});

// Poll backend for status every 2 seconds
function pollUpdateStatus(runId) {
    const interval = setInterval(() => {
        fetch('process_form.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `check_progress=1&run_id=${encodeURIComponent(runId)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.progress === 100) {
                clearInterval(interval);
                statusText.style.display = 'none';
                completeText.style.display = 'block';
                btn.disabled = false;
            } else if (data.status === 'error') {
                clearInterval(interval);
                alert('Update error: ' + data.message);
                btn.disabled = false;
                statusText.style.display = 'none';
            }
            // else: still in progress, do nothing
        })
        .catch(() => {
            clearInterval(interval);
            alert('Error checking update status.');
            btn.disabled = false;
            statusText.style.display = 'none';
        });
    }, 2000);
}
document.getElementById("csvFile").addEventListener("change", function () {
    const fileName = this.files[0]?.name || "No file selected";
    document.getElementById("fileNameDisplay").textContent = fileName;
});

document.getElementById("csvUploadForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Stop normal form submission

    const form = e.target;
    const formData = new FormData(form);
    const progressMessage = document.getElementById("progressMessage");

    progressMessage.textContent = "Uploading CSV...";

    fetch("http://localhost:5000/api/updates/csv", {
        method: "POST",
        body: formData
    })
    .then(async res => {
        const contentType = res.headers.get("content-type");
        let data;

        if (!contentType || !contentType.includes("application/json")) {
            throw new Error("Invalid response from server");
        }

        data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || "Server error");
        }

        if (data.status === "success" && data.run_id) {
            progressMessage.textContent = "Upload successful. Monitoring progress...";
            pollProgress(data.run_id);
        } else {
            throw new Error(data.message || "Unknown error");
        }
    })
    .catch(err => {
        console.error("Upload error:", err);
        progressMessage.textContent = `Upload error: ${err.message}`;
    });

});

function pollProgress(runId) {
    const progressMessage = document.getElementById("progressMessage");

    function check() {
        fetch(`http://localhost:5000/api/updates/progress/${runId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    progressMessage.textContent = `Progress: ${data.progress}% - ${data.message}`;

                    if (data.progress < 100) {
                        setTimeout(check, 2000); // Keep polling
                    } else {
                        progressMessage.textContent = "✅ Update completed successfully!";
                    }
                } else {
                    progressMessage.textContent = "⚠️ Progress check failed.";
                }
            })
            .catch(err => {
                console.error(err);
                progressMessage.textContent = "❌ Error while checking progress.";
            });
    }

    check(); // Start polling
}
    </script>
