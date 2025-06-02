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
        .pagination {
            margin: 20px auto;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .pagination a {
            padding: 10px 20px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            border-radius: 6px;
        }
        .pagination a.disabled {
            background-color: #ccc;
            pointer-events: none;
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
            <!-- <th>ID</th> -->
            <th>Start Time</th>
            <th>Trigger Type</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($paginatedRows)): ?>
            <tr><td colspan="4">No data available or failed to fetch API.</td></tr>
        <?php else: ?>
            <?php foreach ($paginatedRows as $row): ?>
                <tr>
                    <!-- <td> here we can display process id if we want => htmlspecialchars($row['id']) </td> -->
                    <td><?= htmlspecialchars($row['started_at']) ?></td>
                    <td><?= htmlspecialchars($row['trigger_type']) ?></td>
                    <td style="display:flex;align-items:center;">
                        <?= htmlspecialchars($row['status']) ?>&nbsp;&nbsp;&nbsp;
                        <?php if ($row['statusCode'] < 5): ?>
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
<!-- Pagination Buttons -->
<div class="pagination" style="margin: 20px auto; width: 100%; display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" style="padding: 10px 16px; text-decoration: none; background-color: #3498db; color: white; border-radius: 6px; font-size: 14px;">Previous</a>
    <?php else: ?>
        <a class="disabled" style="padding: 10px 16px; text-decoration: none; background-color: #ccc; color: white; border-radius: 6px; font-size: 14px; pointer-events: none;">Previous</a>
    <?php endif; ?>

    <?php
        $visiblePages = 3;
        $startPage = max(1, $page - 1);
        $endPage = min($totalPages, $startPage + $visiblePages - 1);
        if ($endPage - $startPage < $visiblePages - 1) {
            $startPage = max(1, $endPage - $visiblePages + 1);
        }

        if ($startPage > 1):
            $prevBlur = $startPage - 1;
    ?>
        <a href="?page=<?= $prevBlur ?>" style="padding: 10px 16px; text-decoration: none; background-color: #e0e0e0; color: #333; border-radius: 6px; font-size: 14px; opacity: 0.4; filter: blur(1px);"> <?= $prevBlur ?> </a>
    <?php endif; ?>

    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
        <a href="?page=<?= $i ?>" style="padding: 10px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; background-color: <?= $i == $page ? '#2c80b4' : '#3498db' ?>; color: white; font-weight: <?= $i == $page ? 'bold' : 'normal' ?>;">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($endPage < $totalPages): 
        $nextBlur = $endPage + 1;
    ?>
        <a href="?page=<?= $nextBlur ?>" style="padding: 10px 16px; text-decoration: none; background-color: #e0e0e0; color: #333; border-radius: 6px; font-size: 14px; opacity: 0.4; filter: blur(1px);"> <?= $nextBlur ?> </a>
    <?php endif; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" style="padding: 10px 16px; text-decoration: none; background-color: #3498db; color: white; border-radius: 6px; font-size: 14px;">Next</a>
    <?php else: ?>
        <a class="disabled" style="padding: 10px 16px; text-decoration: none; background-color: #ccc; color: white; border-radius: 6px; font-size: 14px; pointer-events: none;">Next</a>
    <?php endif; ?>
</div>
