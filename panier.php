
<?php
session_start();

// Connectez-vous à la base de données
$db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004');
$db_found = mysqli_select_db($db_handle, 'projets2');

// Vérifiez si la connexion à la base de données est réussie
if (!$db_handle || !$db_found) {
    die("Erreur de connexion à la base de données.");
}

// Vider le panier
if (isset($_POST['vider_panier'])) {
    $id_utilisateur = $_SESSION['id_utilisateur'];

    // Mettre à jour la colonne id_utilisateur dans la table article
    $sql_update_articles = "UPDATE article SET id_utilisateur = NULL WHERE id_utilisateur = '$id_utilisateur'";
    mysqli_query($db_handle, $sql_update_articles);

    // Vider le panier en session
    unset($_SESSION['panier']);
}

// Acheter les articles de la catégorie "Vente Immédiate"
if (isset($_POST['acheter_immediat'])) {
    $_SESSION['articles_a_acheter'] = array_filter($_SESSION['panier'], function($article) {
        return $article["type_vente"] == 'immediat';
    });
    header("Location: paiement.php");
    exit();
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
                $articles_immediat = [];
                $articles_enchere = [];
                $articles_vendeur_client = [];

                foreach ($_SESSION['panier'] as $article) {
                    if (isset($article["type_vente"])) {
                        if ($article["type_vente"] == 'immediat') {
                            $articles_immediat[] = $article;
                        } elseif ($article["type_vente"] == 'enchere') {
                            $articles_enchere[] = $article;
                        } elseif ($article["type_vente"] == 'vendeur_client') {
                            $articles_vendeur_client[] = $article;
                        }
                    }
                }

                if (!empty($articles_immediat)) {
                    echo '<h3>Vente Immédiate</h3>';
                    echo '<div class="items">';
                    foreach ($articles_immediat as $article) {
                        echo '<div class="item">';
                        echo '<img src="'.$article["image"].'" alt="'.$article["nom"].'">';
                        echo '<p>'.$article["nom"].'</p>';
                        echo '<p>Prix : '.$article["prix"].' €</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '<form method="post" action="Panier.php">';
                    echo '<button type="submit" name="acheter_immediat">Acheter tous les articles de Vente Immédiate</button>';
                    echo '</form>';
                }

                if (!empty($articles_enchere)) {
                    echo '<h3>Enchère</h3>';
                    echo '<div class="items">';
                    foreach ($articles_enchere as $article) {
                        echo '<div class="item">';
                        echo '<img src="'.$article["image"].'" alt="'.$article["nom"].'">';
                        echo '<p>'.$article["nom"].'</p>';
                        echo '<p>Prix : '.$article["prix"].' €</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                }

                if (!empty($articles_vendeur_client)) {
                    echo '<h3>Transaction Vendeur-Client</h3>';
                    echo '<div class="items">';
                    foreach ($articles_vendeur_client as $article) {
                        echo '<div class="item">';
                        echo '<img src="'.$article["image"].'" alt="'.$article["nom"].'">';
                        echo '<p>'.$article["nom"].'</p>';
                        echo '<p>Prix : '.$article["prix"].' €</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                }

                echo '<form method="post" action="Panier.php">';
                echo '<button type="submit" name="vider_panier">Vider la totalité du panier</button>';
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
