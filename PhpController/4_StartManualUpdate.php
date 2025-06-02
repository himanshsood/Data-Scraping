<?php 
    // Start a manual update
    if (isset($_POST['manual_update'])) {
        $apiUrl = 'http://3.234.76.112:5000/api/updates/start';

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
?>