<?php
session_start();
require_once "../BACK-END/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: connexion.html");
    exit;
}

if (
    !isset($_GET["flight_id"]) ||
    !isset($_GET["activity"]) ||
    !isset($_GET["departure_date"])
) {
    header("Location: reservation_search.php");
    exit;
}

$flight_id = (int) $_GET["flight_id"];
$activity = $_GET["activity"];
$departure_date = $_GET["departure_date"];

// Flight prices because price is not stored in flights table anymore
$flight_prices = [
    1 => 120,
    2 => 220,
    3 => 650,
    4 => 150
];

$flight_price = $flight_prices[$flight_id] ?? 0;

if ($flight_price <= 0) {
    header("Location: reservation_search.php");
    exit;
}

// Get selected flight + airport names + destination city
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

    WHERE f.id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$flight_id]);
$flight = $stmt->fetch();

if (!$flight) {
    header("Location: reservation_search.php");
    exit;
}

$destination = $flight["destination"];

// Show only time, not full database date
$departure_time = date("H:i", strtotime($flight["departure_time"]));
$arrival_time = date("H:i", strtotime($flight["arrival_time"]));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser la réservation - VoyagePlus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- header -->
    <header class="header">
        <h1 class="logo">VoyagePlus</h1>

        <button class="burger" id="burger">☰</button>

        <nav class="nav" id="nav">
            <a href="index.php">Accueil</a>
            <a href="voyage.php">Voyage</a>
            <a href="activites.php">Activités</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="buttons" id="buttons">
            <?php if (isset($_SESSION["fullname"])): ?>

                <div class="profile-circle">
                    <?php echo strtoupper($_SESSION["fullname"][0]); ?>
                </div>

                <a href="../BACK-END/logout.php" class="btn-login">Déconnexion</a>

            <?php else: ?>

                <a href="connexion.html" class="btn-login">Connexion</a>
                <a href="inscription.html" class="btn-register">Inscription</a>

            <?php endif; ?>
        </div>
    </header>

    <!-- Titre de page -->
    <section class="page-title">
        <h2>Finaliser votre réservation</h2>
        <p>Vérifiez le vol choisi puis complétez les informations des passagers.</p>
    </section>

    <!-- Résumé du vol choisi -->
    <section class="reservation-page">
        <div class="reservation-form">

            <h3>Vol sélectionné</h3>

            <p><strong>Destination :</strong> <?php echo htmlspecialchars($destination); ?></p>

            <p>
                <strong>Activité :</strong>
                <?php echo $activity ? htmlspecialchars($activity) : "Aucune activité"; ?>
            </p>

            <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($departure_date); ?></p>

            <p><strong>Vol :</strong> <?php echo htmlspecialchars($flight["flight_number"]); ?></p>

            <p>
                <strong>Trajet :</strong>
                <?php echo htmlspecialchars($flight["departure_city"]); ?>
                -
                <?php echo htmlspecialchars($flight["departure_airport_name"]); ?>
                (<?php echo htmlspecialchars($flight["departure_airport_iata"]); ?>)
                →
                <?php echo htmlspecialchars($destination); ?>
                -
                <?php echo htmlspecialchars($flight["arrival_airport_name"]); ?>
                (<?php echo htmlspecialchars($flight["arrival_airport_iata"]); ?>)
            </p>

            <p><strong>Heure de départ :</strong> <?php echo $departure_time; ?></p>
            <p><strong>Heure d'arrivée :</strong> <?php echo $arrival_time; ?></p>
            <p><strong>Prix du vol :</strong> <?php echo $flight_price; ?> €</p>

        </div>
    </section>

    <!-- Formulaire final de réservation -->
    <section class="reservation-page">
        <form class="reservation-form" action="../BACK-END/reservation_process.php" method="POST">

            <!-- Infos cachées venant des étapes précédentes -->
            <input type="hidden" name="flight_id" value="<?php echo htmlspecialchars($flight_id); ?>">
            <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination); ?>">
            <input type="hidden" name="activity" value="<?php echo htmlspecialchars($activity); ?>">
            <input type="hidden" name="departure_date" value="<?php echo htmlspecialchars($departure_date); ?>">

            <!-- Infos du compte connecté -->
            <label for="nom">Nom complet</label>
            <input 
                type="text" 
                id="nom" 
                name="fullname" 
                value="<?php echo htmlspecialchars($_SESSION['fullname']); ?>"
                readonly
            >

            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?php echo htmlspecialchars($_SESSION['email']); ?>"
                readonly
            >

            <p>
                <strong>Important :</strong>
                Vous êtes automatiquement ajouté comme passager numéro 1.
            </p>

            <label for="personnes">Nombre de personnes</label>
            <input 
                type="number" 
                id="personnes" 
                name="number_of_people" 
                placeholder="Ex: 3" 
                min="1" 
                max="4"
                required
            >

            <!-- Ici JS ajoute seulement les autres passagers -->
            <div id="passengers-container"></div>

            <label for="message">Message</label>
            <textarea 
                id="message" 
                name="message" 
                placeholder="Votre message ou demande spéciale"
            ></textarea>

            <button type="submit">Réserver</button>

        </form>
    </section>

    <!-- footer -->
    <footer class="footer">
        <div class="footer-content">
            <h2>VoyagePlus</h2>
            <p>Découvrez le monde avec nous et réservez vos plus beaux voyages.</p>

            <div class="footer-links">
                <a href="index.php">Accueil</a>
                <a href="voyage.php">Voyage</a>
                <a href="activites.php">Activités</a>
                <a href="contact.php">Contact</a>
            </div>

            <p class="copyright">&copy; 2026 VoyagePlus. Tous droits réservés.</p>
        </div>
    </footer>

    <button id="scrollTopBtn" class="scroll-top-btn">↑</button>

    <script src="script.js"></script>
</body>
</html>