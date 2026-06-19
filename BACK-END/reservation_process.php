<?php

session_start();

require_once "database.php";

// Check if user is connected
if (!isset($_SESSION["user_id"])) {
    header("Location: ../FRONT-END/connexion.html");
    exit;
}

// If someone opens this file directly without POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../FRONT-END/reservation_search.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$connected_user_fullname = $_SESSION["fullname"] ?? "";

// Get form data safely
$flight_id = isset($_POST["flight_id"]) ? (int) $_POST["flight_id"] : 0;
$activity = $_POST["activity"] ?? "";
$departure_date = $_POST["departure_date"] ?? "";
$number_of_people = isset($_POST["number_of_people"]) ? (int) $_POST["number_of_people"] : 0;
$message = $_POST["message"] ?? "";

// Extra passengers only
$passenger_fullnames = $_POST["passenger_fullname"] ?? [];
$passenger_birth_dates = $_POST["passenger_birth_date"] ?? [];

// Basic validation
if (empty($connected_user_fullname)) {
    die("Error: connected user name is missing.");
}

if ($flight_id <= 0) {
    die("Error: please choose a flight.");
}

if (empty($departure_date)) {
    die("Error: departure date is missing.");
}

if ($number_of_people < 1 || $number_of_people > 4) {
    die("Error: number of people must be between 1 and 4.");
}

// Connected user is already passenger number 1
$extra_passengers_count = $number_of_people - 1;

if ($extra_passengers_count !== count($passenger_fullnames)) {
    die("Error: number of passengers does not match number of people.");
}

if ($extra_passengers_count !== count($passenger_birth_dates)) {
    die("Error: passenger birth dates are missing.");
}

// Flight prices because price is not stored in flights table anymore
$flight_prices = [
    1 => 120,
    2 => 220,
    3 => 650,
    4 => 150
];

$flight_price = $flight_prices[$flight_id] ?? 0;

if ($flight_price <= 0) {
    die("Error: flight price not found.");
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Get selected flight + destination city from arrival airport
    $sql_flight = "
        SELECT 
            f.id,
            arr.ville AS destination
        FROM flights f
        INNER JOIN airport arr
            ON f.arrival_airport = arr.id
        WHERE f.id = ?
    ";

    $stmt_flight = $pdo->prepare($sql_flight);
    $stmt_flight->execute([$flight_id]);
    $flight = $stmt_flight->fetch();

    if (!$flight) {
        throw new Exception("Selected flight does not exist.");
    }

    // Destination comes from airport.ville
    $destination = $flight["destination"];

    if (empty($destination)) {
        throw new Exception("Destination not found from airport table.");
    }

    // Get activity price from activities table
    if (!empty($activity)) {
        $sql_activity = "SELECT price FROM activities WHERE slug = ?";
        $stmt_activity = $pdo->prepare($sql_activity);
        $stmt_activity->execute([$activity]);
        $activity_data = $stmt_activity->fetch();

        if (!$activity_data) {
            throw new Exception("Selected activity does not exist.");
        }

        $activity_price = $activity_data["price"];
    } else {
        $activity_price = 0;
    }

    $passenger_price = $flight_price + $activity_price;

    // Get connected user birth date from users table
    $sql_user = "SELECT birth_date FROM users WHERE id = ?";
    $stmt_user = $pdo->prepare($sql_user);
    $stmt_user->execute([$user_id]);
    $user = $stmt_user->fetch();

    $connected_user_birth_date = $user["birth_date"] ?? null;

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

    // Prepare passenger insert
    $sql_passenger = "INSERT INTO passengers 
                      (reservation_id, fullname, birth_date, price)
                      VALUES (?, ?, ?, ?)";

    $stmt_passenger = $pdo->prepare($sql_passenger);

    // 1. Insert connected user as passenger number 1
    $stmt_passenger->execute([
        $reservation_id,
        $connected_user_fullname,
        $connected_user_birth_date,
        $passenger_price
    ]);

    // 2. Insert extra passengers
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

?>