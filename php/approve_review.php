<?php
include 'connection.php';

if ($conn->connect_error) {

    echo json_encode(['success' => false, 'message' => 'Connection Failed: ' . $conn->connect_error]);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$archive_status = isset($_POST['archived']) ? (int)$_POST['archived'] : 0;
$approved_status = isset($_POST['approved']) ? (int)$_POST['approved'] : 0;

$response = ['success' => false, 'message' => 'Invalid ID.'];

if ($id > 0) {

    $sql = "UPDATE reviews SET approved = ?, archived = ? WHERE id = ?";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param("iii", $approved_status, $archive_status, $id);


    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            $response = ['success' => true];
        } else {

            $response = ['success' => false, 'message' => 'No review found with that ID.'];
        }
    } else {

        $response = ['success' => false, 'message' => 'Database query failed.'];
    }

    $stmt->close();
}

$conn->close();


echo json_encode($response);
