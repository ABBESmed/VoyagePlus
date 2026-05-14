<?php

require_once "database.php";

if($_SERVER["REQUEST_METHOD"] === "POST") {



    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        die("passwords do not match.");   // Stop the PHP code and show this message.
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // password_hash Protect / encrypt the password. PASSWORD_DEFAULT PHP, use the best default password protection method.

    $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);  // This prepares the SQL before running it.

    $stmt->execute([$fullname, $email, $hashed_password]); // This runs the SQL and fills the ?.

    header("Location: ../FRONT-END/connexion.html");
    exit;
}