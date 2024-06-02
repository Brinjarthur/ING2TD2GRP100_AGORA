<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Débogage : Début du script PHP login.php<br>";

// Identifier le nom de la base de données
$database = "projets2";

// Connectez-vous à votre BDD
$db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004');
if (!$db_handle) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}
echo "Débogage : Connexion à la base de données réussie<br>";

$db_found = mysqli_select_db($db_handle, $database);
if (!$db_found) {
    die("Erreur de sélection de la base de données.");
}
echo "Débogage : Sélection de la base de données réussie<br>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant'];
    $motdepasse = $_POST['motdepasse'];

    // Utiliser des déclarations préparées pour éviter les injections SQL
    $sql = "SELECT * FROM utilisateur WHERE (pseudo=? OR mail=?) AND mot_de_passe=?";
    $stmt = $db_handle->prepare($sql);
    if (!$stmt) {
        die("Erreur de préparation de la requête SQL : " . $db_handle->error);
    }
    $stmt->bind_param("sss", $identifiant, $identifiant, $motdepasse);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Débogage : Utilisateur trouvé<br>";
        // Récupérer les informations de l'utilisateur
        $row = $result->fetch_assoc();
        $identifiant_utilisateur = $row['id_utilisateur'];
        $type_de_compte = $row['type_compte'];

        // Définir les variables de session
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $identifiant_utilisateur;
        $_SESSION['pseudo'] = $row['pseudo'];
        $_SESSION['type_compte'] = $type_de_compte;

        echo "Débogage : Session démarrée et variables de session définies<br>";

        // Rediriger vers la page d'affichage de l'utilisateur
        header("Location: VotreCompte.php");
        exit();
    } else {
        echo "Identifiant ou mot de passe incorrect.";
    }
}
$db_handle->close();
?>
