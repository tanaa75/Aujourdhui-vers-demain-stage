<?php
require_once 'db.php';

// LOGIQUE DE RECHERCHE
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Si une recherche est faite
    $search = trim($_GET['search']);
    $sql = "SELECT * FROM evenements 
            WHERE titre LIKE :search 
            OR description LIKE :search 
            OR lieu LIKE :search 
            ORDER BY date_evenement DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
    $events = $stmt->fetchAll();
} else {
    // Sinon, on affiche tout par défaut
    $query = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC");
    $events = $query->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aujourd'hui vers Demain - Noisy-le-Sec</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        .hero-banner {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            margin-bottom: 50px;
        }
        .card-event {
            transition: transform 0.2s;
        }
        .card-event:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include 'navbar.php'; ?>

    <div class="hero-banner text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3">Ensemble pour Noisy</h1>
            <p class="lead mb-4 fs-4">
                Solidarité, entraide et partage.<br>
                L'association <strong>Aujourd'hui vers Demain</strong> construit l'avenir de nos quartiers avec vous.
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="#events" class="btn btn-warning btn-lg px-4 gap-3 fw-bold">📅 Voir l'agenda</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg px-4">💌 Nous contacter</a>
            </div>
        </div>
    </div>

    <div class="container mb-5" id="events">
        
        <h2 class="text-center mb-4 text-primary border-bottom pb-2 d-inline-block mx-auto" style="border-width: 3px !important;">
            Nos Prochains Événements
        </h2>

        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <form method="GET" action="#events" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-lg shadow-sm" placeholder="Rechercher un événement (ex: Collecte, Concert...)" value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary btn-lg shadow-sm" type="submit">🔍</button>
                    <?php if(!empty($search)): ?>
                        <a href="index.php#events" class="btn btn-outline-secondary btn-lg" title="Effacer la recherche">✖</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if (count($events) > 0): ?>
            <?php if(!empty($search)): ?>
                <p class="text-center text-muted mb-4">Résultat pour : <strong><?= htmlspecialchars($search) ?></strong></p>
            <?php endif; ?>

            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow border-0 card-event overflow-hidden">
                            
                            <?php if (!empty($event['image']) && file_exists('uploads/' . $event['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($event['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($event['titre']) ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-header bg-primary text-white text-center py-4">
                                    <h1 class="mb-0">📅</h1>
                                    <?= date('d/m/Y', strtotime($event['date_evenement'])) ?>
                                </div>
                            <?php endif; ?>

                            <div class="card-body">
                                <h5 class="card-title text-dark fw-bold"><?= htmlspecialchars($event['titre']) ?></h5>
                                <h6 class="card-subtitle mb-3 text-muted">
                                    <?php if (!empty($event['image'])): ?>
                                        📅 <?= date('d/m/Y', strtotime($event['date_evenement'])) ?> <br>
                                    <?php endif; ?>
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
            <div class="alert alert-warning text-center py-5">
                <h4>😕 Aucun événement trouvé</h4>
                <p>Aucun événement ne correspond à votre recherche "<strong><?= htmlspecialchars($search) ?></strong>".</p>
                <a href="index.php" class="btn btn-outline-dark mt-2">Voir tous les événements</a>
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