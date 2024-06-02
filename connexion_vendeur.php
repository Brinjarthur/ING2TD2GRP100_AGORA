<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajouter un article ou un vendeur</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 50%;
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
            <h1>Ajouter un nouvel article</h1>
            <form method="post" action="ajouter_article.php">
                <table>
                    <tr>
                        <td><label for="nom_article">Nom de l'article :</label></td>
                        <td><input type="text" id="nom_article" name="nom_article" required></td>
                    </tr>
                    <tr>
                        <td><label for="image">Nom du fichier image :</label></td>
                        <td><input type="text" id="image" name="image" required></td>
                    </tr>
                    <tr>
                        <td><label for="prix">Prix :</label></td>
                        <td><input type="number" step="0.01" id="prix" name="prix" required></td>
                    </tr>
                    <tr>
                        <td><label for="type_vente">Type de vente :</label></td>
                        <td>
                            <select id="type_vente" name="type_vente">
                                <option value="enchere">Enchère</option>
                                <option value="immediat">Vente immédiate</option>
                                <option value="vendeur_client">Vendeur client</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="rarete">Rareté :</label></td>
                        <td>
                            <select id="rarete" name="rarete">
                                <option value="regulier">Régulier</option>
                                <option value="rare">Rare</option>
                                <option value="haut_de_gamme">Haut de gamme</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="ajouter_article" value="Ajouter Article">
            </form>

            <h1>Ajouter un nouveau vendeur</h1>
            <form method="post" action="ajouter_vendeur.php">
                <table>
                    <tr>
                        <td><label for="identifiant_vendeur">Identifiant :</label></td>
                        <td><input type="text" id="identifiant_vendeur" name="identifiant_vendeur" required></td>
                    </tr>
                    <tr>
                        <td><label for="mot_de_passe_vendeur">Mot de passe :</label></td>
                        <td><input type="password" id="mot_de_passe_vendeur" name="mot_de_passe_vendeur" required></td>
                    </tr>
                </table>
                <input type="submit" name="ajouter_vendeur" value="Ajouter Vendeur">
            </form>
        </section>
        <footer class="footer">
            <p>© 2024 Agora Francia. Tous droits réservés.</p>
            <p>Contactez-nous: email@agorafrancia.com | +33 1 23 45 67 89 | 3 rue du bief Verdun-Sur-Le-Doubs 71350</p>
        </footer>
    </div>
</body>
</html>
