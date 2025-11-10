<?php
// Get the database URL from the Heroku environment variable
$db_url = getenv('JAWSDB_URL');

if ($db_url) {
    // Parse the URL to get the connection details
    $db_parts = parse_url($db_url);

    $hostname = $db_parts['host'];
    $username = $db_parts['user'];
    $password = $db_parts['pass'];
    $database = ltrim($db_parts['path'], '/');
} else {
    // Fallback for local development
    $hostname = '127.0.0.1';
    $username = 'root';
    $password = '';
    $database = 'plumberdb';
}

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$connection_sql = $conn;
