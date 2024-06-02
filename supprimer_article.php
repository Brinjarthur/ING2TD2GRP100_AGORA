<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['type_de_compte'] !== 'administrateur') {
    header("Location: login_admin.php");
    exit();
}

// Connectez-vous à la base de données
$db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004');
$db_found = mysqli_select_db($db_handle, 'projets2');

// Vérifiez si la connexion à la base de données est réussie
if (!$db_handle || !$db_found) {
    die("Erreur de connexion à la base de données.");
}

// Suppression de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_utilisateur'])) {
    $utilisateur_id = $_POST['utilisateur_id'];
    $sql_delete_utilisateur = "DELETE FROM utilisateurs WHERE id='$utilisateur_id' AND type_de_compte='vendeur'";
    mysqli_query($db_handle, $sql_delete_utilisateur);
}

// Récupération des utilisateurs
$sql_get_utilisateurs = "SELECT * FROM utilisateurs WHERE type_de_compte='vendeur'";
$result_get_utilisateurs = mysqli_query($db_handle, $sql_get_utilisateurs);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supprimer un utilisateur</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <h1>Agora Francia</h1>
            <img src="logo.png" alt="Logo Agora">
        </header>
        <nav class="navigation">
            <ul>
                <li><a href="Accueil.html">Accueil</a></li>
                <li><a href="ToutParcourir.html">Tout Parcourir</a></li>
                <li><a href="Notifications.html">Notifications</a></li>
                <li><a href="Panier.html">Panier</a></li>
                <li><a href="VotreCompte.html" class="active">Votre Compte</a></li>
            </ul>
        </nav>
        <section class="section">
            <h1>Supprimer un utilisateur</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Identifiant</th>
                    <th>Type de compte</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result_get_utilisateurs)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['identifiant']); ?></td>
                    <td><?php echo htmlspecialchars($row['type_de_compte']); ?></td>
                    <td>
                        <form method="post" action="supprimer_utilisateur.php">
                            <input type="hidden" name="utilisateur_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" name="supprimer_utilisateur" value="Supprimer">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </section>
        <footer class="footer">
            <p>© 2024 Agora Francia. Tous droits réservés.</p>
            <p>Contactez-nous: email@agorafrancia.com | +33 1 23 45 67 89 | 3 rue du bief Verdun-Sur-Le-Doubs 71350</p>
        </footer>
    </div>
</body>
</html>
<?php
mysqli_close($db_handle);
?>
