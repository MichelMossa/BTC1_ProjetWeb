<?php

require 'config.php';

require 'cart_functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {

    addToCart($_POST['product_id'], 1);

    header("Location: shop.php");

    exit;
}

// Récupération des produits

$stmt = $pdo->query("SELECT * FROM produit ORDER BY categorie, nom_article");

$products = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Boutique - Cubic</title>
</head>

<body>
    <h1>Boutique Cubic</h1>
    <?php foreach ($products as $product): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px;">
            <h3><?= htmlspecialchars($product['nom_article']) ?></h3>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <p>Prix : <?= $product['prix_monnaie_virtuelle'] ?> MC</p>
            <form method="post">
                <input type="hidden" name="product_id" value="<?= $product['id_produit'] ?>">
                <button type="submit">Ajouter au panier</button>
            </form>
        </div>
    <?php endforeach; ?>
    <a href="cart.php">Voir le panier</a> | <a href="index.php">Accueil</a>
</body>

</html>