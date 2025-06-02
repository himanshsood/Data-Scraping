<?php

$automatic = false;
$day = "";
$time = "";
    $apiUrl = "http://3.234.76.112:5000/api/schedules/active";  // no trailing slash needed, backend has strict_slashes=False
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute request
    $response = curl_exec($ch);

    if ($response === false) {
        error_log("cURL Error: " . curl_error($ch));
    } else {
        $data = json_decode($response, true);
        if (
            isset($data['status']) && $data['status'] === 'success' &&
            isset($data['data']['schedules']) && is_array($data['data']['schedules']) && count($data['data']['schedules']) > 0
        ) {
            $schedule = $data['data']['schedules'][0];
            if (isset($schedule['is_active']) && ($schedule['is_active'] === true || intval($schedule['is_active']) === 1)) {
                $automatic = true;
            } else {
                $automatic = false;
            }
            $day = $schedule['day_of_week'] ?? "";
            $time = $schedule['schedule_time'] ?? "";
        }
    }
    curl_close($ch);
    
?>