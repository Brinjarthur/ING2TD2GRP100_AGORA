<?php
<?php
include 'session.php';
checkLoggedIn();

// Connectez-vous à votre BDD
$db_handle = mysqli_connect('localhost', 'root', '', 'projets2');
if (!$db_handle) {
    die("Erreur de connexion à la base de données: " . mysqli_connect_error());
}

$id_utilisateur = getLoggedInUserId();

$sql_utilisateur = "SELECT * FROM utilisateur WHERE id_utilisateur='$id_utilisateur'";
$result_utilisateur = $db_handle->query($sql_utilisateur);

$sql_acheteur = "SELECT * FROM acheteur WHERE id_utilisateur='$id_utilisateur'";
$result_acheteur = $db_handle->query($sql_acheteur);

$sql_paiement = "SELECT * FROM paiement WHERE id_acheteur IN (SELECT id_acheteur FROM acheteur WHERE id_utilisateur='$id_utilisateur')";
$result_paiement = $db_handle->query($sql_paiement);

function openConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projets2";

    // Créer une connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion: " . $conn->connect_error);
    }

    return $conn;
}

function closeConnection($conn) {
    $conn->close();
}

function getLoggedInUserId() {
    // Démarrer la session
    session_start();

    // Vérifie si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        // Si oui, retourne l'ID de l'utilisateur
        return $_SESSION['user_id'];
    } else {
        // Sinon, redirige l'utilisateur vers la page de connexion
        header("Location: login.php");
        exit(); // Arrête l'exécution du script après la redirection
    }
}

// Établir une connexion à la base de données
$conn = openConnection();

// Initialisation des filtres
$filter_type_vente = isset($_GET['type_vente']) ? $_GET['type_vente'] : '';
$filter_rarete = isset($_GET['rarete']) ? $_GET['rarete'] : '';

// Construire la requête SQL en fonction des filtres
$sql = "SELECT * FROM article WHERE 1=1";
if (!empty($filter_type_vente)) {
    $sql .= " AND type_vente = '$filter_type_vente'";
}
if (!empty($filter_rarete)) {
    $sql .= " AND rarete = '$filter_rarete'";
}

// Exécuter la requête SQL
$result = $conn->query($sql);

function add_to_cart($id_article) {
    $conn = openConnection();

    // Récupérer l'ID utilisateur connecté
    $id_utilisateur = getLoggedInUserId();

    // Ajouter l'article au panier
    $sql_add_to_cart = "INSERT INTO panier (id_utilisateur, id_article) VALUES (?, ?)";
    $stmt_add_to_cart = $conn->prepare($sql_add_to_cart);
    $stmt_add_to_cart->bind_param("ii", $id_utilisateur, $id_article);

    if ($stmt_add_to_cart->execute()) {
        echo "Article ajouté au panier avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'article au panier.";
    }

    closeConnection($conn);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $id_article = intval($_POST['article_id']);
    add_to_cart($id_article);
}


checkLoggedIn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Parcourir - Agora Francia</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .article-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .article-item {
            flex: 1 1 calc(33.333% - 20px);
            box-sizing: border-box;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .article-item img {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
        }
        .article-info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .article-info-bottom {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }
        .filters {
            margin-bottom: 20px;
        }
        .filters label {
            margin-right: 10px;
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
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="ToutParcourir.php" class="active">Tout Parcourir</a></li>
                <li><a href="Notifications.php">Notifications</a></li>
                <li><a href="Panier.php">Panier</a></li>
                <li><a href="VotreCompte.php">Votre Compte</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>

        <section class="section">
            <div class="filters">
                <form action="" method="GET">
                    <label for="type_vente">Filtrer par type de vente:</label>
                    <select name="type_vente" id="type_vente">
                        <option value="">Tous</option>
                        <option value="vente immediate">Vente immédiate</option>
                        <option value="vendeur client">Vendeur client</option>
                        <option value="enchere">Enchère</option>
                    </select>
                    <label for="rarete">Filtrer par rareté:</label>
                    <select name="rarete" id="rarete">
                        <option value="">Tous</option>
                        <option value="regulier">Régulier</option>
                        <option value="rare">Rare</option>
                        <option value="haut de gamme">Haut de gamme</option>
                    </select>
                    <input type="submit" value="Filtrer">
                </form>
            </div>
            <div class="article-grid">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="article-item">';
                        echo '<img src="' . (!empty($row['image']) ? htmlspecialchars($row['image']) : 'placeholder.jpg') . '" alt="' . (!empty($row['nom']) ? htmlspecialchars($row['nom']) : 'Image') . '">';
                        echo '<div class="article-info">';
                        echo '<span>' . (!empty($row['nom']) ? htmlspecialchars($row['nom']) : '&nbsp;') . '</span>';
                        echo '<span>' . (!empty($row['prix']) ? htmlspecialchars($row['prix']) : '&nbsp;') . '€</span>';
                        echo '</div>';
                        echo '<div class="article-info-bottom">';
                        echo '<span>' . (!empty($row['rarete']) ? htmlspecialchars($row['rarete']) : '&nbsp;') . '</span>';
                        echo '<span>' . (!empty($row['type_vente']) ? htmlspecialchars($row['type_vente']) : '&nbsp;') . '</span>';
                        echo '</div>';
                        // Bouton "Ajouter au panier"
                        echo '<form method="post" action="">';
                        echo '<input type="hidden" name="article_id" value="' . $row['id_article'] . '">';
                        echo '<input type="submit" name="add_to_cart" value="Ajouter au panier">';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Aucun article trouvé.</p>';
                }
                // Fermer la connexion à la base de données
                closeConnection($conn);
                ?>
            </div>
        </section>
        <footer class="footer">
            <p>© 2024 Agora Francia. Tous droits réservés.</p>
            <p>Contactez-nous: email@agorafrancia.com | +33 1 23 45 67 89</p>
        </footer>
    </div>
</body>
</html>
