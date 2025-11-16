<?php
session_start();
include 'connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

// Get data from the form
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$email = $_SESSION['admin_email'];

// Simple validation
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
    exit;
}

if (strlen($new_password) < 5 && strlen($confirm_password) < 5) {
    echo json_encode(['success' => false, 'message' => 'Password at least 6 characters']);
    exit;
}

//Get current password hash from DB
$stmt = $connection_sql->prepare("SELECT password_hash FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

$row = $result->fetch_assoc();
$hashed_password = $row['password_hash'];


if (!password_verify($current_password, $hashed_password)) {
    echo json_encode(['success' => false, 'message' => 'Incorrect current password.']);
    exit;
}

$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$update_stmt = $connection_sql->prepare("UPDATE admin SET password_hash = ? WHERE email = ?");
$update_stmt->bind_param("ss", $new_hashed_password, $email);

if ($update_stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error. Could not update password.']);
}

$stmt->close();
$update_stmt->close();
mysqli_close($connection_sql);
