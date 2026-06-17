<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.html");
    exit;
}
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

        <label for="destination">Destination</label>
        <select id="destination" name="destination" required>
            <option value="">Choisir une destination</option>
            <option value="paris">Paris</option>
            <option value="marrakech">Marrakech</option>
            <option value="bali">Bali</option>
            <option value="rome">Rome</option>
        </select>

        <label for="activity">Activité optionnelle</label>
        <select id="activity" name="activity">
            <option value="">Aucune activité</option>
            <option value="randonnee">Randonnée</option>
            <option value="plongee">Plongée</option>
            <option value="safari">Safari</option>
            <option value="visite-guidee">Visite guidée</option>
        </select>

        <label for="departure_date">Date de départ</label>
        <input type="date" id="departure_date" name="departure_date" required>

        <label for="departure_city">Ville de départ</label>
        <input type="text" id="departure_city" name="departure_city" placeholder="Ex: Marseille" required>

        <label for="arrival_city">Ville d'arrivée</label>
        <input type="text" id="arrival_city" name="arrival_city" placeholder="Ex: Paris" required>

        <button type="submit">Rechercher un vol</button>

    </form>
</section>

</body>
</html>