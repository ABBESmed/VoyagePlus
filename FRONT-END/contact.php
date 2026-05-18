<?php
session_start();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - VoyagePlus</title>
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

    

    <!--Contact Section-->

    <section class="page-title">
        <h2>Contactez-nous</h2>
        <p>Une question ? Envoyez-nous un message, notre équipe vous répondra rapidement.</p>
    </section>

    <section class="contact-page">
        <form class="contact-form" action="../BACK-END/contact_process.php" method="POST">
            
            <label for="sujet">Sujet</label>
            <input type="text" id="sujet" name="subject" placeholder="Sujet">
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Votre message"></textarea>
            <button type="submit">Envoyer</button>
        </form>
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