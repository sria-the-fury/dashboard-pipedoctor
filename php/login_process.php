<?php
// Start the session
session_start();

include 'connection.php';


$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {

    header('Location: ../login.php?error=empty');
    exit;
}


$stmt = $connection_sql->prepare("SELECT password_hash FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['password_hash'];

    if (password_verify($password, $hashed_password)) {

        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $email;


        header('Location: .././');
        exit;
    }
}


header('Location: ../login.php?error=invalid');
exit;
