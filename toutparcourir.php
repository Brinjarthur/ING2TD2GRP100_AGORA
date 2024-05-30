<?php
session_start();

// Ajouter un article au panier
if (isset($_POST['ajouter_au_panier'])) {
    $article_id = $_POST['article_id'];
    $article_nom = $_POST['article_nom'];
    $article_prix = $_POST['article_prix'];
    $article_image = $_POST['article_image'];

    $article = [
        'id' => $article_id,
        'nom' => $article_nom,
        'prix' => $article_prix,
        'image' => $article_image
    ];

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    $_SESSION['panier'][] = $article;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agora Francia - Tout Parcourir</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function filterItems() {
            var rarete = document.getElementById('item-filter-rarete').value;
            var type_de_vente = document.getElementById('item-filter-type').value;

            var items = document.querySelectorAll('.item');
            items.forEach(function(item) {
                var itemRarete = item.dataset.rarete;
                var itemType = item.dataset.type;

                if ((rarete === 'all' || itemRarete === rarete) &&
                    (type_de_vente === 'all' || itemType === type_de_vente)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
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
                <li><a href="ToutParcourir.php" class="active">Tout Parcourir</a></li>
                <li><a href="Notifications.html">Notifications</a></li>
                <li><a href="Panier.php">Panier</a></li>
                <li><a href="VotreCompte.html">Votre Compte</a></li>
            </ul>
        </nav>
        <section class="section">
            <h2>Bienvenue sur Agora Francia</h2>
            <p>Choisissez une catégorie et explorez nos articles en vente.</p>

            <!-- Menu déroulant pour filtrer les articles -->
            <label for="item-filter-rarete">Filtrer par type d'article:</label>
            <select id="item-filter-rarete" onchange="filterItems()">
                <option value="all">Tous les articles</option>
                <option value="rare">Articles rares</option>
                <option value="haut-de-gamme">Articles haut de gamme</option>
                <option value="regulier">Articles réguliers</option>
            </select>

            <label for="item-filter-type">Filtrer par type de vente:</label>
            <select id="item-filter-type" onchange="filterItems()">
                <option value="all">Tous les types de vente</option>
                <option value="immediat">Achat immédiat</option>
                <option value="vendeur_client">Transaction vendeur-client</option>
                <option value="enchere">Meilleure offre</option>
            </select>

            <?php
            // Connectez-vous à la base de données
            $db_handle = mysqli_connect('localhost', 'root', 'Yoda,2004');
            $db_found = mysqli_select_db($db_handle, 'projets2');

            // Vérifiez si la connexion à la base de données est réussie
            if (!$db_handle || !$db_found) {
                die("Erreur de connexion à la base de données.");
            }

            // Fonction pour afficher les articles d'une sous-catégorie spécifique
            function afficher_articles($db_handle, $type_de_vente, $rarete) {
                $sql = "SELECT * FROM article WHERE type_de_vente = '$type_de_vente' AND rarete = '$rarete'";
                $result = mysqli_query($db_handle, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="items">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="item" data-rarete="'.$row["rarete"].'" data-type="'.$row["type_de_vente"].'">';
                        echo '<img src="'.$row["image"].'" alt="'.$row["nom_article"].'">';
                        echo '<p>'.$row["nom_article"].'</p>';
                        echo '<p>Prix : '.$row["prix"].' €</p>';
                        echo '<form method="post" action="ToutParcourir.php">';
                        echo '<input type="hidden" name="article_id" value="'.$row["id"].'">';
                        echo '<input type="hidden" name="article_nom" value="'.$row["nom_article"].'">';
                        echo '<input type="hidden" name="article_prix" value="'.$row["prix"].'">';
                        echo '<input type="hidden" name="article_image" value="'.$row["image"].'">';
                        echo '<button type="submit" name="ajouter_au_panier">Ajouter au panier</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo "Aucun article trouvé.";
                }
            }

            // Afficher les articles pour chaque catégorie et sous-catégorie
            $types_de_vente = ['immediat', 'vendeur_client', 'enchere'];
            $raretes = ['rare', 'haut-de-gamme', 'regulier'];

            foreach ($types_de_vente as $type_de_vente) {
                echo '<div class="category">';
                echo '<h3>'.$type_de_vente.'</h3>';
                foreach ($raretes as $rarete) {
                    echo '<div class="sub-category" data-category="'.$rarete.'">';
                    echo '<h4>Articles '.str_replace('-', ' ', $rarete).'</h4>';
                    afficher_articles($db_handle, $type_de_vente, $rarete);
                    echo '</div>';
                }
                echo '</div>';
            }

            // Fermer la connexion
            mysqli_close($db_handle);
            ?>

        </section>
        <footer class="footer">
            <p>© 2024 Agora Francia. Tous droits réservés.</p>
            <p>Contactez-nous: email@agorafrancia.com | +33 1 23 45 67 89</p>
        </footer>
    </div>
</body>
</html>
