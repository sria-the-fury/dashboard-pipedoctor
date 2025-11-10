<?php
include 'connection.php';


if ($conn->connect_error) {

    echo json_encode(['success' => false, 'message' => 'Connection Failed']);
    exit;
}


$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

$response = ['success' => false, 'message' => 'Error: ID not provided or invalid.'];

if ($id > 0) {

    $sql = "DELETE FROM testimony WHERE id = ?";

    $stmt = $conn->prepare($sql);


    $stmt->bind_param("i", $id);


    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'message' => 'Item not found.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Query failed to execute.'];
    }

    $stmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
