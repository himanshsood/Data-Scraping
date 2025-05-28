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
                'status' => $statusMap[$row['status']] ?? 'UNKNOWN'
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
            margin: 40px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin:auto;
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
        .recent-updates{
            margin-left:520px;
        }
    </style>
</head>
<body>
<br>
<br>
<br><br>
<h2 class="recent-updates">Recent Updates</h2>

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
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
