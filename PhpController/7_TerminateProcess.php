<?php
    // TERMINATE FUNCTION 
    if (isset($_POST['terminate_run'])) {
        // echo or alert here to see weather this funciton has received the id or not
        $runId = intval($_POST['terminate_run']);
        $apiUrl = "http://3.234.76.112:5000/api/updates/terminate/$runId";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);  // It's a POST request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($response === false) {
            error_log('cURL error: ' . curl_error($ch));
        } else {
            error_log("Response from Flask: $response");
            error_log("HTTP code: $httpCode");
        }
        curl_close($ch);

        $responseData = json_decode($response, true);
        if ($httpCode >= 200 && $httpCode < 300 && isset($responseData['status']) && $responseData['status'] === 'success') {
            echo json_encode(['status' => 'success']);
        } else {
            $message = $responseData['message'] ?? 'Termination failed. Please try again.';
            echo json_encode(['status' => 'error', 'message' => $message]);
        }
        exit();
    }

?>