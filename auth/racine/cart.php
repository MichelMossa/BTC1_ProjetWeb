<?php

require 'config.php';

require 'cart_functions.php';

session_start();

// Mise à jour du panier

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach ($_POST['quantite'] as $id => $qty) {

        updateCart($id, $qty);
    }

    header("Location: cart.php");

    exit;
}

$cartItems = [];

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $id => $qty) {

        $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_produit = ?");

        $stmt->execute([$id]);

        $product = $stmt->fetch();

        if ($product) {

            $product['quantite'] = $qty;

            $cartItems[] = $product;
        }
    }
}

$total = getCartTotal($pdo);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Panier - Cubic</title>
</head>

<body>
    <h1>Mon Panier</h1>
    <?php if ($cartItems): ?>
        <form method="post">
            <?php foreach ($cartItems as $item): ?>
                <div style="border:1px solid #ccc; padding:5px; margin:5px;">
                    <p><?= htmlspecialchars($item['nom_article']) ?> - <?= $item['prix_monnaie_virtuelle'] ?> MC</p>
                    <input type="number" name="quantite[<?= $item['id_produit'] ?>]" value="<?= $item['quantite'] ?>" min="0">
                </div>
            <?php endforeach; ?>
            <button type="submit">Mettre à jour le panier</button>
        </form>
        <p>Total : <?= $total ?> MC</p>
        <a href="checkout.php">Passer à la commande</a>
    <?php else: ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>
    <a href="shop.php">Retour à la boutique</a> | <a href="index.php">Accueil</a>
</body>

</html>