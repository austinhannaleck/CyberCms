<!DOCTYPE HTML>
<html>
    <body>
        <?php
            
            $configs = include('config/configs.php');
            
            $dbServerName = $configs['host'];
            $dbUsername   = $configs['username'];
            $dbPassword   = $configs['password'];
            $dbName       = $configs['database'];
            
            $conn = mysqli_connect($dbServerName, $dbUsername, $dbPassword, $dbName);
            
            if(mysqli_ping($conn)) {
            echo "Connection OK";
            } else {
            echo "ERROR: " . mysqli_error($conn);
            }
            
            mysqli_close($conn);
            
        ?>
    </body>
</html>