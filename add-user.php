<?php

$configs = include('config/configs.php');

$dbServerName = $configs['host'];
$dbUsername   = $configs['username'];
$dbPassword   = $configs['password'];
$dbName       = $configs['database'];
ini_set('display_errors', 1);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Create DB connection
    $conn = new mysqli($dbServerName, $dbUsername, $dbPassword, $dbName);
    
    // Check connection
    if($conn->connect_error)
    {
        // Exit process and print error
        http_response_code(400);
        die("Connection to database failed: " . $conn->connect_error);
    }
    
    // Prepare statement and bind to variables
    if(!($conn->prepare("SELECT * FROM user WHERE user_name = ?")))
    {
        http_response_code(400);
        die("Error preparing SQL statement");
    }
    else
    {
        $stmt = $conn->prepare("SELECT * FROM user WHERE user_name = ?");
    }
    
    $stmt->bind_param("s", $username);
    
    // Set parameters and execute statement
    $username       = $_POST['username'];
    $password       = $_POST['password'];
    $password_check = $_POST['password-check'];
    $author         = $_POST['author-name'];
    
    if($password != $password_check)
    {
        http_response_code(400);
        die("Passwords do not match.");
    }
    else
    {
        $stmt->execute();
        
        // If user name exists, end script and notify user
        if($stmt->get_result()->num_rows > 0)
        {
            http_response_code(400);
            die("Username already taken - please choose another.");
        }
        else
        {
            // Close first statement before preparing a new one.
            $stmt->close();
            
            // Prepare statement and bind to variables
            if(!($conn->prepare("INSERT INTO user (user_name, user_password, user_author) VALUES (?, ?, ?);")))
            {
                http_response_code(400);
                die("Error preparing INSERT SQL statement");
            }
            else
            {
                $stmt = $conn->prepare("INSERT INTO user (user_name, user_password, user_author) VALUES (?, ?, ?);");
                
                $stmt->bind_param("sss", $username, $password, $author);
                $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);
                if($stmt->execute() == false)
                {
                    die("Failed to add username to database");
                }
                else
                {
                    http_response_code(200);
                    echo "Username added!";                    
                }
                
                $stmt->close();
                $conn->close();
                exit();
            }
        }
    }
}
else
{
    http_response_code(400);
    die("Failure to add user to database, please try again");
}
    
?>