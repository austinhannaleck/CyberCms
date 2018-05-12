<?php

$configs = include('config/configs.php');

$dbServerName = $configs['host'];
$dbUsername   = $configs['username'];
$dbPassword   = $configs['password'];
$dbName       = $configs['database'];

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
    if(!($conn->prepare("INSERT INTO review (review_artist, review_title, review_genre, review_released_date, review_artist_website, review_content, review_date, review_embed, review_img_path, review_tracks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);")))
    {
        http_response_code(400);
        die("Error preparing SQL statement");
    }
    else
    {
        $stmt = $conn->prepare("INSERT INTO review (review_artist, review_title, review_genre, review_released_date, review_artist_website, review_content, review_date, review_embed, review_img_path, review_tracks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
    }
    
    $stmt->bind_param("ssssssssss", $artist,
                                   $title,
                                   $genre,
                                   $rel_date,
                                   $website,
                                   $content,
                                   $rev_date,
                                   $embed,
                                   $img_path,
                                   $tracks);
    
    // Set parameters and execute statement
    $artist   = $_POST['artist'];
    $title    = $_POST['title'];
    $genre    = $_POST['genre'];
    $rel_date = $_POST['release-date']; // Date of album release
    $website  = $_POST['artist-website'];
    $content  = $_POST['content'];
    $rev_date = date('Y-m-d');
    $embed    = $_POST['embed'];
    $img_path = $_FILES['file']['name'];
    $tracks   = $_POST['tracks'];
    
    if($stmt->execute() == false) {
        echo "Printing POST ";
        print_r($_POST);
        echo "Printing FILES ";
        print_r($_FILES);
        echo "Printing error ";
        echo $FILES['file']['error'];
        //die("Error submitting file. file name: " . $_FILES['file']['tmp_name'] . " post: " .  $_POST['file']);
        //die("Statement execution failed " . $stmt->error);
    };
    //
    if($stmt->close())
    {
        upload_image(); // this will currently fail locally, update configs.
        echo "Success!";
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

function upload_image()
{
    $file = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];



    // upload file - use file name for 2nd parameter
    if (move_uploaded_file($file, "/home/nc6h8f8eaaoj/public_html/images/albums/" . $_FILES['file']['name']))
    {
        echo "Successfully uploaded $name";
    }
    else
    {
    
        debug_print_backtrace();
        echo "Error uploading $name : " . print_r(error_get_last());
        die();
    }
}
    
?>