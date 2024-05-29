<?php
// Vérifier si l'utilisateur est connecté en tant qu'administrateur
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['type_de_compte'] !== 'administrateur') {
    // Rediriger vers une page d'erreur ou de connexion
    header("Location: erreur.php");
    exit();
}

// Traitement du formulaire d'ajout d'article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_article'])) {
    // Connectez-vous à la base de données
    $db_handle = mysqli_connect('localhost', 'root', '');
    $db_found = mysqli_select_db($db_handle, 'projets2');

    // Vérifiez si la connexion à la base de données est réussie
    if (!$db_handle || !$db_found) {
        die("Erreur de connexion à la base de données.");
    }

    // Récupérer les données du formulaire
    $nom_article = isset($_POST['nom_article']) ? $_POST['nom_article'] : '';
    $image = isset($_POST['image']) ? $_POST['image'] . ".jpg" : '';
    $prix = isset($_POST['prix']) ? $_POST['prix'] : '';
    $type_de_vente = isset($_POST['type_de_vente']) ? $_POST['type_de_vente'] : '';
    $rarete = isset($_POST['rarete']) ? $_POST['rarete'] : '';
    $vendeur_id = isset($_POST['vendeur_id']) ? $_POST['vendeur_id'] : '';

    // Prévenir les injections SQL
    $nom_article = $db_handle->real_escape_string($nom_article);
    $image = $db_handle->real_escape_string($image);
    $prix = $db_handle->real_escape_string($prix);
    $type_de_vente = $db_handle->real_escape_string($type_de_vente);
    $rarete = $db_handle->real_escape_string($rarete);
    $vendeur_id = $db_handle->real_escape_string($vendeur_id);

    // Requête SQL pour insérer le nouvel article dans la base de données
    $sql_insert_article = "INSERT INTO article (nom_article, image, prix, type_de_vente, rarete, vendeur_id) VALUES ('$nom_article', '$image', '$prix', '$type_de_vente', '$rarete', '$vendeur_id')";
    $result_insert_article = mysqli_query($db_handle, $sql_insert_article);

    if ($result_insert_article) {
        // Générer le message de confirmation avec les informations de l'article ajouté
        $message = "Nouvel article ajouté avec succès. Voici les informations :<br>";
        $message .= "Nom de l'article : " . htmlspecialchars($nom_article) . "<br>";
        $message .= "Image : " . htmlspecialchars($image) . "<br>";
        $message .= "Prix : " . htmlspecialchars($prix) . "<br>";
        $message .= "Type de vente : " . htmlspecialchars($type_de_vente) . "<br>";
        $message .= "Rareté : " . htmlspecialchars($rarete) . "<br>";
        $message .= "Numéro de vendeur : " . htmlspecialchars($vendeur_id) . "<br>";
    } else {
        // Gérer l'erreur lors de l'ajout de l'article
        $erreur = "Erreur lors de l'ajout de l'article.";
    }
    $db_handle->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un article</title>
</head>
<body>
    <?php if (!empty($message)) { ?>
        <h1 style="color: green;"><?php echo $message; ?></h1>
    <?php } elseif (!empty($erreur)) { ?>
        <h1 style="color: red;"><?php echo $erreur; ?></h1>
    <?php } ?>

    <a href="formulaire_ajout_utilisateur.php">Retour au formulaire</a>
</body>
</html>
