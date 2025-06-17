<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Accueil - Gestion LEGO</title>

    <style>
        /* Reset minimal */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            margin: 0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f0f0;
            color: #222;
            line-height: 1.5;
        }

        /* Bannière principale */
        .hero {
            background: url('https://cdn.pixabay.com/photo/2017/06/07/19/30/lego-2389482_1280.jpg') no-repeat center center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-shadow: 2px 2px 5px #000a;
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: 2px;
        }

        main {
            max-width: 1100px;
            margin: 2rem auto 4rem;
            padding: 0 1rem;
        }

        /* Section Présentation */
        .presentation {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px #aaa5;
            margin-bottom: 3rem;
            text-align: center;
        }
        .presentation h2 {
            color: #d32f2f;
            margin-bottom: 1rem;
            font-weight: 800;
        }
        .presentation p {
            font-size: 1.2rem;
            max-width: 650px;
            margin: 0 auto;
            color: #444;
        }

        /* Call to Action Button */
        .cta {
            text-align: center;
            margin-bottom: 4rem;
        }
        .cta h2 {
            color: #d32f2f;
            font-weight: 900;
            margin-bottom: 1rem;
        }
        .cta a {
            background-color: #d32f2f;
            color: white;
            text-decoration: none;
            font-weight: 700;
            padding: 1rem 2.5rem;
            border-radius: 30px;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        .cta a:hover {
            background-color: #b71c1c;
            box-shadow: 0 6px 10px rgba(0,0,0,0.5);
        }

        /* Footer */
        footer {
            background: #222;
            color: #ccc;
            text-align: center;
            padding: 1.2rem;
            font-size: 0.9rem;
        }

    </style>
</head>
<body>

<div class="hero">
    Gestion LEGO
</div>

<main>
    <section class="presentation">
        <h2>Bienvenue sur Gestion LEGO</h2>
        <p>Gérez vos collections LEGO simplement et efficacement.</p>
    </section>

    <section class="cta">
        <h2>Prêt à enrichir votre collection ?</h2>
        <a href="ajouter_set.php">Ajouter un nouveau Set</a>
    </section>

</main>

<footer>
    &copy; <?php echo date('Y'); ?> Gestion LEGO. Tous droits réservés.
</footer>

</body>
</html>
