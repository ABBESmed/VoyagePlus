<?php
session_start();
require_once "../BACK-END/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.html");
    exit;
}

// Get activities from database
$sql_activities = "SELECT id, name, slug, price FROM activities ORDER BY name";
$stmt_activities = $pdo->prepare($sql_activities);
$stmt_activities->execute();
$activities = $stmt_activities->fetchAll();

// Get departure airports from database
// For now, departure is only Marseille
$sql_departure_airports = "SELECT id, nom, IATA, ville FROM airport WHERE id = 1";
$stmt_departure_airports = $pdo->prepare($sql_departure_airports);
$stmt_departure_airports->execute();
$departure_airports = $stmt_departure_airports->fetchAll();

// Get arrival airports from database
// Exclude Marseille because Marseille is departure
$sql_arrival_airports = "SELECT id, nom, IATA, ville FROM airport WHERE id != 1 ORDER BY ville";
$stmt_arrival_airports = $pdo->prepare($sql_arrival_airports);
$stmt_arrival_airports->execute();
$arrival_airports = $stmt_arrival_airports->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche réservation - VoyagePlus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="reservation-page">
    <form class="reservation-form" action="select_flight.php" method="GET">

        <h2>Préparer votre voyage</h2>

        <label for="activity">Activité optionnelle</label>
        <select id="activity" name="activity">
            <option value="">Aucune activité</option>

            <?php foreach ($activities as $activity): ?>
                <option value="<?php echo htmlspecialchars($activity["slug"]); ?>">
                    <?php echo htmlspecialchars($activity["name"]); ?>
                    - <?php echo htmlspecialchars($activity["price"]); ?> €
                </option>
            <?php endforeach; ?>

        </select>

        <label for="departure_date">Date de départ</label>
        <input type="date" id="departure_date" name="departure_date" required>

        <label for="departure_airport">Aéroport de départ</label>
        <select id="departure_airport" name="departure_airport" required>
            <option value="">Choisir un aéroport de départ</option>

            <?php foreach ($departure_airports as $airport): ?>
                <option value="<?php echo htmlspecialchars($airport["id"]); ?>">
                    <?php echo htmlspecialchars($airport["ville"]); ?>
                    -
                    <?php echo htmlspecialchars($airport["nom"]); ?>
                    (<?php echo htmlspecialchars($airport["IATA"]); ?>)
                </option>
            <?php endforeach; ?>

        </select>

        <label for="arrival_airport">Destination / Aéroport d'arrivée</label>
        <select id="arrival_airport" name="arrival_airport" required>
            <option value="">Choisir une destination</option>

            <?php foreach ($arrival_airports as $airport): ?>
                <option value="<?php echo htmlspecialchars($airport["id"]); ?>">
                    <?php echo htmlspecialchars($airport["ville"]); ?>
                    -
                    <?php echo htmlspecialchars($airport["nom"]); ?>
                    (<?php echo htmlspecialchars($airport["IATA"]); ?>)
                </option>
            <?php endforeach; ?>

        </select>

        <button type="submit">Rechercher un vol</button>

    </form>
</section>

</body>
</html>