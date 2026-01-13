<?php
require_once 'db.php';
// ... (Logique de recherche inchangée) ...
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $sql = "SELECT * FROM evenements WHERE titre LIKE :search OR description LIKE :search OR lieu LIKE :search ORDER BY date_evenement DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search' => "%$search%"]);
    $events = $stmt->fetchAll();
} else {
    $query = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC");
    $events = $query->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aujourd'hui vers Demain - Noisy-le-Sec</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body { transition: background-color 0.5s, color 0.5s; }
        .hero-banner {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 150px 0;
            margin-bottom: 0; /* On colle les chiffres clés */
        }
        /* Style des chiffres clés */
        .key-figures {
            background-color: #ffc107; /* Jaune Bootstrap Warning */
            color: #212529;
            padding: 40px 0;
            font-weight: bold;
        }
        .card-event { transition: all 0.4s ease; cursor: pointer; border-radius: 15px; overflow: hidden; }
        .card-event:hover { transform: translateY(-10px) scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include 'navbar.php'; ?>

    <div class="hero-banner text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3" data-aos="fade-down">
                Construire demain, dès aujourd'hui.
            </h1>
            <p class="lead mb-4 fs-4" data-aos="fade-in" data-aos-delay="300">
                Au cœur de Noisy-le-Sec, pour l'avenir de nos quartiers.
            </p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center" data-aos="zoom-in" data-aos-delay="500">
                <a href="actions.php" class="btn btn-warning btn-lg px-4 gap-3 fw-bold rounded-pill shadow-lg">📚 Aide aux devoirs</a>
                <a href="benevolat.php" class="btn btn-outline-light btn-lg px-4 rounded-pill">🤝 Devenir Bénévole</a>
            </div>
        </div>
    </div>

    <div class="key-figures text-center shadow">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3" data-aos="flip-left">
                    <h2 class="display-4 fw-bold">15+</h2>
                    <p class="mb-0 text-uppercase ls-1">Bénévoles engagés</p>
                </div>
                <div class="col-md-4 mb-3" data-aos="flip-left" data-aos-delay="200">
                    <h2 class="display-4 fw-bold">50+</h2>
                    <p class="mb-0 text-uppercase ls-1">Enfants accompagnés</p>
                </div>
                <div class="col-md-4 mb-3" data-aos="flip-left" data-aos-delay="400">
                    <h2 class="display-4 fw-bold">2020</h2>
                    <p class="mb-0 text-uppercase ls-1">Année de création</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-5 my-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="fw-bold text-primary mb-4">Qui sommes-nous ?</h2>
            <p class="lead text-muted">
                L'association <strong>Aujourd'hui vers Demain</strong> est née d'une volonté simple : celle des habitants de Noisy-le-Sec de s'entraider pour construire un avenir meilleur.
            </p>
            <p>
                Depuis notre création en 2020, nous agissons au cœur des quartiers Langevin et de la Boissière. Ce qui nous anime ? La conviction que la solidarité locale est le moteur le plus puissant pour l'épanouissement des jeunes et le renforcement des liens entre voisins.
            </p>
            <p class="mb-4">
                Que ce soit à travers l'aide aux devoirs, nos ateliers de français ou nos événements festifs, chaque action est pensée pour et par les habitants.
            </p>
            <a href="actions.php" class="btn btn-outline-primary rounded-pill px-4">Découvrir nos actions</a>
        </div>
        <div class="col-lg-6" data-aos="zoom-in">
            <div class="position-relative">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                     alt="Équipe association" 
                     class="img-fluid rounded-4 shadow-lg">
                <div class="position-absolute bottom-0 start-0 bg-warning p-3 m-3 rounded-3 shadow animate__animated animate__pulse animate__infinite">
                    <span class="fw-bold">❤️ Engagement local</span>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="container my-5" id="events">
        <h2 class="text-center mb-5 text-primary fw-bold" data-aos="fade-up">
            <span class="border-bottom border-3 border-primary pb-2">Nos Actualités & Événements</span>
        </h2>
        
        <?php if (count($events) > 0): ?>
            <div class="row">
                <?php foreach ($events as $index => $event): ?>
                    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                        <div class="card h-100 shadow border-0 card-event">
                            <?php if (!empty($event['image']) && file_exists('uploads/' . $event['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($event['image']) ?>" class="card-img-top" alt="Event" style="height: 220px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-header bg-primary text-white text-center py-5"><h1 class="mb-0 display-4">📅</h1></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="badge bg-warning text-dark mb-2"><?= date('d/m/Y', strtotime($event['date_evenement'])) ?></span>
                                <h5 class="card-title fw-bold mt-2"><?= htmlspecialchars($event['titre']) ?></h5>
                                <p class="text-muted small mb-3">📍 <?= htmlspecialchars($event['lieu']) ?></p>
                                <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>