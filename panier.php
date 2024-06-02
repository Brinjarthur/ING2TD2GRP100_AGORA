<?php
session_start();

// Vider le panier
if (isset($_POST['vider_panier'])) {
    unset($_SESSION['panier']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia - Panier</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <h1>Votre Panier</h1>
            <img src="logo.png" alt="Logo Agora">
        </header>
        <nav class="navigation">
            <ul>
                <li><a href="Accueil.html">Accueil</a></li>
                <li><a href="ToutParcourir.php">Tout Parcourir</a></li>
                <li><a href="Notifications.html">Notifications</a></li>
                <li><a href="Panier.php" class="active">Panier</a></li>
                <li><a href="VotreCompte.html">Votre Compte</a></li>
            </ul>
        </nav>
        <section class="section">
            <h2>Articles dans votre panier</h2>
            <?php
            if (!empty($_SESSION['panier'])) {
                echo '<div class="items">';
                foreach ($_SESSION['panier'] as $article) {
                    echo '<div class="item">';
                    echo '<img src="'.$article["image"].'" alt="'.$article["nom"].'">';
                    echo '<p>'.$article["nom"].'</p>';
                    echo '<p>Prix : '.$article["prix"].' €</p>';
                    echo '</div>';
                }
                echo '</div>';
                echo '<form method="post" action="Panier.php">';
                echo '<button type="submit" name="vider_panier">Vider le panier</button>';
                echo '</form>';
            } else {
                echo "<p>Votre panier est vide.</p>";
            }
            ?>
        </section>
        <footer class="footer">
            <p>© 2024 Agora Francia. Tous droits réservés.</p>
            <p>Contactez-nous: email@agorafrancia.com | +33 1 23 45 67 89</p>
        </footer>
    </div>
</body>
</html>
