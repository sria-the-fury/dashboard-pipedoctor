<?php
include 'connection.php';

header('Content-Type: application/json');
$response = [];

$create_news = "CREATE TABLE IF NOT EXISTS news (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(80) NOT NULL,
    article TEXT NOT NULL,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

mysqli_query($connection_sql, $create_news);
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $title = $_POST['title'];
    $article = $_POST['article'];

    $insert_query = "INSERT INTO news (title, article) VALUES ('$title', '$article')";

    $result = mysqli_query($connection_sql, $insert_query);
    if ($result) {
        $response['success'] = true;
        $response['message'] = "Data inserted successfully!";
    } else {
        $response['success'] = false;
        $response['message'] = "Error in inserting data: " . mysqli_error($connection_sql);
    }
    echo json_encode($response);
}
