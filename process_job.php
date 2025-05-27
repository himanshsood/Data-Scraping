<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Check if job history exists
if (!isset($_SESSION['jobHistory'])) {
    exit(json_encode(['success' => false, 'message' => 'No job history found']));
}

$action = $_GET['action'] ?? '';
$jobId = $_GET['id'] ?? 0;

if ($action === 'cancel') {
    // Find and remove the job
    foreach ($_SESSION['jobHistory'] as $key => $job) {
        if ($job['id'] == $jobId) {
            unset($_SESSION['jobHistory'][$key]);
            $_SESSION['jobHistory'] = array_values($_SESSION['jobHistory']);
            echo json_encode(['success' => true, 'message' => 'Job cancelled successfully']);
            exit();
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'Job not found']);
    exit();
} 
elseif ($action === 'rollback') {
    // Find the job to rollback
    foreach ($_SESSION['jobHistory'] as $key => $job) {
        if ($job['id'] == $jobId) {
            // Add rollback job to history
            $rollbackJob = [
                'id' => count($_SESSION['jobHistory']) + 1,
                'name' => "Rollback: " . $job['name'],
                'startTime' => date('m/d/Y h:i:s A'),
                'status' => 'Rollback initiated',
                'timestamp' => time()
            ];
            
            array_unshift($_SESSION['jobHistory'], $rollbackJob);
            
            echo json_encode(['success' => true, 'message' => 'Rollback initiated']);
            exit();
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'Job not found']);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);