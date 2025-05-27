<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Initialize job history if not exists
if (!isset($_SESSION['jobHistory'])) {
    $_SESSION['jobHistory'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle manual update via AJAX
    if (isset($_POST['manual_update'])) {
        $jobName = "Manual Update";
        addJobToHistory($jobName, "Completed");
        
        echo json_encode(['success' => true, 'message' => 'Manual update completed']);
        exit();
    }

    // Handle form submissions
    $formType = $_POST['form_type'] ?? '';
    
    switch ($formType) {
        case 'auto_update':
            handleAutoUpdate();
            break;
            
        case 'csv_upload':
            handleCsvUpload();
            break;
            
        default:
            $_SESSION['message'] = "Invalid form submission";
            header('Location: index.php');
            exit();
    }
}

function handleAutoUpdate() {
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    
    if (empty($day) || empty($time)) {
        $_SESSION['message'] = "Please select both day and time";
        header('Location: index.php');
        exit();
    }
    
    if (!isset($_POST['enableAutoUpdate'])) {
        $_SESSION['message'] = "Please enable automatic updates first";
        header('Location: index.php');
        exit();
    }
    
    $jobName = "Auto Update - " . ucfirst($day);
    addJobToHistory($jobName, "Scheduled");
    
    $_SESSION['message'] = "Automatic update scheduled for $day at $time";
    header('Location: index.php');
    exit();
}

function handleCsvUpload() {
    $gender = $_POST['gender'] ?? '';
    $fileName = $_FILES['csvFile']['name'] ?? '';
    
    if (empty($gender)) {
        $_SESSION['message'] = "Please select a gender";
        header('Location: index.php');
        exit();
    }
    
    if (empty($fileName)) {
        $_SESSION['message'] = "Please select a CSV file";
        header('Location: index.php');
        exit();
    }
    
    // Process file upload
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $targetFile = $targetDir . basename($_FILES["csvFile"]["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if file is a CSV
    if ($fileType != "csv") {
        $_SESSION['message'] = "Only CSV files are allowed";
        header('Location: index.php');
        exit();
    }
    
    // Try to upload file
    if (move_uploaded_file($_FILES["csvFile"]["tmp_name"], $targetFile)) {
        $jobName = "CSV Upload for " . ucfirst($gender);
        addJobToHistory($jobName, "Sent to Monday");
        
        $_SESSION['message'] = "CSV file uploaded successfully for $gender";
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['message'] = "Sorry, there was an error uploading your file";
        header('Location: index.php');
        exit();
    }
}

function addJobToHistory($name, $status) {
    $jobId = count($_SESSION['jobHistory']) + 1;
    $currentTime = date('m/d/Y h:i:s A');
    
    $newJob = [
        'id' => $jobId,
        'name' => $name,
        'startTime' => $currentTime,
        'status' => $status,
        'timestamp' => time()
    ];
    
    // Add to beginning of array (latest first)
    array_unshift($_SESSION['jobHistory'], $newJob);
    
    // Keep only the last 50 jobs
    $_SESSION['jobHistory'] = array_slice($_SESSION['jobHistory'], 0, 50);
}