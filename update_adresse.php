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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_address'])) {
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $id_utilisateur = $_SESSION['id_utilisateur'];

    // Prévenir les injections SQL
    $adresse = mysqli_real_escape_string($db_handle, $adresse);
    $ville = mysqli_real_escape_string($db_handle, $ville);
    $code_postal = mysqli_real_escape_string($db_handle, $code_postal);

    // Mettre à jour l'adresse
    $sql_update_address = "UPDATE utilisateurs SET adresse='$adresse', ville='$ville', code_postal='$code_postal' WHERE id_utilisateur='$id_utilisateur'";
    if (mysqli_query($db_handle, $sql_update_address)) {
        $_SESSION['message'] = "Adresse mise à jour avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour de l'adresse.";
    }

    header("Location: connexion_utilisateur.html");
    exit();
}
?>
