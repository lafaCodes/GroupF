<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'wholesalers_combined');
 
/* Attempt to connect to MySQL database */
$conn = new mysqli("localhost", "root", "", "wholesalers_combined");
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
    else{
        echo "Database successfully connected";
    }

?>
