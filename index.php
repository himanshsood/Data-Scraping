<?php
session_start();

// Initialize session data if not exists
if (!isset($_SESSION['jobs'])) {
    $_SESSION['jobs'] = [];
}

// Initialize current job session if not exists
if (!isset($_SESSION['current_job'])) {
    $_SESSION['current_job'] = [
        'job_name' => '',
        'starting_time' => null,
        'status' => 'Ready to Create',
        'data_fetched' => false,
        'csv_uploaded' => false,
        'sent_to_monday' => false
    ];
}

// Handle form submissions
if ($_POST) {
    if (isset($_POST['action'])) {
        $jobId = $_POST['job_id'] ?? null;
        
        switch ($_POST['action']) {
            case 'add_job':
                if (!empty($_POST['job_name'])) {
                    // Create new job and add to history
                    $newJob = [
                        'id' => uniqid(),
                        'job_name' => $_POST['job_name'],
                        'starting_time' => null,
                        'status' => 'Created',
                        'data_fetched' => false,
                        'csv_uploaded' => false,
                        'sent_to_monday' => false
                    ];
                    $_SESSION['jobs'][] = $newJob;
                    
                    // Reset current job
                    $_SESSION['current_job'] = [
                        'job_name' => '',
                        'starting_time' => null,
                        'status' => 'Ready to Create',
                        'data_fetched' => false,
                        'csv_uploaded' => false,
                        'sent_to_monday' => false
                    ];
                }
                break;
                
            case 'fetch_data_current':
                if (!empty($_POST['job_name'])) {
                    $_SESSION['current_job']['job_name'] = $_POST['job_name'];
                    $_SESSION['current_job']['status'] = 'Data Fetched';
                    $_SESSION['current_job']['data_fetched'] = true;
                }
                break;
                
            case 'upload_csv_current':
                if (!empty($_POST['job_name'])) {
                    $_SESSION['current_job']['job_name'] = $_POST['job_name'];
                    $_SESSION['current_job']['status'] = 'CSV Uploaded';
                    $_SESSION['current_job']['csv_uploaded'] = true;
                }
                break;
                
            case 'send_to_monday_current':
                if (!empty($_POST['job_name'])) {
                    // Update current job details
                    $_SESSION['current_job']['job_name'] = $_POST['job_name'];
                    $_SESSION['current_job']['starting_time'] = date('Y-m-d H:i:s');
                    $_SESSION['current_job']['status'] = 'Sent to Monday';
                    $_SESSION['current_job']['sent_to_monday'] = true;
                    
                    // Automatically move to history
                    $completedJob = [
                        'id' => uniqid(),
                        'job_name' => $_SESSION['current_job']['job_name'],
                        'starting_time' => $_SESSION['current_job']['starting_time'],
                        'status' => $_SESSION['current_job']['status'],
                        'data_fetched' => $_SESSION['current_job']['data_fetched'],
                        'csv_uploaded' => $_SESSION['current_job']['csv_uploaded'],
                        'sent_to_monday' => true
                    ];
                    $_SESSION['jobs'][] = $completedJob;
                    
                    // Reset current job for new entry
                    $_SESSION['current_job'] = [
                        'job_name' => '',
                        'starting_time' => null,
                        'status' => 'Ready to Create',
                        'data_fetched' => false,
                        'csv_uploaded' => false,
                        'sent_to_monday' => false
                    ];
                }
                break;
                
            case 'fetch_data':
                foreach ($_SESSION['jobs'] as &$job) {
                    if ($job['id'] == $jobId) {
                        $job['status'] = 'Fetching Data...';
                        // Simulate processing time
                        sleep(1);
                        $job['status'] = 'Data Fetched';
                        break;
                    }
                }
                break;
                
            case 'upload_csv':
                foreach ($_SESSION['jobs'] as &$job) {
                    if ($job['id'] == $jobId) {
                        $job['status'] = 'Uploading CSV...';
                        // Simulate processing time
                        sleep(1);
                        $job['status'] = 'CSV Uploaded';
                        break;
                    }
                }
                break;
                
            case 'send_to_monday':
                foreach ($_SESSION['jobs'] as &$job) {
                    if ($job['id'] == $jobId) {
                        $job['starting_time'] = date('Y-m-d H:i:s');
                        $job['status'] = 'Sending to Monday...';
                        // Simulate processing time
                        sleep(1);
                        $job['status'] = 'Sent to Monday';
                        break;
                    }
                }
                break;
                
            case 'clear_history':
                $_SESSION['jobs'] = [];
                break;
                
            case 'reset_current':
                $_SESSION['current_job'] = [
                    'job_name' => '',
                    'starting_time' => null,
                    'status' => 'Ready to Create',
                    'data_fetched' => false,
                    'csv_uploaded' => false,
                    'sent_to_monday' => false
                ];
                break;
        }
    }
}

