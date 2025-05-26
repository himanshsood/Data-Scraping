<?php
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobId = $_POST['job_id'] ?? null;
    $jobName = $_POST['job_name'] ?? '';

    switch ($_POST['action']) {
        case 'add_job':
            if (!empty($jobName)) {
                $_SESSION['jobs'][] = [
                    'id' => uniqid(),
                    'job_name' => $jobName,
                    'starting_time' => null,
                    'status' => 'Created',
                    'data_fetched' => false,
                    'csv_uploaded' => false,
                    'sent_to_monday' => false
                ];
                $_SESSION['current_job'] = default_current_job();
            }
            break;

        case 'fetch_data_current':
        case 'upload_csv_current':
        case 'send_to_monday_current':
            handle_current_job_update($_POST['action'], $jobName);
            break;

        case 'fetch_data':
        case 'upload_csv':
        case 'send_to_monday':
            simulate_action_on_job($jobId, $_POST['action']);
            break;

        case 'clear_history':
            $_SESSION['jobs'] = [];
            break;

        case 'reset_current':
            $_SESSION['current_job'] = default_current_job();
            break;
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

function default_current_job() {
    return [
        'job_name' => '',
        'starting_time' => null,
        'status' => 'Ready to Create',
        'data_fetched' => false,
        'csv_uploaded' => false,
        'sent_to_monday' => false
    ];
}

function handle_current_job_update($action, $jobName) {
    $_SESSION['current_job']['job_name'] = $jobName;

    switch ($action) {
        case 'fetch_data_current':
            $_SESSION['current_job']['status'] = 'Data Fetched';
            $_SESSION['current_job']['data_fetched'] = true;
            break;
        case 'upload_csv_current':
            $_SESSION['current_job']['status'] = 'CSV Uploaded';
            $_SESSION['current_job']['csv_uploaded'] = true;
            break;
        case 'send_to_monday_current':
            $_SESSION['current_job']['starting_time'] = date('Y-m-d H:i:s');
            $_SESSION['current_job']['status'] = 'Sent to Monday';
            $_SESSION['current_job']['sent_to_monday'] = true;

            $_SESSION['jobs'][] = array_merge(['id' => uniqid()], $_SESSION['current_job']);
            $_SESSION['current_job'] = default_current_job();
            break;
    }
}

function simulate_action_on_job($jobId, $action) {
    foreach ($_SESSION['jobs'] as &$job) {
        if ($job['id'] === $jobId) {
            $job['status'] = ucfirst(str_replace('_', ' ', $action)) . '...';
            sleep(1);
            switch ($action) {
                case 'fetch_data': $job['status'] = 'Data Fetched'; break;
                case 'upload_csv': $job['status'] = 'CSV Uploaded'; break;
                case 'send_to_monday':
                    $job['starting_time'] = date('Y-m-d H:i:s');
                    $job['status'] = 'Sent to Monday';
                    break;
            }
            break;
        }
    }
}
