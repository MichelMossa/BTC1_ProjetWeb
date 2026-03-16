<?php

require '../../config/config.php';

session_start();

// Récupération des articles récents

$stmt = $pdo->query("SELECT a.titre, a.contenu, a.image_vitrine, a.date_publication, u.pseudo 

                     FROM article a 

                     JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur 

                     ORDER BY date_publication DESC LIMIT 5");

$articles = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Accueil - Cubic</title>
</head>

<body>
    <h1>Bienvenue sur Cubic Infrastructure Group</h1>
    <h2>Dernières actualités</h2>
    <?php foreach ($articles as $article): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px;">
            <h3><?= htmlspecialchars($article['titre']) ?></h3>
            <p><?= htmlspecialchars($article['contenu']) ?></p>
            <?php if ($article['image_vitrine']): ?>
                <img src="<?= htmlspecialchars($article['image_vitrine']) ?>" width="200">
            <?php endif; ?>
            <p>Publié par <?= htmlspecialchars($article['pseudo']) ?> le <?= $article['date_publication'] ?></p>
        </div>
    <?php endforeach; ?>
    <a href="shop.php">Accéder à la boutique</a> |
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="profile.php">Mon Profil</a>
    <?php else: ?>
        <a href="login.php">Se connecter</a>
    <?php endif; ?>
</body>

</html>