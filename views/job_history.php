<?php
$apiUrl = 'http://localhost:5000/api/updates/recent';

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

$dataRows = [];

if (!curl_errno($ch)) {
    $data = json_decode($response, true);
    if ($data && isset($data['status']) && $data['status'] === 'success') {
        foreach ($data['data'] as $row) {
            $dataRows[] = [
                'id' => $row['id'] ?? 'N/A',
                'started_at' => $row['started_at'] ?? 'N/A',
                'trigger_type' => $row['trigger_type'] ?? 'N/A',
                'status' => $statusMap[$row['status']] ?? 'UNKNOWN',
                'statusCode' => $row['status'] ,
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding:0px;
            
        }
        h2{
            text-align:center;
            padding:10px;
            margin:0px;
            border-bottom:3px solid #3498db ; 
        }
        .table-wrapper {
            overflow-x: auto;
            width: 100%;
        }
        table {
            min-width:768px; 
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
<br>
<br>
<h2 class="recent-updates" style="font-size:30px;font-weight:400;">Recent Updates</h2>
<br>
<div class="table-wrapper">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Start Time</th>
            <th>Trigger Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($dataRows)): ?>
            <tr><td colspan="4">No data available or failed to fetch API.</td></tr>
        <?php else: ?>
            <?php foreach ($dataRows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['started_at']) ?></td>
                    <td><?= htmlspecialchars($row['trigger_type']) ?></td>
                    <td style="display:flex;align-items:center;">
                        <?= htmlspecialchars($row['status']) ?>&nbsp;&nbsp;&nbsp;
                        <?php if ($row['statusCode'] < 4): ?>
                            <button onClick="CancelFunction(<?= htmlspecialchars($row['id']) ?>)" style="
                                padding: 6px 12px;
                                border-radius: 6px;
                                background-color: #dc3545;
                                color: white;
                                border: none;
                                cursor: pointer;
                            ">Cancel</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
                        </div>
</body>
</html>
