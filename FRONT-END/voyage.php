<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos voyages - VoyagePlus</title>
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

    

    <!--Voyages Section-->
    <section class="page-title">
        <h2>Nos voyages</h2>
        <p>Découvrez nos meilleures destinations et choisissez votre prochaine aventure.</p>
    </section>

    <section class="voyages-page">
        <div class="cards">
            <div class="card">
                <img src="images/paris.jpg" alt="Paris">
                <h3>Paris</h3>
                <p>Découvrez la ville lumière, ses monuments et sa gastronomie.</p>
                <p class="price">À partir de 299€</p>
                <a href="reservation.php">Réserver</a>
            </div>
            <div class="card">
                <img src="images/marrakech.jpg" alt="Marrakech">
                <h3>Marrakech</h3>
                <p>Profitez du soleil, des souks et d’une culture unique.</p>
                <p class="price">À partir de 399€</p>
                <a href="reservation.php">Réserver</a>
            </div>
            <div class="card">
                <img src="images/bali.jpg" alt="Bali">
                <h3>Bali</h3>
                <p>Explorez les plages, les rizières et les paysages tropicaux.</p>
                <p class="price">À partir de 899€</p>
                <a href="reservation.php">Réserver</a>
            </div>
            <div class="card">
                <img src="images/rome.jpg" alt="Rome">
                <h3>Rome</h3>
                <p>Visitez les monuments historiques et profitez de la cuisine italienne.</p>
                <p class="price">À partir de 349€</p>
                <a href="reservation.php">Réserver</a>
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