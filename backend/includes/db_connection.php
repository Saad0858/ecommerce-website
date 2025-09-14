<?php
require_once 'config.php';

$db_host = 'localhost';
$db_user = 'root'; // Replace with your MySQL username
$db_pass = ''; // Replace with your MySQL password
$db_name = 'eyestore_db';


// Create connection
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($mysqli->connect_error) {
    if (ENVIRONMENT === 'development') {
        die("Connection failed: " . $mysqli->connect_error);
    } else {
        error_log("Database connection error: " . $mysqli->connect_error);
        die("Sorry, we're experiencing technical difficulties.");
    }
}

// Set charset to utf8
$mysqli->set_charset("utf8mb4");
?>