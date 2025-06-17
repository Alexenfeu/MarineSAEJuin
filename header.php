<?php
session_start();
?>

<!-- Header avec CSS intégré -->
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
    }

    header {
        background-color: #d32f2f;
        color: white;
        padding: 1rem 0;
        width: 100%;
    }

    .main-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-container a {
        color: white;
        text-decoration: none;
        font-weight: normal;
    }

    .main-container .logo {
        font-weight: bold;
        font-size: 1.2rem;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .logout {
        color: #ffdddd;
        font-weight: bold;
        font-size: 1.2rem;
    }
</style>

<header>
    <div class="main-container">
        <a href="index.php" class="logo">Gestion LEGO</a>
        <div class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="sets.php">Les Sets</a>
            <a href="ajouter_set.php">Ajouter un Set</a>

            <?php if (isset($_SESSION['pseudo'])): ?>
                <a href="detail_user.php">
                    👤 <?= htmlspecialchars($_SESSION['pseudo']) ?>
                </a>
                <a href="deconnexion.php" class="logout" title="Se déconnecter">&#x21aa;</a>
            <?php else: ?>
                <a href="inscription.php">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>
</header>
