<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['type_de_compte'] !== 'vendeur') {
    header("Location: login_vendeur.php");
    exit();
}

// Connectez-vous à la base de données
$db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004');
$db_found = mysqli_select_db($db_handle, 'projets2');

// Vérifiez si la connexion à la base de données est réussie
if (!$db_handle || !$db_found) {
    die("Erreur de connexion à la base de données.");
}

// Traitement du formulaire d'ajout de vendeur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_vendeur'])) {
    // Récupérer les données du formulaire
    $identifiant_vendeur = $_POST['identifiant_vendeur'];
    $mot_de_passe_vendeur = $_POST['mot_de_passe_vendeur'];

    // Prévenir les injections SQL
    $identifiant_vendeur = mysqli_real_escape_string($db_handle, $identifiant_vendeur);
    $mot_de_passe_vendeur = mysqli_real_escape_string($db_handle, $mot_de_passe_vendeur);

    // Vérifier si l'identifiant est déjà utilisé
    $sql_check_identifiant = "SELECT * FROM utilisateurs WHERE identifiant='$identifiant_vendeur'";
    $result_check_identifiant = mysqli_query($db_handle, $sql_check_identifiant);

    if (mysqli_num_rows($result_check_identifiant) > 0) {
        $_SESSION['error'] = "Identifiant déjà utilisé.";
        header("Location: connexion_vendeur.html");
        exit();
    } else {
        // Requête SQL pour insérer le nouveau vendeur dans la base de données
        $sql_insert_vendeur = "INSERT INTO utilisateurs (identifiant, mot_de_passe, type_de_compte) VALUES ('$identifiant_vendeur', '$mot_de_passe_vendeur', 'vendeur')";
        $result_insert_vendeur = mysqli_query($db_handle, $sql_insert_vendeur);

        if ($result_insert_vendeur) {
            $_SESSION['message'] = "Vendeur ajouté avec succès.";
            header("Location: connexion_vendeur.html");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du vendeur.";
            header("Location: connexion_vendeur.html");
            exit();
        }
    }
}
?>
