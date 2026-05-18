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
    $flight_id = $_POST["flight_id"];
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

    // Get flight price from flights table
    $sql_flight = "SELECT price FROM flights WHERE id = ?";

    $stmt_flight = $pdo->prepare($sql_flight);

    $stmt_flight->execute([$flight_id]);

    $flight = $stmt_flight->fetch();

    $flight_price = $flight["price"];

    // Calculate total price
    $total_price = ($destination_price + $activity_price + $flight_price) * $persons;

    $sql = "INSERT INTO reservations 
            (user_id, fullname, email, destination, flight_id, activity, departure_date, return_date, persons, message, total_price)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $user_id,
        $fullname,
        $email,
        $destination,
        $flight_id,
        $activity,
        $departure_date,
        $return_date,
        $persons,
        $message,
        $total_price
    ]);

    $reservation_id = $pdo->lastInsertId();

    $passenger_fullnames = $_POST["passenger_fullname"];
    $passenger_birth_dates = $_POST["passenger_birth_date"];

    $sql_passenger = "INSERT INTO passengers (reservation_id, fullname, birth_date)
                    VALUES (?, ?, ?)";

    $stmt_passenger = $pdo->prepare($sql_passenger);

    for ($i = 0; $i < count($passenger_fullnames); $i++) {
        $stmt_passenger->execute([
            $reservation_id,
            $passenger_fullnames[$i],
            $passenger_birth_dates[$i]
        ]);
    }

    header("Location: ../FRONT-END/reservation.php");
    exit;
}