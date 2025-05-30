<?php 
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
?>