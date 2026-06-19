<?php
session_start();
require_once "../BACK-END/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.html");
    exit;
}

// Get search data safely
$activity = $_GET["activity"] ?? "";
$departure_date = $_GET["departure_date"] ?? "";
$departure_airport = $_GET["departure_airport"] ?? "";
$arrival_airport = $_GET["arrival_airport"] ?? "";

// If something is missing, go back to search page
if (
    empty($departure_date) ||
    empty($departure_airport) ||
    empty($arrival_airport)
) {
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

// Get matching flights + airport names + destination city
$sql = "
    SELECT
        f.id,
        f.flight_number,
        f.departure_time,
        f.arrival_time,

        dep.nom AS departure_airport_name,
        dep.IATA AS departure_airport_iata,
        dep.ville AS departure_city,

        arr.nom AS arrival_airport_name,
        arr.IATA AS arrival_airport_iata,
        arr.ville AS destination

    FROM flights f

    INNER JOIN airport dep
        ON f.departure_airport = dep.id

    INNER JOIN airport arr
        ON f.arrival_airport = arr.id

    WHERE f.departure_airport = ?
    AND f.arrival_airport = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$departure_airport, $arrival_airport]);

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

        <div class="search-summary">
            <p><strong>Destination :</strong> <?php echo htmlspecialchars($flights[0]["destination"]); ?></p>
            <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($departure_date); ?></p>

            <p>
                <strong>Aéroport de départ :</strong>
                <?php echo htmlspecialchars($flights[0]["departure_city"]); ?> -
                <?php echo htmlspecialchars($flights[0]["departure_airport_name"]); ?>
                (<?php echo htmlspecialchars($flights[0]["departure_airport_iata"]); ?>)
            </p>

            <p>
                <strong>Aéroport d'arrivée :</strong>
                <?php echo htmlspecialchars($flights[0]["destination"]); ?> -
                <?php echo htmlspecialchars($flights[0]["arrival_airport_name"]); ?>
                (<?php echo htmlspecialchars($flights[0]["arrival_airport_iata"]); ?>)
            </p>
        </div>

        <?php foreach ($flights as $flight): ?>

            <?php
            $departure_time = date("H:i", strtotime($flight["departure_time"]));
            $arrival_time = date("H:i", strtotime($flight["arrival_time"]));
            $price = $flight_prices[$flight["id"]] ?? 0;
            ?>

            <div class="flight-card">
                <h3><?php echo htmlspecialchars($flight["flight_number"]); ?></h3>

                <p>
                    <?php echo htmlspecialchars($flight["departure_city"]); ?>
                    (<?php echo htmlspecialchars($flight["departure_airport_iata"]); ?>)
                    →
                    <?php echo htmlspecialchars($flight["destination"]); ?>
                    (<?php echo htmlspecialchars($flight["arrival_airport_iata"]); ?>)
                </p>

                <p><strong>Départ :</strong> <?php echo $departure_time; ?></p>
                <p><strong>Arrivée :</strong> <?php echo $arrival_time; ?></p>
                <p><strong>Prix :</strong> <?php echo $price; ?> €</p>

                <br>

                <a 
                    class="btn-register" 
                    href="reservation.php?flight_id=<?php echo urlencode($flight["id"]); ?>&activity=<?php echo urlencode($activity); ?>&departure_date=<?php echo urlencode($departure_date); ?>"
                >
                    Choisir ce vol
                </a>
            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="search-summary">
            <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($departure_date); ?></p>
        </div>

        <p>Aucun vol trouvé pour cette recherche.</p>
        <a href="reservation_search.php">Retour</a>

    <?php endif; ?>

</section>

</body>
</html>