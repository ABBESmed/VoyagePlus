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
    $destination = $_POST["destination"] ?? "";
    $activity = $_POST["activity"] ?? "";
    $departure_date = $_POST["departure_date"] ?? "";
    $number_of_people = isset($_POST["number_of_people"]) ? (int) $_POST["number_of_people"] : 0;
    $message = $_POST["message"] ?? "";

    // Other passengers only
    $passenger_fullnames = $_POST["passenger_fullname"] ?? [];
    $passenger_birth_dates = $_POST["passenger_birth_date"] ?? [];

    // Basic validation
    if ($flight_id <= 0) {
        die("Error: please choose a flight.");
    }

    if (empty($destination)) {
        die("Error: destination is missing.");
    }

    if (empty($departure_date)) {
        die("Error: departure date is missing.");
    }

    if ($number_of_people <= 0) {
        die("Error: please choose the number of people.");
    }

    // Because the connected user is already included
    $extra_passengers_count = $number_of_people - 1;

    if ($extra_passengers_count !== count($passenger_fullnames)) {
        die("Error: number of passengers does not match number of people.");
    }

    if ($extra_passengers_count !== count($passenger_birth_dates)) {
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
    $flight_prices = [
        1 => 120,
        2 => 220,
        3 => 650,
        4 => 150
    ];

    $activity_price = $activity_prices[$activity] ?? 0;
    $flight_price = $flight_prices[$flight_id] ?? 0;

    $passenger_price = $flight_price + $activity_price;

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert reservation
        $sql = "INSERT INTO reservations 
                (user_id, flight_id, destination, activity, departure_date, number_of_people, message)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $user_id,
            $flight_id,
            $destination,
            $activity,
            $departure_date,
            $number_of_people,
            $message
        ]);

        $reservation_id = $pdo->lastInsertId();

        // Insert only other passengers
        $sql_passenger = "INSERT INTO passengers 
                          (reservation_id, fullname, birth_date, price)
                          VALUES (?, ?, ?, ?)";

        $stmt_passenger = $pdo->prepare($sql_passenger);

        for ($i = 0; $i < $extra_passengers_count; $i++) {

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

        // Redirect to success page
        header("Location: ../FRONT-END/reservation_success.php");
        exit;

    } catch (Exception $e) {
        // Cancel transaction if something goes wrong
        $pdo->rollBack();

        die("Reservation error: " . $e->getMessage());
    }

} else {
    // If someone opens this file directly without POST
    header("Location: ../FRONT-END/reservation_search.php");
    exit;
}

?>