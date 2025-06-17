<?php
session_start();

$host = 'localhost';
$dbname = 'alban.chabalier';
$username = 'alban.chabalier';
$password = 'Chaudeyrac48';

// Vérification du paramètre ID_SET dans l'URL
if (!isset($_GET['ID_SET'])) {
    die("Erreur : aucun identifiant de set fourni.");
}

$idSet = $_GET['ID_SET'];

// Validation simple (lettres, chiffres, tirets, underscore)
if (!preg_match('/^[a-zA-Z0-9_-]+$/', $idSet)) {
    die("Erreur : identifiant de set invalide.");
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation et exécution de la requête pour le set demandé
    $stmt = $pdo->prepare("SELECT * FROM lego_sets WHERE set_number = ?");
    $stmt->execute([$idSet]);
    $set = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$set) {
        die("Erreur : set introuvable pour l'identifiant fourni.");
    }

    // Ici tu pourras ajouter les requêtes pour les commentaires, notes, utilisateurs, etc.

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<?php include 'header.php'; ?>

<style>
    main {
        max-width: 900px;
        margin: 2rem auto;
        font-family: Arial, sans-serif;
        padding: 0 1rem;
    }
    h1 {
        color: #d32f2f;
        margin-bottom: 1rem;
    }
    .set-detail {
        display: flex;
        gap: 2rem;
        align-items: flex-start;
        border: 1px solid #ccc;
        padding: 1rem;
        border-radius: 8px;
        background: #f9f9f9;
    }
    .set-image img {
        max-width: 300px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(211,47,47,0.3);
    }
    .set-info {
        flex-grow: 1;
    }
    .set-info p {
        font-size: 1.1rem;
        margin: 0.3rem 0;
    }
    .actions {
        margin-top: 1.5rem;
    }
    button {
        background-color: #d32f2f;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        margin-right: 1rem;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #b02828;
    }
    .comments, .ratings {
        margin-top: 2rem;
    }
    textarea {
        width: 100%;
        height: 80px;
        margin-top: 0.5rem;
        padding: 0.5rem;
        font-size: 1rem;
        border-radius: 4px;
        border: 1px solid #ccc;
        resize: vertical;
    }
    label {
        font-weight: bold;
        margin-top: 1rem;
        display: block;
    }
    .rating-stars input[type="radio"] {
        display: none;
    }
    .rating-stars label {
        font-size: 1.5rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.3s;
    }
    .rating-stars input[type="radio"]:checked ~ label,
    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #d32f2f;
    }
</style>

<main>
    <h1><?= htmlspecialchars($set['set_name']) ?></h1>
    <div class="set-detail">
        <div class="set-image">
            <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="Image de <?= htmlspecialchars($set['set_name']) ?>">
        </div>
        <div class="set-info">
            <p><strong>Numéro :</strong> <?= htmlspecialchars($set['set_number']) ?></p>
            <p><strong>Année de sortie :</strong> <?= htmlspecialchars($set['year_released']) ?></p>
            <p><strong>Nombre de pièces :</strong> <?= htmlspecialchars($set['number_of_parts']) ?></p>

            <div class="actions">
                <?php if (isset($_SESSION['pseudo'])): ?>
                    <button type="button" onclick="alert('Fonction à implémenter : Je possède ce set')">✅ Je possède ce set</button>
                    <button type="button" onclick="alert('Fonction à implémenter : Ajouter à ma wishlist')">⭐ Ajouter à ma wishlist</button>
                <?php else: ?>
                    <p><a href="inscription.php">Connectez-vous pour gérer vos sets</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<section class="ratings">
    <style>
        .rating-form {
            display: inline-block;
            margin-top: 0.5rem;
        }

        .rating-stars {
            direction: rtl;
            unicode-bidi: bidi-override;
            font-size: 2.5rem;
            user-select: none;
            display: flex;
            justify-content: flex-start;
            gap: 5px;
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars label {
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #d32f2f;
        }

        .rating-stars input[type="radio"]:checked ~ label {
            color: #ccc;
        }

        .rating-stars input[type="radio"]:checked + label,
        .rating-stars input[type="radio"]:checked + label ~ label {
            color: #d32f2f;
        }

        .rating-form button[type="submit"] {
            display: block;
            margin-top: 10px;
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .rating-form button[type="submit"]:hover {
            background-color: #b02828;
        }
    </style>

    <h2>Noter ce set</h2>

    <?php if (isset($_SESSION['pseudo'])): ?>
        <form method="post" action="ajouter_note.php" class="rating-form">
            <input type="hidden" name="set_number" value="<?= htmlspecialchars($set['set_number']) ?>">
            <div class="rating-stars">
                <?php for ($i=5; $i>=1; $i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>">
                    <label for="star<?= $i ?>" title="<?= $i ?> étoile<?= $i > 1 ? 's' : '' ?>">&#9733;</label>
                <?php endfor; ?>
            </div>
            <button type="submit">Envoyer la note</button>
        </form>
    <?php else: ?>
        <p><a href="inscription.php">Connectez-vous pour noter ce set</a></p>
    <?php endif; ?>
</section>


    <section class="comments">
        <h2>Commentaires</h2>
        <?php if (isset($_SESSION['pseudo'])): ?>
            <form method="post" action="ajouter_commentaire.php">
                <input type="hidden" name="set_number" value="<?= htmlspecialchars($set['set_number']) ?>">
                <label for="commentaire">Votre commentaire :</label>
                <textarea id="commentaire" name="commentaire" required></textarea>
                <button type="submit">Envoyer</button>
            </form>
        <?php else: ?>
            <p><a href="inscription.php">Connectez-vous pour commenter</a></p>
        <?php endif; ?>

        <!-- Ici tu pourras afficher les commentaires déjà postés -->
    </section>
</main>
