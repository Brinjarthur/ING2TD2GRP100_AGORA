<?php
session_start();

// Vider le panier
if (isset($_POST['vider_panier'])) {
    unset($_SESSION['panier']);
}

// Connectez-vous à votre BDD
$db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004', 'projets2');
if (!$db_handle) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}

// Récupérer l'ID de l'utilisateur connecté
$id_utilisateur = $_SESSION['id_utilisateur'];

// Récupérer les notifications pour l'utilisateur connecté
$sql_notifications = "SELECT * FROM notification WHERE id_utilisateur='$id_utilisateur'";
$result_notifications = $db_handle->query($sql_notifications);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia - Notifications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <h1>Agora Francia</h1>
            <img src="logo.png" alt="Logo Agora">
        </header>
        <nav class="navigation">
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="ToutParcourir.php">Tout Parcourir</a></li>
                <li><a href="Notifications.php" class="active">Notifications</a></li>
                <li><a href="Panier.php">Panier</a></li>
                <li><a href="VotreCompte.php">Votre Compte</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <section class="section">
            <h2>Bienvenue sur Agora Francia</h2>
            <h2>Notifications</h2>*
            <h2>Notifications en cours</h2>
            <?php
            if ($result_notifications->num_rows > 0) {
                while ($notification = $result_notifications->fetch_assoc()) {
                    echo "<h3>Notification ID: " . $notification['id_notif'] . "</h3>";
                    echo "<p>Prix: " . $notification['prix'] . "</p>";
                    echo "<p>Type: " . $notification['type'] . "</p>";
                    echo "<p>Rareté: " . $notification['rarete'] . "</p>";
                    echo "<p>Statut: " . $notification['statut'] . "</p>";

                    // Rechercher les articles correspondants
                    $sql_articles = "SELECT * FROM article WHERE type_produit='" . $notification['type'] . "' AND rarete='" . $notification['rarete'] . "' AND prix <= " . $notification['prix'];
                    $result_articles = $db_handle->query($sql_articles);

                    if ($result_articles->num_rows > 0) {
                        echo "<h4>Articles correspondants:</h4>";
                        echo "<table border='1'>";
                        echo "<tr><th>ID Article</th><th>Nom</th><th>Prix</th><th>Type de vente</th><th>Type de produit</th><th>Rareté</th><th>Description</th></tr>";
                        while ($article = $result_articles->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $article['id_article'] . "</td>";
                            echo "<td>" . $article['nom'] . "</td>";
                            echo "<td>" . $article['prix'] . "</td>";
                            echo "<td>" . $article['type_vente'] . "</td>";
                            echo "<td>" . $article['type_produit'] . "</td>";
                            echo "<td>" . $article['rarete'] . "</td>";
                            echo "<td>" . $article['description'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>Aucun article ne correspond aux critères de cette notification.</p>";
                    }
                }
            } else {
                echo "<p>Aucune notification trouvée.</p>";
            }

            $db_handle->close();
            ?>
            
</body>
</html>
