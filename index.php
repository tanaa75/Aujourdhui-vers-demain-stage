<?php
require_once 'db.php';
$query = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC");
$events = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Accueil - Aujourd'hui vers Demain</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container py-5">
        <h1 class="text-center mb-5">Nos Événements à Noisy-le-Sec</h1>
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($event['titre']) ?></h5>
                            <p class="card-text text-muted">📅 <?= date('d/m/Y H:i', strtotime($event['date_evenement'])) ?><br>📍 <?= htmlspecialchars($event['lieu']) ?></p>
                            <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>