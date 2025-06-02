<?php
    $ISPROCESSRUNNING = false ; // LOCK VARIABLE (LOCK PROCESS)


    // FETCHING API
    $apiUrl = 'http://3.234.76.112:5000/api/updates/recent';
    $statusMap = [
        0 => 'PENDING',
        1 => 'INITIALIZED',
        2 => 'FETCHING',
        3 => 'MERGING',
        4 => 'UPLOADING',
        5 => 'COMPLETED',
        6 => 'FAILED',
        7 => 'TERMINATED'
    ];
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $dataRows = []; // ARRAY :this array will contain all the entries fetched from database 

    // FUNCTION TO SET PROPER DATE AND TRIGGER TYPE
    function formatDateTime($datetime) {
        $dt = new DateTime($datetime);
        return $dt->format('d M Y, h:i A'); // Example: 29 May 2025, 03:15 PM
    }
    function getFriendlyTrigger($trigger) {
        $map = [
            'form' => 'Manual Upload (Form)',
            'csv_upload' => 'CSV Upload',
            'schedule' => 'Scheduled Sync',
            'api' => 'Triggered via API',
            'cron' => 'System Cron Job',
            'user' => 'User-Initiated',
            'webhook' => 'Webhook Triggered'
        ];
        return $map[strtolower($trigger)] ?? ucfirst($trigger);
    }

    if (!curl_errno($ch)) {
        $data = json_decode($response, true);
        if ($data && isset($data['status']) && $data['status'] === 'success') {
                        // LOCK : Check first entry's statusCode to determine if process is running
                        if (isset($data['data'][0]['status']) && $data['data'][0]['status'] < 5) {
                            $ISPROCESSRUNNING = true;
                        }
                        else{
                            $ISPROCESSRUNNING = false ; 
                        }
            foreach ($data['data'] as $row) {
                $dataRows[] = [
                    'id' => $row['id'] ?? 'N/A',
                    'started_at' => isset($row['started_at']) ? formatDateTime($row['started_at']) : 'N/A',
                    'trigger_type' => isset($row['trigger_type']) ? getFriendlyTrigger($row['trigger_type']) : 'N/A',
                    'status' => $statusMap[$row['status']] ?? 'UNKNOWN',
                    'statusCode' => $row['status'],
                ];
            }
        }
    }

    // PAGINATION
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $itemsPerPage = 10;
    $totalItems = count($dataRows);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $startIndex = ($page - 1) * $itemsPerPage;
    $paginatedRows = array_slice($dataRows, $startIndex, $itemsPerPage); // THIS IS THE CURRENT ARRAY OF 10 THAT WE ARE DISPLAYING 

?>