<?php
session_start();

// Connexion à la base
$host = 'localhost';
$dbname = 'alban.chabalier';
$username = 'alban.chabalier';
$password = 'Chaudeyrac48';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// GESTION DES FILTRES
$theme = isset($_GET['theme']) ? $_GET['theme'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'set_name';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$setsPerPage = 8;
$offset = ($page - 1) * $setsPerPage;

// Compter le total des sets (filtrés)
$whereClause = "";
$params = [];

if ($theme) {
    $whereClause = " WHERE theme_name = :theme ";
    $params[':theme'] = $theme;
}

$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM lego_sets $whereClause");
$stmtCount->execute($params);
$totalSets = $stmtCount->fetchColumn();
$totalPages = ceil($totalSets / $setsPerPage);

// Charger les sets à afficher
$query = "SELECT * FROM lego_sets";
if ($whereClause) $query .= $whereClause;
$query .= " ORDER BY $sort LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);
if ($theme) $stmt->bindParam(':theme', $theme);
$stmt->bindValue(':limit', $setsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$sets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les thèmes pour le filtre
$themesStmt = $pdo->query("SELECT DISTINCT theme_name FROM lego_sets ORDER BY theme_name");
$themes = $themesStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include('header.php'); ?>

<main style="max-width: 1200px; margin: 2rem auto; padding: 1rem; font-family: Arial, sans-serif;">
    <h1 style="text-align:center; margin-bottom: 2rem; color:#333;">Liste des sets LEGO</h1>

    <!-- Filtres -->
<!-- Filtres -->
<form method="GET" style="margin: 2rem auto; display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; align-items: center; background: #f1f1f1; padding: 1.5rem 2rem; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); max-width: 900px;">
    <div style="display: flex; flex-direction: column; align-items: start;">
        <label for="theme" style="font-weight: bold; margin-bottom: 0.5rem; color: #333;">Filtrer par thème</label>
        <select id="theme" name="theme" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid #ccc; background: white; font-size: 1rem;">
            <option value="">-- Tous --</option>
            <?php foreach ($themes as $t): ?>
                <option value="<?= htmlspecialchars($t) ?>" <?= $theme === $t ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="display: flex; flex-direction: column; align-items: start;">
        <label for="sort" style="font-weight: bold; margin-bottom: 0.5rem; color: #333;">Trier par</label>
        <select id="sort" name="sort" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid #ccc; background: white; font-size: 1rem;">
            <option value="set_name" <?= $sort === 'set_name' ? 'selected' : '' ?>>Nom</option>
            <option value="year_released" <?= $sort === 'year_released' ? 'selected' : '' ?>>Année</option>
            <option value="set_number" <?= $sort === 'set_number' ? 'selected' : '' ?>>Numéro</option>
        </select>
    </div>
</form>


    <!-- Liste des sets -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:1.5rem;">
        <?php foreach ($sets as $set): ?>
            <div style="border:1px solid #ddd; padding:1rem; border-radius:10px; text-align:center; background:#fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: transform 0.2s;">
                <a href="detail_set.php?ID_SET=<?= urlencode($set['set_number']) ?>" style="text-decoration:none; color:inherit;">
                    <img src="<?= htmlspecialchars($set['image_url']) ?>" alt="<?= htmlspecialchars($set['set_name']) ?>" style="max-width:100%; height:auto; border-radius:5px;">
                    <h3 style="margin:0.8rem 0; color:#d32f2f;"><?= htmlspecialchars($set['set_name']) ?></h3>
                    <p><strong>Année :</strong> <?= htmlspecialchars($set['year_released']) ?></p>
                    <p><strong>Numéro :</strong> <?= htmlspecialchars($set['set_number']) ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination stylée -->
    <div style="margin-top:3rem; display:flex; justify-content:center; align-items:center; gap:1rem; flex-wrap:wrap;">
        <?php if ($page > 1): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>"
               style="padding:0.5rem 1rem; background:#d32f2f; color:white; text-decoration:none; border-radius:5px;">⬅ Précédent</a>
        <?php endif; ?>

        <span style="font-weight:bold; color:#555;">Page <?= $page ?> / <?= $totalPages ?></span>

        <?php if ($page < $totalPages): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>"
               style="padding:0.5rem 1rem; background:#d32f2f; color:white; text-decoration:none; border-radius:5px;">Suivant ➡</a>
        <?php endif; ?>
    </div>
</main>
