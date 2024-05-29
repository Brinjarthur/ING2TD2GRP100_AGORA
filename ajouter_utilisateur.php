<?php
// Vérifier si l'utilisateur est connecté en tant qu'administrateur
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['type_de_compte'] !== 'administrateur') {
    // Rediriger vers une page d'erreur ou de connexion
    header("Location: erreur.php");
    exit();
}

// Initialiser les variables
$message = "";
$erreur = "";

// Traitement du formulaire d'ajout d'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_utilisateur'])) {
    // Connectez-vous à la base de données
    $db_handle = mysqli_connect('localhost', 'root', '');
    $db_found = mysqli_select_db($db_handle, 'projetS2');

    // Vérifiez si la connexion à la base de données est réussie
    if (!$db_handle || !$db_found) {
        die("Erreur de connexion à la base de données.");
    }

    // Récupérer les données du formulaire
    $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : '';
    $nouveau_mot_de_passe = isset($_POST['nouveau_mot_de_passe']) ? $_POST['nouveau_mot_de_passe'] : '';
    $mail = isset($_POST['mail']) ? $_POST['mail'] : '';
    $nouveau_type_de_compte = isset($_POST['nouveau_type_de_compte']) ? $_POST['nouveau_type_de_compte'] : '';

    // Prévenir les injections SQL
    $pseudo = $db_handle->real_escape_string($pseudo);
    $nouveau_mot_de_passe = $db_handle->real_escape_string($nouveau_mot_de_passe);
    $mail = $db_handle->real_escape_string($mail);

    if (!empty($pseudo) && !empty($nouveau_mot_de_passe) && !empty($mail) && !empty($nouveau_type_de_compte)) {
        // Requête SQL pour insérer le nouvel utilisateur dans la base de données
        $sql_insert_utilisateur = "INSERT INTO utilisateurs (pseudo, mot_de_passe, mail, type_de_compte) VALUES ('$pseudo', '$nouveau_mot_de_passe', '$mail', '$nouveau_type_de_compte')";
        $result_insert_utilisateur = mysqli_query($db_handle, $sql_insert_utilisateur);
        if ($result_insert_utilisateur) {
            // Récupérer les informations de l'utilisateur ajouté
            $sql_get_utilisateur = "SELECT * FROM utilisateurs WHERE pseudo='$pseudo'";
            $result_get_utilisateur = mysqli_query($db_handle, $sql_get_utilisateur);
            $utilisateur_ajoute = mysqli_fetch_assoc($result_get_utilisateur);

            // Générer le message de confirmation avec les informations de l'utilisateur ajouté
            $message = "Nouvel utilisateur ajouté avec succès. Voici les informations :<br>";
            $message .= "Pseudo : " . $utilisateur_ajoute['pseudo'] . "<br>";
            $message .= "Mail : " . $utilisateur_ajoute['mail'] . "<br>";
            $message .= "Type de compte : " . $utilisateur_ajoute['type_de_compte'] . "<br>";
        } else {
            // Gérer l'erreur lors de l'ajout de l'utilisateur
            $erreur = "Erreur lors de l'ajout de l'utilisateur.";
        }
    } else {
        $erreur = "Tous les champs doivent être remplis.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un utilisateur</title>
</head>
<body>
    <?php if (!empty($message)) { ?>
        <h1 style="color: green;"><?php echo $message; ?></h1>
    <?php } elseif (!empty($erreur)) { ?>
        <h1 style="color: red;"><?php echo $erreur; ?></h1>
    <?php } ?>

    <a href="ajouter_utilisateur.html">Retour au formulaire</a>
</body>
</html>
