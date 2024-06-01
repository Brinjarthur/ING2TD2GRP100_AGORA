<?php
session_start();
if (!isset($_SESSION['loggedin'])  $_SESSION['type_de_compte'] !== 'acheteur') {
    header("Location: login_utilisateur.php");
    exit();
}

// Connectez-vous à la base de données
$db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004');
$db_found = mysqli_select_db($db_handle, 'projets2');

if (!$db_handle  !$db_found) {
    die("Erreur de connexion à la base de données.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_credit_card'])) {
    $numero_carte = $_POST['numero_carte'];
    $expiration = $_POST['expiration'];
    $cvv = $_POST['cvv'];
    $id_utilisateur = $_SESSION['id_utilisateur'];

    // Prévenir les injections SQL
    $numero_carte = mysqli_real_escape_string($db_handle, $numero_carte);
    $expiration = mysqli_real_escape_string($db_handle, $expiration);
    $cvv = mysqli_real_escape_string($db_handle, $cvv);

    // Mettre à jour les informations de la carte de crédit
    $sql_update_credit_card = "UPDATE utilisateurs SET numero_carte='$numero_carte', expiration='$expiration', cvv='$cvv' WHERE id_utilisateur='$id_utilisateur'";
    if (mysqli_query($db_handle, $sql_update_credit_card)) {
        $_SESSION['message'] = "Informations de la carte de crédit mises à jour avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour des informations de la carte de crédit.";
    }

    header("Location: connexion_utilisateur.html");
    exit();
}
?>
