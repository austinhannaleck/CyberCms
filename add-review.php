<?php

$dbServerName = 'localhost';
$dbUsername   = 'root';
$dbPassword   = '';
$dbName       = 'cms_test';

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
    if(!($conn->prepare("INSERT INTO review (review_artist, review_title, review_genre, review_released_date, review_artist_website, review_content, review_date, review_embed, review_img_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);")))
    {
        http_response_code(400);
        die("Error preparing SQL statement");
    }
    else
    {
        $stmt = $conn->prepare("INSERT INTO review (review_artist, review_title, review_genre, review_released_date, review_artist_website, review_content, review_date, review_embed, review_img_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
    }
    
    $stmt->bind_param("sssssssss", $artist,
                                   $title,
                                   $genre,
                                   $rel_date,
                                   $website,
                                   $content,
                                   $rev_date,
                                   $embed,
                                   $img_path);
    
    // Set parameters and execute statement
    $artist   = $_POST['artist'];
    $title    = $_POST['title'];
    $genre    = $_POST['genre'];
    $rel_date = $_POST['release-date']; // Date of album release
    $website  = $_POST['artist-website'];
    $content  = $_POST['content'];
    $rev_date = date('Y-m-d');
    $embed    = $_POST['embed'];
    $img_path = $_POST['image_path'];
    
    $stmt->execute();
    
    if($stmt->close())
    {
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
    
?>