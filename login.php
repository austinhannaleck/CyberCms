<?php

$dbServerName = 'localhost';
$dbUsername   = 'root';
$dbPassword   = '';
$dbName       = 'cms_test';

// Only process post requests
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    // Create DB connection
    $conn = new mysqli($dbServerName, $dbUsername, $dbPassword, $dbName);
    
    // Check connection
    if($conn->connect_error)
    {
        // Exit process and print error
        die("Connection to database failed: " . $conn->connect_error);
    }
    
    // Prepare statement and bind to variables
    if(!($conn->prepare("SELECT user_password FROM user WHERE user_name = ?")))
    {
        http_response_code(400);
        die("Error preparing SQL statement: " . $conn->error);
        
    }
    else
    {
        $stmt = $conn->prepare("SELECT user_password FROM user WHERE user_name = ?");
    }
    
    $stmt->bind_param("s", $username);
    
    // Set parameters and execute statement
    $username  = $_POST['username'];
    $password  = $_POST['password'];
    
    if(empty($username) || empty($password))
    {
        http_response_code(400);
        die("Please fill in all fields.");
    }
    else
    {
        $stmt->execute();
    
        // Bind result to variable
        $stmt->bind_result($db_pass);
        
        $password_to_compare = null;
        
        while($stmt->fetch())
        {
            $password_to_compare =  $db_pass;
        }
        
        if($password_to_compare == $password)
        {
            echo $username;
            exit();
        }
        else
        {
            http_response_code(400);
            die("ERROR: This username and password combination do not exist.");
        }
    }

}
else
{
    http_response_code(403);
    echo "There was a problem with your login attempt, please try again";
}

?>