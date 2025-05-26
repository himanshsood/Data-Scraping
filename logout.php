<?php
// logout.php
session_start();

// Destroy all session data
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>

<?php
// Updated init.php with login check
session_start();

// Check if user is logged in (except for login.php)
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page !== 'login.php' && (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)) {
    header('Location: login.php');
    exit();
}

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