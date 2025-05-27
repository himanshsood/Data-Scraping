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
        echo json_encode(['success' => true, 'message' => 'Manual update completed']);
        exit();
        // $apiUrl = 'http://localhost:3000/manual-update'; // Your Express API endpoint
        // // Initialize cURL to make POST request with NO data
        // $ch = curl_init($apiUrl);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // // Empty POST body
        // curl_setopt($ch, CURLOPT_POSTFIELDS, []);
        // // Execute the request
        // $response = curl_exec($ch);
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // curl_close($ch);
        // $responseData = json_decode($response, true);
        // // Handle success/failure
        // if ($httpCode === 200 && isset($responseData['success']) && $responseData['success'] === true) {
        //     echo json_encode(['success' => true, 'message' => 'Manual update completed']);
        // } else {
        //     echo json_encode([
        //         'success' => false,
        //         'message' => $responseData['message'] ?? 'Error in manual update. Please contact the developers.'
        //     ]);
        // }
        // exit();
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
    $_SESSION['message'] = "Automatic update scheduled for $day at $time";
    header('Location: index.php');
    exit();

    // $day = $_POST['day'] ?? '';
    // $time = $_POST['time'] ?? '';
    // $enabled = isset($_POST['enableAutoUpdate']); // true if checked, false if not

    // if (empty($day) || empty($time)) {
    //     $_SESSION['message'] = "Please select both day and time";
    //     header('Location: index.php');
    //     exit();
    // }
    // $jobName = "Auto Update - " . ucfirst($day);
    // // Prepare data
    // $postData = [
    //     'day' => $day,
    //     'time' => $time,
    //     'jobName' => $jobName,
    //     'enabled' => $enabled
    // ];
    // // Send to backend
    // $apiUrl = 'http://localhost:3000/schedule-update';
    // $ch = curl_init($apiUrl);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_POST, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    // $response = curl_exec($ch);
    // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close($ch);
    // $responseData = json_decode($response, true);
    // if ($httpCode === 200 && isset($responseData['success']) && $responseData['success'] === true) {
    //     $statusText = $enabled ? "enabled" : "disabled";
    //     $_SESSION['message'] = "Automatic update $statusText for $day at $time";
    // } else {
    //     $_SESSION['message'] = $responseData['message'] ?? "Error scheduling update. Please contact developers.";
    // }
    // header('Location: index.php');
    // exit();

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

        // // POST REQUEST FOR UPLOADING .CSV 
        // $jobName = "CSV Upload for " . ucfirst($gender);
        // $status = "Sent to Monday";
        // $startTime = date('m/d/Y h:i:s A');
        // $jobId = count($_SESSION['jobHistory']) + 1;
        // // ✅ Send to backend API BEFORE storing in session
        // $apiUrl = "https://your-backend-api.com/upload";
        // $curl = curl_init();
        // $postFields = [
        //     'jobId' => $jobId,
        //     'jobName' => $jobName,
        //     'startTime' => $startTime,
        //     'status' => $status,
        //     'csvFile' => new CURLFile($targetFile, 'text/csv', basename($targetFile))
        // ];
        // curl_setopt_array($curl, [
        //     CURLOPT_URL => $apiUrl,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_POST => true,
        //     CURLOPT_POSTFIELDS => $postFields
        // ]);
        // $response = curl_exec($curl);
        // $error = curl_error($curl);
        // curl_close($curl);
        // if ($error) {
        //     $_SESSION['message'] = "Upload failed: API error - $error";
        //     header('Location: index.php');
        //     exit();
        // }
        // // ✅ Only add to job history if API call succeeds
        // addJobToHistory($jobName, $status);
        // $_SESSION['message'] = "CSV file uploaded and sent to API successfully for $gender";
        // header('Location: index.php');
        // exit();
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