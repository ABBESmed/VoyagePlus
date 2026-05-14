<?php
session_start();
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["fullname"] = $user["fullname"];

        header("Location: ../FRONT-END/index.php");
        exit;
    } else {
        echo "Email or password incorrect.";
    }
}