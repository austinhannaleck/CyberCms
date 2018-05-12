<?php



// then do something...
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
  }

?>