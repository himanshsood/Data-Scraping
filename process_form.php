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
    // Start a manual update
    if (isset($_POST['manual_update'])) {
        $apiUrl = 'http://localhost:5000/api/updates/start';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, []); // No payload
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode === 202 && isset($responseData['run_id'])) {
            echo json_encode([
                'success' => true,
                'run_id' => $responseData['run_id'],
                'message' => 'Manual update started (Run ID: ' . $responseData['run_id'] . ')'
            ]);
        } else {
            $errorMessage = $responseData['message'] ?? 'Unknown error from backend';
            echo json_encode(['success' => false, 'message' => "Manual update failed: $errorMessage"]);
        }
        exit();
    }

    // Polling progress check
    if (isset($_POST['check_progress']) && isset($_POST['run_id'])) {
        $runId = intval($_POST['run_id']);
        $statusApiUrl = "http://localhost:5000/api/updates/progress/$runId";

        $ch = curl_init($statusApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); // <-- explicitly set GET method

        $response = curl_exec($ch);

        if ($response === false) {
            echo json_encode([
                'status' => 'error',
                'message' => 'cURL error: ' . curl_error($ch)
            ]);
            curl_close($ch);
            exit();
        }

curl_close($ch);
$responseData = json_decode($response, true);
echo json_encode($responseData);
exit();

    }
}






if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['form_type'] ?? '';
    if ($formType === 'auto_update') {
        handleAutoUpdate();
    }
}
// --- HANDLE AUTO UPDATE FORM ---
function handleAutoUpdate() {
    $day = $_POST['day'] ?? 'monday';
    $time = $_POST['time'] ?? '00:00';
    $enabled = isset($_POST['enableAutoUpdate']);

    $apiUrl = 'http://localhost:5000/api/schedules';
    $postData = json_encode([
        'time' => $time,           // key fixed
        'day' => $day,             // key fixed
        'active' => $enabled       // key fixed
    ]);
    $ch = curl_init();
    if ($enabled) {
        // POST request to insert or replace schedule
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
    } else {
        // DELETE request to remove schedule
        curl_setopt($ch, CURLOPT_URL, $apiUrl); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $responseData = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300 && isset($responseData['status']) && $responseData['status'] === 'success') {
        if ($enabled) {
            $_SESSION['message'] = "Automatic update scheduled for $day at $time";
            $automatic = true ; 
            // day and time is now set
        } else {
            $_SESSION['message'] = "Automatic update disabled !";
            $automatic = false ; 
        }
    } else {
        $_SESSION['message'] = $responseData['message'] ?? "Error updating schedule. Please contact developers.";
        // If error occurred then we should not update the $automatic value and let it be like it is 
    }
    header('Location: index.php');
    exit();
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
