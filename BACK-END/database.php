// Connect PHP to MySQL database

<?php

// Declare Variables.

$host = "localhost";  // My database is on my own computer Cause XAMPP is running on my pc. 
$dbname = "voyageplus_db"; // This is the name of my database.
$username = "root"; // in XAMPP, this is the default MYSQL username.
$password = ""; // in XAMPP, this is the default MYSQL password.

$pdo = new PDO(   // This create a new connection to the database. PDO is a php tool used to talk to the database.

    "mysql:host=$host;dbname=$dbname;charset=utf8",
    $username,
    $password
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // This Tell PDO how to show database errors. ->setAttribute Change a setting for this connection. Change the PDO error setting to exception mode.