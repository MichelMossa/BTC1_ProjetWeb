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

<?php

require 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit;
}

// Récupérer infos utilisateur

$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");

$stmt->execute([$_SESSION['user_id']]);

$user = $stmt->fetch();

// Historique commandes

$stmt_orders = $pdo->prepare("

    SELECT c.id_commande, p.nom_article, p.prix_monnaie_virtuelle, c.date_transaction, c.statut_paiement 

    FROM commande c 

    JOIN produit p ON c.id_produit = p.id_produit 

    WHERE c.id_utilisateur = ? 

    ORDER BY c.date_transaction DESC

");

$stmt_orders->execute([$_SESSION['user_id']]);

$orders = $stmt_orders->fetchAll();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Profil - Cubic</title>
</head>

<body>
    <h1>Profil de <?= htmlspecialchars($user['pseudo']) ?></h1>
    <p>Email : <?= htmlspecialchars($user['email']) ?></p>
    <p>Rôle : <?= htmlspecialchars($user['role']) ?></p>
    <h2>Historique des commandes</h2>
    <?php if ($orders): ?>
        <ul>
            <?php foreach ($orders as $order): ?>
                <li><?= htmlspecialchars($order['nom_article']) ?> - <?= $order['prix_monnaie_virtuelle'] ?> MC - <?= $order['statut_paiement'] ?> (<?= $order['date_transaction'] ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune commande pour le moment.</p>
    <?php endif; ?>
    <a href="shop.php">Boutique</a> | <a href="logout.php">Déconnexion</a>
</body>

</html>