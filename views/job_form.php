<?php 
    $Jobname = "" ; 
    $File = null ;

?>


    <form method="POST" style="display: contents;">
        <td>
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
        </td>
        <td>
            <?php if ($_SESSION['current_job']['starting_time']): ?>
                <?php echo date('M j, Y g:i A', strtotime($_SESSION['current_job']['starting_time'])); ?>
            <?php else: ?>
        
            <?php endif; ?>
        </td>
        <td class="actions">
            <form method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <!-- Hidden file input -->
                    <input type="file" id="csv_upload" name="csv_file" accept=".csv" class="d-none" required style="display:none;">
                    <button type="button" id="file_button" class="btn" style="background-color: #ff6600; color: white; border: none;">
                        Choose File
                    </button>
                    <!-- Span now has ID to update via JS -->
                    <span id="file_name" class="ms-2" style="color:<?= $File === null ? 'red' : 'black'; ?>">
                        <?= $File === null ? 'No File Selected' : htmlspecialchars($File); ?>
                    </span>
                    <script>
                        const fileInput = document.getElementById('csv_upload');
                        const fileButton = document.getElementById('file_button');
                        const fileNameSpan = document.getElementById('file_name');

                        fileButton.onclick = () => {
                            fileInput.click();
                        };
                        fileInput.onchange = (e) => {
                            const fileName = e.target.files[0]?.name || 'No file selected';
                            fileNameSpan.textContent = fileName;
                            fileNameSpan.style.color = 'black'; // Change color to black when a file is selected
                        };
                    </script>

                    <!-- Submit button -->
                    <button type="submit" name="action" value="send_to_monday_current"
                    class="btn <?php echo $_SESSION['current_job']['sent_to_monday'] ? 'btn-completed' : 'btn-info'; ?>">
                        Send to Monday
                    </button>

                </div>
            </form>

        </td>
        <td>
            <?php 
                $currentStatus = $_SESSION['current_job']['status'];
                switch (strtolower(str_replace(' ', '-', $currentStatus))) {
                    case 'ready-to-create':
                        $statusClass = 'status-created';
                        break;
                    case 'data-fetched':
                    case 'csv-uploaded':
                    case 'sent-to-monday':
                        $statusClass = 'status-fetched';
                        break;
                    default:
                        $statusClass = 'status-created';
                }
            ?>

            <span class="status <?php echo $statusClass; ?>">
                <?php echo htmlspecialchars($currentStatus); ?>
            </span>

            <?php if ($_SESSION['current_job']['sent_to_monday']): ?>
            <div class="auto-moved-message">
                ✓ Job completed and moved to history automatically
            </div>
            <?php endif; ?>
        </td>
    </form>

