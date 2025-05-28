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
