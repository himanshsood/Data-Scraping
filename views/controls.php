</tbody>
</table>
</div>

<?php if (!empty($jobs)): ?>
<div class="controls">
    <form method="POST" style="display: inline;">
        <button type="submit" name="action" value="clear_history" class="btn btn-warning" 
                onclick="return confirm('Are you sure you want to clear all job history?')">
            Clear History
        </button>
    </form>
    <form method="POST" style="display: inline; margin-left: 10px;">
        <button type="submit" name="action" value="reset_current" class="btn btn-info"
                onclick="return confirm('Are you sure you want to reset the current job?')">
            Reset Current Job
        </button>
    </form>
    <span style="margin-left: 15px; color: #666;">
        Total Jobs: <?php echo count($jobs); ?>
    </span>
</div>
<?php elseif ($_SESSION['current_job']['status'] != 'Ready to Create'): ?>
<div class="controls">
    <form method="POST" style="display: inline;">
        <button type="submit" name="action" value="reset_current" class="btn btn-info"
                onclick="return confirm('Are you sure you want to reset the current job?')">
            Reset Current Job
        </button>
    </form>
</div>
<?php endif; ?>
