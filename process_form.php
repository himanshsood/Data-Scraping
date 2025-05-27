<?php
session_start();

// --- AUTH CHECK ---
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// --- INITIALIZE JOB HISTORY ---
if (!isset($_SESSION['jobHistory'])) {
    $_SESSION['jobHistory'] = [];
}

// --- HANDLE POST REQUESTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manual Update Triggered via AJAX
    if (isset($_POST['manual_update'])) {
        echo json_encode(['success' => true, 'message' => 'Manual update completed']);
        exit();
    }

    // Handle Different Form Submissions
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

// --- HANDLE AUTO UPDATE FORM ---
function handleAutoUpdate() {
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    $enabled = isset($_POST['enableAutoUpdate']);

    if (empty($day) || empty($time)) {
        $_SESSION['message'] = "Please select both day and time";
        header('Location: index.php');
        exit();
    }

    $jobName = "Auto Update - " . ucfirst($day);
    $_SESSION['message'] = "Automatic update scheduled for $day at $time";
    header('Location: index.php');
    exit();

    // Example: Send to Express API
    /*
    $postData = json_encode([
        'day' => $day,
        'time' => $time,
        'jobName' => $jobName,
        'enabled' => $enabled
    ]);

    $ch = curl_init('http://localhost:3000/schedule-update');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($httpCode === 200 && $responseData['success']) {
        $statusText = $enabled ? "enabled" : "disabled";
        $_SESSION['message'] = "Automatic update $statusText for $day at $time";
    } else {
        $_SESSION['message'] = $responseData['message'] ?? "Error scheduling update. Please contact developers.";
    }

    header('Location: index.php');
    exit();
    */
}

// --- HANDLE CSV UPLOAD FORM ---
function handleCsvUpload() {
    $gender = $_POST['gender'] ?? '';
    $file = $_FILES['csvFile'] ?? null;

    if (empty($gender)) {
        $_SESSION['message'] = "Please select a gender";
        header('Location: index.php');
        exit();
    }

    if (!$file || empty($file['name'])) {
        $_SESSION['message'] = "Please select a CSV file";
        header('Location: index.php');
        exit();
    }

    // Ensure uploads directory exists
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . basename($file["name"]);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if ($fileType !== "csv") {
        $_SESSION['message'] = "Only CSV files are allowed";
        header('Location: index.php');
        exit();
    }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $jobName = "CSV Upload for " . ucfirst($gender);
        addJobToHistory($jobName, "Sent to Monday");

        $_SESSION['message'] = "CSV file uploaded successfully for $gender";
        header('Location: index.php');
        exit();

        // Optional: Forward file to backend API
        /*
        $apiUrl = "https://your-backend-api.com/upload";
        $curl = curl_init();
        $postFields = [
            'jobId' => count($_SESSION['jobHistory']) + 1,
            'jobName' => $jobName,
            'startTime' => date('m/d/Y h:i:s A'),
            'status' => "Sent to Monday",
            'csvFile' => new CURLFile($targetFile, 'text/csv', basename($targetFile))
        ];
        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            $_SESSION['message'] = "Upload failed: API error - $error";
            header('Location: index.php');
            exit();
        }

        addJobToHistory($jobName, "Sent to Monday");
        $_SESSION['message'] = "CSV uploaded and sent to API for $gender";
        header('Location: index.php');
        exit();
        */
    } else {
        $_SESSION['message'] = "Error uploading your file";
        header('Location: index.php');
        exit();
    }
}

// --- HELPER FUNCTION TO ADD TO JOB HISTORY ---
function addJobToHistory($name, $status) {
    $jobId = count($_SESSION['jobHistory']) + 1;
    $newJob = [
        'id' => $jobId,
        'name' => $name,
        'startTime' => date('m/d/Y h:i:s A'),
        'status' => $status,
        'timestamp' => time()
    ];

    array_unshift($_SESSION['jobHistory'], $newJob); // Add to top
    $_SESSION['jobHistory'] = array_slice($_SESSION['jobHistory'], 0, 50); // Keep recent 50
}
