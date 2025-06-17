<?php

// Connexion à la base de données
$host = 'localhost';
$dbname = 'alban.chabalier';
$username = 'alban.chabalier';
$password = 'Chaudeyrac48';

try {
    $db = new PDO("mysql:host=$host;dbname=" . urlencode($dbname) . ";charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base : " . $e->getMessage());
}

// Vérification des données POST (car formulaire envoyé en POST)
if (isset($_POST['pseudo'], $_POST['email'], $_POST['motdepasse']) &&!empty($_POST['pseudo']) &&!empty($_POST['email']) &&!empty($_POST['motdepasse'])) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    // Hash du mot de passe
    $hash = password_hash($motdepasse, PASSWORD_DEFAULT);

    try {
        $requete = "INSERT INTO utilisateurs (pseudo, email, motdepasse) VALUES (:pseudo, :email, :motdepasse)";
        $stmt = $db->prepare($requete);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':motdepasse', $hash);

        $stmt->execute();
        echo "✅ Inscription réussie ! <a href='index.php'>Retour</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "⚠️ Ce pseudo ou email existe déjà.";
        } else {
            echo "❌ Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
} else {
    
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Connexion / Inscription - LEGO</title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f7f7f7;
        margin: 0; padding: 0;
        color: #333;
    }
    main {
        max-width: 420px;
        margin: 3rem auto 5rem;
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 0 12px #ccc;
    }
    h2 {
        color: #d32f2f;
        text-align: center;
        margin-bottom: 1.5rem;
        font-weight: 900;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
    }
    label {
        font-weight: 700;
        color: #555;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        padding: 0.8rem;
        font-size: 1rem;
        border: 2px solid #ddd;
        border-radius: 6px;
        transition: border-color 0.3s;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: #d32f2f;
        outline: none;
    }
    .btn {
        background-color: #d32f2f;
        border: none;
        color: white;
        padding: 0.9rem 1.5rem;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn:hover {
        background-color: #b71c1c;
    }
    .form-switch {
        margin: 1rem 0 2rem;
        text-align: center;
        color: #666;
        font-size: 0.9rem;
    }
    .form-switch a {
        color: #d32f2f;
        cursor: pointer;
        text-decoration: none;
        font-weight: 700;
    }
    .message {
        text-align: center;
        margin-bottom: 1rem;
        font-weight: 700;
        color: #d32f2f;
    }
</style>

<script>
function switchForms() {
    const connexionForm = document.getElementById('connexion-form');
    const inscriptionForm = document.getElementById('inscription-form');
    if (connexionForm.style.display === 'none') {
        connexionForm.style.display = 'block';
        inscriptionForm.style.display = 'none';
    } else {
        connexionForm.style.display = 'none';
        inscriptionForm.style.display = 'block';
    }
}
</script>
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Formulaire Connexion -->
    <section id="connexion-form">
        <h2>Connexion</h2>
<form method="POST" action="verif_connexion.php">
    <label for="email">Adresse email</label>
    <input type="email" id="email" name="email" required placeholder="Votre adresse email">

    <label for="motdepasse">Mot de passe</label>
    <input type="password" id="motdepasse" name="motdepasse" required placeholder="Votre mot de passe">

    <button type="submit" class="btn">Se connecter</button>
</form>



        <div class="form-switch">
            Pas encore inscrit ? <a onclick="switchForms()">Créer un compte</a>
        </div>
    </section>

    <!-- Formulaire Inscription -->
    <section id="inscription-form" style="display:none;">
        <h2>Inscription</h2>
<form method="POST" action="verif_inscription.php">
    <label for="email">Adresse mail</label>
    <input type="email" id="email" name="email" required placeholder="exemple@domaine.com" />

    <label for="pseudo_inscription">Pseudo</label>
    <input type="text" id="pseudo_inscription" name="pseudo" required placeholder="Choisissez un pseudo" />

    <label for="motdepasse_inscription">Mot de passe</label>
    <input type="password" id="motdepasse_inscription" name="motdepasse" required placeholder="Choisissez un mot de passe" />

    <button type="submit" name="inscription" class="btn">S’inscrire</button>
</form>

        <div class="form-switch">
            Déjà inscrit ? <a onclick="switchForms()">Se connecter</a>
        </div>
    </section>
</main>

</body>
</html>


