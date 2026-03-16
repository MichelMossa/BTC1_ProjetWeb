<?php

session_start(); // Démarre la session si ce n'est pas déjà fait

// Ajouter un produit au panier

function addToCart($productId, $quantity = 1)
{

    if (!isset($_SESSION['cart'])) {

        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {

        $_SESSION['cart'][$productId] += $quantity;
    } else {

        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Supprimer un produit du panier

function removeFromCart($productId)
{

    if (isset($_SESSION['cart'][$productId])) {

        unset($_SESSION['cart'][$productId]);
    }
}

// Modifier la quantité dans le panier

function updateCart($productId, $quantity)
{

    if ($quantity <= 0) {

        removeFromCart($productId);
    } else {

        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Calculer le total du panier

function getCartTotal($pdo)
{

    $total = 0;

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

        foreach ($_SESSION['cart'] as $id => $qty) {

            $stmt = $pdo->prepare("SELECT prix_monnaie_virtuelle FROM produit WHERE id_produit = ?");

            $stmt->execute([$id]);

            $product = $stmt->fetch();

            if ($product) $total += $product['prix_monnaie_virtuelle'] * $qty;
        }
    }

    return $total;
}