// Reverse jobs array to show newest first
$jobs = array_reverse($_SESSION['jobs']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Job Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header p {
            opacity: 0.9;
        }
        
        .table-container {
            overflow-x: auto;
            padding: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
            position: sticky;
            top: 0;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .entry-row {
            background-color: #e3f2fd !important;
            border: 2px solid #2196f3;
        }
        
        .entry-row:hover {
            background-color: #e3f2fd !important;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #2196f3;
        }
        
        .btn {
            padding: 8px 16px;
            margin: 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: #2196f3;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #1976d2;
            transform: translateY(-1px);
        }
        
        .btn-success {
            background-color: #4caf50;
            color: white;
        }
        
        .btn-success:hover {
            background-color: #45a049;
            transform: translateY(-1px);
        }
        
        .btn-warning {
            background-color: #ff9800;
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #f57c00;
            transform: translateY(-1px);
        }
        
        .btn-info {
            background-color: #00bcd4;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #00acc1;
            transform: translateY(-1px);
        }
        
        .btn-disabled {
            background-color: #cccccc;
            color: #666666;
            cursor: not-allowed;
        }
        
        .btn-disabled:hover {
            background-color: #cccccc;
            transform: none;
        }
        
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            min-width: 100px;
        }
        
        .status-created {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-fetching, .status-uploading, .status-sending {
            background-color: #fff3e0;
            color: #f57c00;
        }
        
        .status-fetched, .status-uploaded, .status-sent {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .controls {
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
        }
        
        .loading {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #666;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 5px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                margin: 1px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Panel</h1>
            <p>Job Management System</p>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Job Name</th>
                        <th>Starting Time</th>
                        <th>Actions</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Entry Point Row -->
                    <tr class="entry-row">
                        <form method="POST" style="display: contents;">
                            <td>
                                <input type="text" name="job_name" id="current_job_name" 
                                       value="<?php echo htmlspecialchars($_SESSION['current_job']['job_name']); ?>"
                                       placeholder="Enter job name..." required>
                                       
                                <!-- Workflow Indicator -->
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
                                $statusClass = '';
                                switch(strtolower(str_replace(' ', '-', $currentStatus))) {
                                    case 'ready-to-create': $statusClass = 'status-created'; break;
                                    case 'data-fetched': $statusClass = 'status-fetched'; break;
                                    case 'csv-uploaded': $statusClass = 'status-fetched'; break;
                                    case 'sent-to-monday': $statusClass = 'status-fetched'; break;
                                    default: $statusClass = 'status-created';
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
                    
                    <!-- History Rows -->
                    <?php if (empty($jobs)): ?>
                    <tr>
                        <td colspan="4" class="no-data">
                            No jobs created yet. Use the form above to add your first job.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($job['job_name']); ?></strong></td>
                            <td>
                                <?php echo $job['starting_time'] ? date('M j, Y g:i A', strtotime($job['starting_time'])) : '<em style="color: #999;">Not started</em>'; ?>
                            </td>
                            <td class="actions">
                                <form method="POST" style="display: contents;">
                                    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                                    
                                    <button type="submit" name="action" value="fetch_data" class="btn btn-disabled" disabled>
                                        Fetch Data
                                    </button>
                                    
                                    <button type="submit" name="action" value="upload_csv" class="btn btn-disabled" disabled>
                                        Upload CSV
                                    </button>
                                    
                                    <button type="submit" name="action" value="send_to_monday" class="btn btn-disabled" disabled>
                                        Send to Monday
                                    </button>
                                </form>
                            </td>
                            <td>
                                <span class="status <?php 
                                    switch(strtolower(str_replace(' ', '-', $job['status']))) {
                                        case 'created': echo 'status-created'; break;
                                        case 'fetching-data...': case 'uploading-csv...': case 'sending-to-monday...': 
                                            echo 'status-fetching'; break;
                                        case 'data-fetched': case 'csv-uploaded': case 'sent-to-monday': 
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
                        <?php endforeach; ?>
                    <?php endif; ?>
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
    </div>
</body>
</html>