<?php
session_start();
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - VoyagePlus</title>
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
        <h2>Réserver un voyage</h2>
        <p>Remplissez le formulaire pour préparer votre prochaine aventure.</p>
    </section>

    <!-- Formulaire de réservation -->
    <section class="reservation-page">
        <form class="reservation-form" action="../BACK-END/reservation_process.php" method="POST">

            <label for="nom">Nom complet</label>
            <input 
                type="text" 
                id="nom" 
                name="fullname" 
                placeholder="Votre nom complet"
                value="<?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : ''; ?>"
            >
            <label for="birth-date">Date de naissance</label>
            <input type="date" id="birth-date" name="birth_date">

            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Votre email"
                value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>"
            >

            <label for="destination">Destination</label>
            <select id="destination" name="destination">
                <option value="">Choisir une destination</option>
                <option value="paris">Paris</option>
                <option value="marrakech">Marrakech</option>
                <option value="bali">Bali</option>
                <option value="rome">Rome</option>
            </select>

            <label for="flight">Vol</label>
            <select id="flight" name="flight_id">
                <option value="">Choisir un vol</option>
                <option value="1">VP101 - Marseille → Paris - 120€</option>
                <option value="2">VP102 - Marseille → Marrakech - 220€</option>
                <option value="3">VP103 - Marseille → Bali - 650€</option>
                <option value="4">VP104 - Marseille → Rome - 150€</option>
            </select>

            <label for="activite">Activité optionnelle</label>
            <select id="activite" name="activity">
                <option value="">Aucune activité</option>
                <option value="randonnee">Randonnée</option>
                <option value="plongee">Plongée</option>
                <option value="safari">Safari</option>
                <option value="visite-guidee">Visite guidée</option>
            </select>

            <label for="date-depart">Date de départ</label>
            <input type="date" id="date-depart" name="departure_date">

            <label for="date-retour">Date de retour</label>
            <input type="date" id="date-retour" name="return_date">

            <label for="personnes">Nombre de personnes</label>
            <input type="number" id="personnes" name="number_of_people" placeholder="Ex: 2" min="1" required>

            <div id="passengers-container"></div>

            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Votre message ou demande spéciale"></textarea>

            <button type="submit">Envoyer la réservation</button>

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