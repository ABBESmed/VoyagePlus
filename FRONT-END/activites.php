<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos activités - VoyagePlus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!--header-->
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

    
    <!--Activités Section-->
    <section class="page-title">
        <h2>Nos activités</h2>
        <p>Découvrez les activités passionnantes que nous proposons pour rendre votre voyage inoubliable.</p>
    </section>

    <section class="activites-page">
        <div class="cards">

            <div class="card">
                <img src="images/randonnee.jpg" alt="Randonnée">
                <h3>Randonnée</h3>
                <p>Explorez des montagnes, des forêts et des paysages naturels magnifiques.</p>
                <p class="price">À partir de 49€</p>
                <a href="reservation.html">Réserver</a>
            </div>
            <div class="card">
                <img src="images/plongee.jpg" alt="Plongée">
                <h3>Plongée</h3>
                <p>Découvrez les fonds marins et profitez d’une aventure sous-marine.</p>
                <p class="price">À partir de 79€</p>
                <a href="reservation.html">Réserver</a>
            </div>
            <div class="card">
                <img src="images/safari.jpg" alt="Safari">
                <h3>Safari</h3>
                <p>Vivez une expérience unique au cœur de la nature et observez les animaux.</p>
                <p class="price">À partir de 149€</p>
                <a href="reservation.html">Réserver</a>
            </div>
            <div class="card">
                <img src="images/visite.jpg" alt="Visite guidée">
                <h3>Visite guidée</h3>
                <p>Découvrez les villes, les monuments et la culture locale avec un guide.</p>
                <p class="price">À partir de 39€</p>
                <a href="reservation.html">Réserver</a>
            </div>
        </div>
    </section>

    <!--footer-->
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