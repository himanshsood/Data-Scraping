<?php
    // AUTOMATIC SCHEDULE
    if(isset($_POST['form_type'])) {
        $formType = $_POST['form_type'] ?? '';
        if ($formType === 'auto_update') {
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
    }
?>