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

?>