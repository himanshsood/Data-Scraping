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
                    <?php if(empty($_SESSION['jobHistory'])): ?>
                        <tr>
                            <td colspan="4" class="no-jobs">No jobs found. Upload a CSV to see job history.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($_SESSION['jobHistory'] as $index => $job): ?>
                            <tr>
                                <td class="job-name"><?= htmlspecialchars($job['name']) ?></td>
                                <td class="starting-time"><?= htmlspecialchars($job['startTime']) ?></td>
                                <td>
                                    <?php if($index === 0): ?>
                                        <button class="action-btn btn-cancel" onclick="cancelJob(<?= $job['id'] ?>)">Cancel</button>
                                    <?php elseif($index === 1 || $index === 2): ?>
                                        <button class="action-btn btn-rollback" onclick="rollbackJob(<?= $job['id'] ?>)">Rollback</button>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><span class="status-badge status-sent"><?= htmlspecialchars($job['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>