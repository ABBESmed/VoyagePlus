<?php

session_start();

require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $user_id = null;

    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
    }

    $sql = "INSERT INTO contacts (name, email, subject, message, user_id) VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$name, $email, $subject, $message, $user_id]);

    header("Location: ../FRONT-END/contact.php");
    exit;
}