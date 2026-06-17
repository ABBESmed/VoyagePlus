<?php
session_start();
require_once "../BACK-END/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.html");
    exit;
}

// Get search data safely
$destination = $_GET["destination"] ?? "";
$activity = $_GET["activity"] ?? "";
$departure_date = $_GET["departure_date"] ?? "";
$departure_city = $_GET["departure_city"] ?? "";
$arrival_city = $_GET["arrival_city"] ?? "";

// If something is missing, go back to search page
if (empty($destination) || empty($departure_date) || empty($departure_city) || empty($arrival_city)) {
    header("Location: reservation_search.php");
    exit;
}

// Flight prices because price is not stored in flights table anymore
$flight_prices = [
    1 => 120,
    2 => 220,
    3 => 650,
    4 => 150
];

// Get matching flights
$sql = "SELECT * FROM flights 
        WHERE departure_city = ? 
        AND arrival_city = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$departure_city, $arrival_city]);

$flights = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choisir un vol - VoyagePlus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="reservation-page">
    <h2>Choisir un vol</h2>

    <?php if (count($flights) > 0): ?>

        <?php foreach ($flights as $flight): ?>

            <?php
            $departure_time = date("H:i", strtotime($flight["departure_time"]));
            $arrival_time = date("H:i", strtotime($flight["arrival_time"]));
            ?>

            <div class="flight-card">
                <h3><?php echo htmlspecialchars($flight["flight_number"]); ?></h3>

                <p>
                    <?php echo htmlspecialchars($flight["departure_city"]); ?> 
                    →
                    <?php echo htmlspecialchars($flight["arrival_city"]); ?>
                </p>

                <p>Départ : <?php echo $departure_time; ?></p>
                <p>Arrivée : <?php echo $arrival_time; ?></p>
                <p>Prix : <?php echo $flight_prices[$flight["id"]] ?? 0; ?> €</p>

                <br>

                <a 
                    class="btn-register" 
                    href="reservation.php?flight_id=<?php echo urlencode($flight["id"]); ?>&destination=<?php echo urlencode($destination); ?>&activity=<?php echo urlencode($activity); ?>&departure_date=<?php echo urlencode($departure_date); ?>"
                >
                    Choisir ce vol
                </a>
            </div>
        <?php endforeach; ?>

    <?php else: ?>

        <p>Aucun vol trouvé pour cette recherche.</p>
        <a href="reservation_search.php">Retour</a>

    <?php endif; ?>
</section>

</body>
</html>