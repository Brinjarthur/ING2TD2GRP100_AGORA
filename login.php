<?php
// Identifier le nom de la base de données
$database = "projetS2";

// Connectez-vous à votre BDD
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);

// Vérifier si la connexion et la sélection de la base de données ont réussi
if (!$db_handle || !$db_found) {
    die("Erreur de connexion à la base de données.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant'];
    $motdepasse = $_POST['motdepasse'];

    // Prévenir les injections SQL
    $identifiant = $db_handle->real_escape_string($identifiant);
    $motdepasse = $db_handle->real_escape_string($motdepasse);

    // Modifier la requête pour vérifier l'identifiant ou le pseudo
    $sql = "SELECT * FROM utilisateurs WHERE (identifiant='$identifiant' OR pseudo='$identifiant') AND mot_de_passe='$motdepasse'";
    $result = $db_handle->query($sql);

    if ($result->num_rows > 0) {
        // Récupérer les informations de l'utilisateur
        $row = $result->fetch_assoc();
        $identifiant_utilisateur = $row['identifiant'];
        $type_de_compte = $row['type_de_compte'];

        // Définir les variables de session
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['identifiant'] = $identifiant_utilisateur;
        $_SESSION['pseudo'] = $row['pseudo'];
        $_SESSION['type_de_compte'] = $type_de_compte;

        // Rediriger vers différentes pages en fonction du type de compte avec paramètres d'URL
        switch ($type_de_compte) {
            case 'administrateur':
                header("Location: connexion_admin.html?type=$type_de_compte&identifiant=$identifiant_utilisateur");
                break;
            case 'vendeur':
                header("Location: connexion_vendeur.html?type=$type_de_compte&identifiant=$identifiant_utilisateur");
                break;
            case 'acheteur':
                header("Location: connexion_acheteur.html?type=$type_de_compte&identifiant=$identifiant_utilisateur");
                break;

            // Ajouter d'autres cas si nécessaire
            default:
                echo "Identifiant ou mot de passe incorrect.";
                break;
        }
        exit();
    } else {
        echo "Identifiant ou mot de passe incorrect.";
    }
}
$db_handle->close();
?>
