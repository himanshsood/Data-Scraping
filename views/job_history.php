<?php if (empty($jobs)): ?>
<tr>
    <td colspan="4" class="no-data">
        No jobs created yet. Use the form above to add your first job.
    </td>
</tr>
<?php else: ?>
    <?php 
    $index = 0;
    foreach ($jobs as $job): 
    ?>
    <tr>
        <td><strong><?php echo htmlspecialchars($job['job_name']); ?></strong></td>
        <td>
            <?php echo $job['starting_time'] ? date('M j, Y g:i A', strtotime($job['starting_time'])) : '<em style="color: #999;">Not started</em>'; ?>
        </td>
        <td class="actions">
            <?php if ($index === 0): ?>
                <!-- Cancel for the most recent job -->
                <form method="POST" style="display: contents;">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <button type="submit" name="action" value="cancel" class="btn">
                        Cancel
                    </button>
                </form>
            <?php elseif ($index > 0 && $index <= 2): ?>
                <!-- Rollback for next 3 jobs -->
                <form method="POST" style="display: contents;">
                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                    <button type="submit" name="action" value="send_to_monday" class="btn">
                        Rollback
                    </button>
                </form>
            <?php else: ?>
                <!-- Empty cell for older jobs -->
                &nbsp;
            <?php endif; ?>
        </td>
        <td>
            <span class="status <?php 
                switch(strtolower(str_replace(' ', '-', $job['status']))) {
                    case 'created': echo 'status-created'; break;
                    case 'fetching-data...':
                    case 'uploading-csv...':
                    case 'sending-to-monday...':
                        echo 'status-fetching'; break;
                    case 'data-fetched':
                    case 'csv-uploaded':
                    case 'sent-to-monday':
                        echo 'status-fetched'; break;
                    default: echo 'status-created';
                }
            ?>">
                <?php echo htmlspecialchars($job['status']); ?>
                <?php if (strpos($job['status'], '...') !== false): ?>
                    <span class="loading"></span>
                <?php endif; ?>
            </span>
        </td>
    </tr>
    <?php 
    $index++;
    endforeach; 
    ?>
<?php endif; ?>
