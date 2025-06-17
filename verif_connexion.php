<?php
include 'config.php';


// Vérification des champs
if (
    isset($_POST['email'], $_POST['motdepasse']) &&
    !empty($_POST['email']) &&
    !empty($_POST['motdepasse'])
) {
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    // Recherche de l'utilisateur par email
    $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        if (password_verify($motdepasse, $utilisateur['motdepasse'])) {
            // Connexion réussie
            $_SESSION['pseudo'] = $utilisateur['pseudo'];
            $_SESSION['email'] = $utilisateur['email'];
            $_SESSION['id'] = $utilisateur['id'];
            //header("Location:index.php");
            exit;
        } else {
            echo "❌ Mot de passe incorrect.";
        }
    } else {
        echo "❌ Cette adresse email n'est pas enregistrée.";
    }
} else {
    echo "⚠️ Merci de remplir tous les champs.";
}
?> 
