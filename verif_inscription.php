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

// Vérifie que les champs POST sont bien remplis
if (isset($_POST['pseudo'], $_POST['email'], $_POST['motdepasse']) &&!empty($_POST['pseudo']) &&!empty($_POST['email']) &&!empty($_POST['motdepasse'])
) {
    // Récupération des données
    $v_pseudo = $_POST['pseudo'];
    $v_email = $_POST['email'];
    $v_password = $_POST['motdepasse'];

    // Hash du mot de passe pour la sécurité
    $hashed_password = password_hash($v_password, PASSWORD_DEFAULT);

    // Requête SQL
    $requete = "INSERT INTO utilisateurs (pseudo, email, motdepasse) VALUES (:pseudo, :email, :motdepasse)";
    $stmt = $db->prepare($requete);
    $stmt->bindParam(':pseudo', $v_pseudo);
    $stmt->bindParam(':email', $v_email);
    $stmt->bindParam(':motdepasse', $hashed_password);

    try {
        $stmt->execute();
        echo "✅ Inscription réussie ! <a href='index.php'>Retour</a>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "⚠️ Ce pseudo ou email est déjà utilisé.";
        } else {
            echo "❌ Erreur lors de l'inscription : " . $e->getMessage();
        }
    }

    $db = null;
} else {
    echo "⚠️ Merci de remplir tous les champs.";
}
    