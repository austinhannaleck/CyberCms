<?php

$configs = include('../config/configs.php');

$dbServerName = $configs['host'];
$dbUsername   = $configs['username'];
$dbPassword   = $configs['password'];
$dbName       = $configs['database'];

// Create DB connection
$conn = new mysqli($dbServerName, $dbUsername, $dbPassword, $dbName);

// Check connection
if($conn->connect_errno)
{
    // Exit process and print error
    http_response_code(400);
    die("Connection to database failed: " . $conn->connect_errno);
}

$result = $conn->query("SELECT * FROM review ORDER BY review_id DESC LIMIT 1");
 
$row = $result->fetch_assoc();

echo json_encode($row);
exit();

?>