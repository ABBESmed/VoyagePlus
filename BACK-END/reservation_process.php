<?php

session_start();

require_once "database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../FRONT-END/connexion.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];

    $fullname = $_POST["fullname"];
    $birth_date = $_POST["birth_date"];
    $email = $_POST["email"];
    $destination = $_POST["destination"];
    $activity = $_POST["activity"];
    $departure_date = $_POST["departure_date"];
    $return_date = $_POST["return_date"];
    $persons = $_POST["persons"];
    $message = $_POST["message"];

    // Prices for destinations
    $destination_prices = [
        "paris" => 299,
        "marrakech" => 399,
        "bali" => 899,
        "rome" => 349
    ];

    // Prices for activities
    $activity_prices = [
        "" => 0,
        "randonnee" => 49,
        "plongee" => 79,
        "safari" => 149,
        "visite-guidee" => 39
    ];

    // Get destination price
    $destination_price = $destination_prices[$destination];

    // Get activity price
    $activity_price = $activity_prices[$activity];

    // Calculate total price
    $total_price = ($destination_price + $activity_price) * $persons;

    $sql = "INSERT INTO reservations 
            (user_id, fullname, email, destination, activity, departure_date, return_date, persons, message, total_price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $user_id,
        $fullname,
        $email,
        $destination,
        $activity,
        $departure_date,
        $return_date,
        $persons,
        $message,
        $total_price
    ]);

    $reservation_id = $pdo->lastInsertId();

    $sql_passenger = "INSERT INTO passengers (reservation_id, fullname, birth_date)
                  VALUES (?, ?, ?)";

    $stmt_passenger = $pdo->prepare($sql_passenger);

    $stmt_passenger->execute([
        $reservation_id,
        $fullname,
        $birth_date
    ]);

    header("Location: ../FRONT-END/reservation.php");
    exit;
}