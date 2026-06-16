<?php

session_start();

require_once "database.php";

// Check if user is connected
if (!isset($_SESSION["user_id"])) {
    header("Location: ../FRONT-END/connexion.html");
    exit;
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION["user_id"];

    // Get form data safely
    $flight_id = isset($_POST["flight_id"]) ? (int) $_POST["flight_id"] : 0;
    $activity = $_POST["activity"] ?? "";
    $number_of_people = isset($_POST["number_of_people"]) ? (int) $_POST["number_of_people"] : 0;
    $message = $_POST["message"] ?? "";

    $passenger_fullnames = $_POST["passenger_fullname"] ?? [];
    $passenger_birth_dates = $_POST["passenger_birth_date"] ?? [];

    // Basic validation
    if ($flight_id <= 0) {
        die("Error: please choose a flight.");
    }

    if ($number_of_people <= 0) {
        die("Error: please choose the number of people.");
    }

    if ($number_of_people !== count($passenger_fullnames)) {
        die("Error: number of passengers does not match number of people.");
    }

    if ($number_of_people !== count($passenger_birth_dates)) {
        die("Error: passenger birth dates are missing.");
    }

    // Activity prices
    $activity_prices = [
        "" => 0,
        "randonnee" => 49,
        "plongee" => 79,
        "safari" => 149,
        "visite-guidee" => 39
    ];

    // Flight prices
    // Price is not stored in the flights table anymore.
    // We calculate it here and store the final price in passengers.price.
    $flight_prices = [
        1 => 120, // VP101 Marseille → Paris
        2 => 220, // VP102 Marseille → Marrakech
        3 => 650, // VP103 Marseille → Bali
        4 => 150  // VP104 Marseille → Rome
    ];

    $activity_price = $activity_prices[$activity] ?? 0;
    $flight_price = $flight_prices[$flight_id] ?? 0;

    $passenger_price = $flight_price + $activity_price;

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert reservation
        $sql = "INSERT INTO reservations 
                (user_id, flight_id, activity, number_of_people, message)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $user_id,
            $flight_id,
            $activity,
            $number_of_people,
            $message
        ]);

        $reservation_id = $pdo->lastInsertId();

        // Insert passengers
        $sql_passenger = "INSERT INTO passengers 
                          (reservation_id, fullname, birth_date, price)
                          VALUES (?, ?, ?, ?)";

        $stmt_passenger = $pdo->prepare($sql_passenger);

        for ($i = 0; $i < $number_of_people; $i++) {

            $fullname = trim($passenger_fullnames[$i]);
            $birth_date = $passenger_birth_dates[$i];

            if (empty($fullname) || empty($birth_date)) {
                throw new Exception("Passenger information is missing.");
            }

            $stmt_passenger->execute([
                $reservation_id,
                $fullname,
                $birth_date,
                $passenger_price
            ]);
        }

        // Confirm transaction
        $pdo->commit();

        header("Location: ../FRONT-END/reservation.php");
        exit;

    } catch (Exception $e) {
        // Cancel transaction if something goes wrong
        $pdo->rollBack();

        die("Reservation error: " . $e->getMessage());
    }
}