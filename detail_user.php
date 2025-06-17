<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php'; // adapte le chemin selon ton projet

// Redirection si non connecté
if (!isset($_SESSION['id'])) {
    header('Location: connexion.php');
    exit;
}

$id_utilisateur = $_SESSION['id'];

// Connexion PDO supposée dans $pdo (depuis config.php)

// 1. Récupérer pseudo + date d'inscription
$stmt = $pdo->prepare("SELECT pseudo, email FROM utilisateurs WHERE id = ?");
$stmt->execute([$id_utilisateur]);
$user = $stmt->fetch();

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}

// 2. Récupérer liste sets possédés (inventaire)
$stmt_inv = $pdo->prepare("
    SELECT s.id_set_number, s.set_name, s.image_url 
    FROM inventaire i
    JOIN sets s ON i.id_set_number = s.id_set_number
    WHERE i.id_utilisateur = ?
");
$stmt_inv->execute([$id_utilisateur]);
$inventaire = $stmt_inv->fetchAll();

// 3. Récupérer liste sets souhaités (wishlist)
$stmt_wish = $pdo->prepare("
    SELECT s.id_set_number, s.set_name, s.image_url 
    FROM wishlist w
    JOIN sets s ON w.id_set_number = s.id_set_number
    WHERE w.id_utilisateur = ?
");
$stmt_wish->execute([$id_utilisateur]);
$wishlist = $stmt_wish->fetchAll();

// 4. Nombre total de commentaires postés par l'utilisateur
$stmt_count_comments = $pdo->prepare("
    SELECT COUNT(*) as nb_commentaires 
    FROM commentaires 
    WHERE id_utilisateur = ?
");
$stmt_count_comments->execute([$id_utilisateur]);
$nb_commentaires = $stmt_count_comments->fetchColumn();

// 5. Liste des sets commentés par l'utilisateur (sans doublons)
$stmt_sets_comments = $pdo->prepare("
    SELECT DISTINCT s.id_set_number, s.set_name, s.image_url 
    FROM commentaires c
    JOIN sets s ON c.id_set_number = s.id_set_number
    WHERE c.id_utilisateur = ?
");
$stmt_sets_comments->execute([$id_utilisateur]);
$sets_commentes = $stmt_sets_comments->fetchAll();

?>

<?php include 'includes/header.php'; ?>

<h1>Profil de <?= htmlspecialchars($user['pseudo']) ?></h1>
<p><strong>Date d'inscription :</strong> <?= htmlspecialchars($user['date_inscription']) ?></p>

<h2>📦 Sets possédés (Inventaire)</h2>
<?php if (count($inventaire) > 0): ?>
    <ul style="list-style:none; padding-left:0;">
    <?php foreach ($inventaire as $set): ?>
        <li style="margin-bottom:1rem;">
            <strong><?= htmlspecialchars($set['set_name']) ?></strong><br>
            <?php if (!empty($set['image_url'])): ?>
                <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>" width="150">
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Vous ne possédez aucun set.</p>
<?php endif; ?>

<h2>⭐ Sets souhaités (Wishlist)</h2>
<?php if (count($wishlist) > 0): ?>
    <ul style="list-style:none; padding-left:0;">
    <?php foreach ($wishlist as $set): ?>
        <li style="margin-bottom:1rem;">
            <strong><?= htmlspecialchars($set['set_name']) ?></strong><br>
            <?php if (!empty($set['image_url'])): ?>
                <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>" width="150">
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Votre wishlist est vide.</p>
<?php endif; ?>

<h2>📝 Commentaires</h2>
<p>Nombre total de commentaires postés : <strong><?= $nb_commentaires ?></strong></p>

<h3>Sets commentés</h3>
<?php if (count($sets_commentes) > 0): ?>
    <ul style="list-style:none; padding-left:0;">
    <?php foreach ($sets_commentes as $set): ?>
        <li style="margin-bottom:1rem;">
            <strong><?= htmlspecialchars($set['set_name']) ?></strong><br>
            <?php if (!empty($set['image_url'])): ?>
                <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>" width="150">
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Vous n'avez commenté aucun set.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
