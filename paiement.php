<?php
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Connectez-vous à la base de données
$db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004');
$db_found = mysqli_select_db($db_handle, 'projets2');

if (!$db_handle || !$db_found) {
    die("Erreur de connexion à la base de données.");
}

$id_utilisateur = $_SESSION['identifiant'];

// Récupérer les informations de la carte de crédit et adresse
$sql_get_credit_card = "SELECT numero_carte, expiration, cvv, adresse, ville, code_postal FROM utilisateurs WHERE identifiant='$id_utilisateur'";
$result_get_credit_card = mysqli_query($db_handle, $sql_get_credit_card);
$credit_card = mysqli_fetch_assoc($result_get_credit_card);

// Calculer le prix total des articles dans la catégorie "vente immédiate"
$total_prix = 0;
$articles_immediat = [];
if (!empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $article) {
        if ($article['type_vente'] == 'immediat') {
            $total_prix += $article['prix'];
            $articles_immediat[] = $article;
        }
    }
}

// Si le formulaire de paiement est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier les informations de paiement
    $numero_carte = !empty($_POST['numero_carte']) ? $_POST['numero_carte'] : $credit_card['numero_carte'];
    $expiration = !empty($_POST['expiration']) ? $_POST['expiration'] : $credit_card['expiration'];
    $cvv = !empty($_POST['cvv']) ? $_POST['cvv'] : $credit_card['cvv'];
    $adresse = !empty($_POST['adresse']) ? $_POST['adresse'] : $credit_card['adresse'];
    $ville = !empty($_POST['ville']) ? $_POST['ville'] : $credit_card['ville'];
    $code_postal = !empty($_POST['code_postal']) ? $_POST['code_postal'] : $credit_card['code_postal'];

    // Effectuer le paiement (cette partie est simulée, vous pouvez ajouter une intégration avec un processeur de paiement réel)
    $paiement_reussi = true; // Simuler un paiement réussi

    if ($paiement_reussi) {
        // Supprimer les articles de la base de données
        foreach ($articles_immediat as $article) {
            $id_article = $article['id'];
            $sql_delete_article = "DELETE FROM article WHERE id_article='$id_article'";
            mysqli_query($db_handle, $sql_delete_article);
        }

        // Vider le panier
        unset($_SESSION['panier']);

        // Rediriger vers la page d'accueil
        header("Location: Accueil.html");
        exit();
    } else {
        $message = "Le paiement a échoué. Veuillez réessayer.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia - Paiement</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <h1>Paiement</h1>
            <img src="logo.png" alt="Logo Agora">
        </header>
        <nav class="navigation">
            <ul>
                <li><a href="Accueil.html">Accueil</a></li>
                <li><a href="ToutParcourir.php">Tout Parcourir</a></li>
                <li><a href="Notifications.html">Notifications</a></li>
                <li><a href="Panier.php">Panier</a></li>
                <li><a href="VotreCompte.html">Votre Compte</a></li>
            </ul>
        </nav>
        <section class="section">
            <h2>Récapitulatif de votre commande</h2>
            <div class="items">
                <?php foreach ($articles_immediat as $article) { ?>
                    <div class="item">
                        <img src="<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>">
                        <p><?php echo htmlspecialchars($article['nom']); ?></p>
                        <p>Prix : <?php echo $article['prix']; ?> €</p>
                    </div>
                <?php } ?>
            </div>
            <h3>Total : <?php echo $total_prix; ?> €</h3>

            <h2>Informations de paiement</h2>
            <form method="post" action="paiement.php">
                <h3>Carte de crédit</h3>
                <?php if (!empty($credit_card['numero_carte']) && !empty($credit_card['expiration']) && !empty($credit_card['cvv'])) { ?>
                    <p>Numéro de la carte : <?php echo htmlspecialchars($credit_card['numero_carte']); ?></p>
                    <p>Date d'expiration : <?php echo htmlspecialchars($credit_card['expiration']); ?></p>
                    <p>CVV : <?php echo htmlspecialchars($credit_card['cvv']); ?></p>
                    <input type="hidden" name="numero_carte" value="<?php echo htmlspecialchars($credit_card['numero_carte']); ?>">
                    <input type="hidden" name="expiration" value="<?php echo htmlspecialchars($credit_card['expiration']); ?>">
                    <input type="hidden" name="cvv" value="<?php echo htmlspecialchars($credit_card['cvv']); ?>">
                <?php } else { ?>
                    <p>Veuillez entrer vos informations de carte de crédit :</p>
                    <label for="numero_carte">Numéro de la carte :</label>
                    <input type="text" id="numero_carte" name="numero_carte" required><br>
                    <label for="expiration">Date d'expiration :</label>
                    <input type="text" id="expiration" name="expiration" required><br>
                    <label for="cvv">CVV :</label>
                    <input type="text" id="cvv" name="cvv" required><br>
                <?php } ?>

                <h3>Adresse de livraison</h3>
                <?php if (!empty($credit_card['adresse']) && !empty($credit_card['ville']) && !empty($credit_card['code_postal'])) { ?>
                    <p>Adresse : <?php echo htmlspecialchars($credit_card['adresse']); ?></p>
                    <p>Ville : <?php echo htmlspecialchars($credit_card['ville']); ?></p>
                    <p>Code Postal : <?php echo htmlspecialchars($credit_card['code_postal']); ?></p>
                    <input type="hidden" name="adresse" value="<?php echo htmlspecialchars($credit_card['adresse']); ?>">
                    <input type="hidden" name="ville" value="<?php echo htmlspecialchars($credit_card['ville']); ?>">
                    <input type="hidden" name="code_postal" value="<?php echo htmlspecialchars($credit_card['code_postal']); ?>">
                <?php } else { ?>
                    <p>Veuillez entrer votre adresse de livraison :</p>
                    <label for="adresse">Adresse :</label>
                    <input type="text" id="adresse" name="adresse" required><br>
                    <label for="ville">Ville :</label>
                    <input type="text" id="ville" name="ville" required><br>
                    <label for="code_postal">Code Postal :</label>
                    <input type="text" id="code_postal" name="code_postal" required><br>
                <?php } ?>

                <input type="submit" value="Confirmer le paiement">
            </form>
        </section>
        <footer class="footer">
            <p>© 2024 Agora Francia. Tous droits réservés.</p>
            <p>Contactez-nous: email@agorafrancia.com | +33 1 23 45 67 89</p>
        </footer>
    </div>
</body>
</html>
<?php
mysqli_close($db_handle);
?>
