<?php
require_once 'db.php';

// On récupère les événements
$query = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC");
$events = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aujourd'hui vers Demain - Noisy-le-Sec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .hero-banner {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .card-event {
            transition: transform 0.2s;
        }
        .card-event:hover {
            transform: translateY(-5px); /* Petit effet de levitation au survol */
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include 'navbar.php'; ?>

    <div class="hero-banner text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Aujourd'hui vers Demain</h1>
            <p class="lead mb-4">Agir ensemble pour l'avenir des quartiers de Noisy-le-Sec.</p>
            <a href="#events" class="btn btn-warning btn-lg px-4">Voir nos actions</a>
        </div>
    </div>

    <div class="container mb-5" id="events">
        <h2 class="text-center mb-5 text-primary border-bottom pb-2 d-inline-block mx-auto" style="border-width: 3px !important;">
            Nos Prochains Événements
        </h2>

        <?php if (count($events) > 0): ?>
            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow border-0 card-event">
                            <div class="card-header bg-primary text-white text-center">
                                📅 <?= date('d/m/Y', strtotime($event['date_evenement'])) ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-dark fw-bold"><?= htmlspecialchars($event['titre']) ?></h5>
                                <h6 class="card-subtitle mb-3 text-muted">
                                    📍 <?= htmlspecialchars($event['lieu']) ?> <br>
                                    🕒 <?= date('H:i', strtotime($event['date_evenement'])) ?>
                                </h6>
                                <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <button class="btn btn-outline-primary btn-sm">En savoir plus</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Aucun événement prévu pour le moment. Revenez vite !
            </div>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <p class="mb-1">&copy; 2026 Association Aujourd'hui vers Demain</p>
            <small class="text-white-50">116 rue de l'Avenir, 93130 Noisy-le-Sec</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>