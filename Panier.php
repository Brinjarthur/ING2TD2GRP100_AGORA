<?php
session_start(); // Démarre une nouvelle session ou reprend une session existante

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "Yoda,2004", "projets2");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Arrête le script si la connexion échoue
}

// Vider le panier
if (isset($_POST['vider_panier'])) { // Vérifie si le formulaire de vidage du panier a été soumis
    $id_utilisateur = $_SESSION['id_utilisateur']; // Récupère l'ID de l'utilisateur depuis la session
    $sql = "DELETE FROM panier WHERE id_utilisateur = $id_utilisateur"; // Requête SQL pour supprimer tous les articles du panier de l'utilisateur
    if ($conn->query($sql) === TRUE) { // Exécute la requête et vérifie si elle a réussi
        unset($_SESSION['panier']); // Supprime la variable de session 'panier'
    } else {
        echo "Error: " . $conn->error; // Affiche une erreur en cas d'échec de la requête
    }
}

// Récupérer les articles du panier pour l'utilisateur connecté
$id_utilisateur = $_SESSION['id_utilisateur']; // Récupère l'ID de l'utilisateur depuis la session
$sql = "SELECT article.nom, article.image, article.prix 
        FROM panier 
        JOIN article ON panier.id_article = article.id_article 
        WHERE panier.id_utilisateur = $id_utilisateur"; // Requête SQL pour récupérer les articles du panier
$result = $conn->query($sql); // Exécute la requête
$cart_items = [];
if ($result->num_rows > 0) { // Vérifie s'il y a des résultats
    while($row = $result->fetch_assoc()) { // Parcourt les résultats
        $cart_items[] = $row; // Ajoute chaque article au tableau $cart_items
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia - Panier</title>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <h1>Votre Panier</h1>
            <img src="logo.png" alt="Logo Agora"> <!-- Logo du site -->
        </header>
        <nav class="navigation">
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="ToutParcourir.php">Tout Parcourir</a></li>
                <li><a href="Notifications.php">Notifications</a></li>
                <li><a href="Panier.php" class="active">Panier</a></li> <!-- Lien actif vers la page du panier -->
                <li><a href="VotreCompte.php">Votre Compte</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <section class="section">
            <h2>Articles dans votre panier</h2>
            <?php
            if (!empty($cart_items)) { // Vérifie si le panier contient des articles
                echo '<div class="items">';
                foreach ($cart_items as $article) { // Parcourt les articles du panier
                    echo '<div class="item">';
                    echo '<img src="'.$article["image"].'" alt="'.$article["nom"].'">'; // Affiche l'image de l'article
                    echo '<p>'.$article["nom"].'</p>'; // Affiche le nom de l'article
                    echo '<p>Prix : '.$article["prix"].' €</p>'; // Affiche le prix de l'article
                    echo '</div>';
                }
                echo '</div>';
                echo '<form method="post" action="Panier.php">'; // Formulaire pour vider le panier
                echo '<button type="submit" name="vider_panier">Vider le panier</button>'; // Bouton pour soumettre le formulaire
                echo '</form>';
            } else {
                echo "<p>Votre panier est vide.</p>"; // Message si le panier est vide
            }
            ?>
        </section>
        <footer class="footer">
            <p>© 2024 Agora Francia. Tous droits réservés.</p>
            <p>Contactez-nous: email@agorafrancia.com | +33 1 23 45 67 89</p> <!-- Coordonnées de contact -->
        </footer>
    </div>
</body>
</html>
