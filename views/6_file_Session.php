<?php

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['path'], $data['type'])) {
    $_SESSION['uploaded_csv_path'] = $data['path'];
    $_SESSION['uploaded_csv_type'] = $data['type'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
}

?>