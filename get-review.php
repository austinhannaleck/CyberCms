<?php

$configs = include('config/configs.php');

$dbServerName = $configs['host'];
$dbUsername   = $configs['username'];
$dbPassword   = $configs['password'];
$dbName       = $configs['database'];

if($_SERVER["REQUEST_METHOD"] == "GET")
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
    if(!($conn->prepare("SELECT * FROM review WHERE review_artist='Bon jovi'")))
    {
        http_response_code(400);
        die("Error preparing SQL statement");
    }
    else
    {
        $stmt = $conn->prepare("SELECT * FROM review WHERE review_artist='Bon jovi'");
    }
    
    $stmt->execute();
    $output = array();
    
    if($stmt->bind_result($col1, $col2, $col3,
                         $col4, $col5, $col6,
                         $col7, $col8, $col9, $col10, $col11, $col12))
    {   
        while($stmt->fetch())
        {
            $output[] = array($col1, $col2, $col3,
                         $col4, $col5, $col6,
                         $col7, $col8, $col9, $col10, $col11, $col12);
        }
    }
    else
    {
        echo "Error fetching review";
    }
    
    if($stmt->close())
    {
        echo json_encode($output);
        exit();
    }
    else
    {
        die("Error inserting data into table");
    }
    $conn->close();

}
else
{
    http_response_code(400);
    die("Failure to add user to database, please try again");
}
    
?>