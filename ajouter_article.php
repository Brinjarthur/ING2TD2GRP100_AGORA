<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projets2";

// Crée une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifie si les données sont bien envoyées par le formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupère les données du formulaire
    $nom_article = isset($_POST['nom_article']) ? $_POST['nom_article'] : '';
    $image = isset($_POST['image']) ? $_POST['image'] : '';
    $prix = isset($_POST['prix']) ? $_POST['prix'] : 0.0;
    $type_de_vente = isset($_POST['type_de_vente']) ? $_POST['type_de_vente'] : '';
    $rarete = isset($_POST['rarete']) ? $_POST['rarete'] : '';
    $vendeur_id = isset($_POST['vendeur_id']) ? $_POST['vendeur_id'] : 0;
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $date_depot = date("Y-m-d");

    // Vérifie la date de fin de vente si le type de vente est enchère
    if ($type_de_vente == 'enchere') {
        $date_fin_vente = isset($_POST['date_fin_vente']) ? $_POST['date_fin_vente'] : '';
    } else {
        $date_fin_vente = NULL;
    }

    // Prépare et exécute la requête SQL
    $sql = "INSERT INTO article (vendeur_id, nom_article, image, prix, type_de_vente, rarete, date_depot, date_fin_vente, description)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    $stmt->bind_param("issdsssss", $vendeur_id, $nom_article, $image, $prix, $type_de_vente, $rarete, $date_depot, $date_fin_vente, $description);

    if ($stmt->execute() === TRUE) {
        echo "Nouvel article ajouté avec succès";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

// Ferme la connexion
$conn->close();
?>
