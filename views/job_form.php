<tr class="entry-row">
    <form method="POST" style="display: contents;">
        <td>
            <input type="text" name="job_name" id="current_job_name" 
                   value="<?php echo htmlspecialchars($_SESSION['current_job']['job_name']); ?>"
                   placeholder="Enter job name..." required>

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
                <em style="color: #666;">Autofilled </em>
            <?php endif; ?>
        </td>
        <td class="actions">
            <button type="submit" name="action" value="fetch_data_current" 
                    class="btn <?php echo $_SESSION['current_job']['data_fetched'] ? 'btn-completed' : 'btn-success'; ?>">
                Fetch Data
            </button>
            <button type="submit" name="action" value="upload_csv_current" 
                    class="btn <?php echo $_SESSION['current_job']['csv_uploaded'] ? 'btn-completed' : 'btn-warning'; ?>">
                Upload CSV
            </button>
            <button type="submit" name="action" value="send_to_monday_current" 
                    class="btn <?php echo $_SESSION['current_job']['sent_to_monday'] ? 'btn-completed' : 'btn-info'; ?>">
                Send to Monday
            </button>
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
</tr>
