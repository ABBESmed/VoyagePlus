<?php

session_start();

require_once "database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../FRONT-END/connexion.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $sql = "INSERT INTO contacts (user_id, subject, message)
            VALUES (?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $user_id,
        $subject,
        $message
    ]);

    header("Location: ../FRONT-END/contact.php");
    exit;
}