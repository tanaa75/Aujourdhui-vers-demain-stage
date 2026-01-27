<?php
/**
 * ===========================================
 * PAGE GALERIE PHOTOS - VERSION DYNAMIQUE
 * ===========================================
 * 
 * Cette page affiche toutes les photos :
 * - Photos de la table 'photos' (galerie indépendante)
 * - Photos des événements (table 'evenements')
 * 
 * Fonctionnalités :
 * - Filtrage par catégorie (onglets)
 * - Tri par date (récent/ancien)
 * - Effet lightbox au clic
 * - Design responsive
 */

session_start();
require_once 'db.php';

// ========== FILTRES ET TRI ==========
$categorie_filtre = isset($_GET['cat']) ? $_GET['cat'] : '';
$tri = isset($_GET['tri']) ? $_GET['tri'] : 'recent';

// Récupérer les photos de la galerie
$sql_photos = "SELECT id, titre, image, categorie, date_ajout as date_photo, 'galerie' as source FROM photos";
if ($categorie_filtre && $categorie_filtre != 'evenements') {
    $sql_photos .= " WHERE categorie = :cat";
}

// Récupérer les photos des événements
$sql_events = "SELECT id, titre, image, 'Événements' as categorie, date_evenement as date_photo, 'evenement' as source 
               FROM evenements WHERE image IS NOT NULL AND image != ''";

// Combiner les requêtes selon le filtre
if ($categorie_filtre == 'evenements') {
    // Seulement les événements
    $stmt = $pdo->query($sql_events);
    $all_photos = $stmt->fetchAll();
} elseif ($categorie_filtre) {
    // Seulement une catégorie de la galerie
    $stmt = $pdo->prepare($sql_photos);
    $stmt->execute(['cat' => $categorie_filtre]);
    $all_photos = $stmt->fetchAll();
} else {
    // Toutes les photos (galerie + événements)
    $photos_galerie = $pdo->query($sql_photos)->fetchAll();
    $photos_events = $pdo->query($sql_events)->fetchAll();
    $all_photos = array_merge($photos_galerie, $photos_events);
}

// Tri par date
usort($all_photos, function($a, $b) use ($tri) {
    $dateA = strtotime($a['date_photo']);
    $dateB = strtotime($b['date_photo']);
    return $tri == 'recent' ? $dateB - $dateA : $dateA - $dateB;
});

// Récupérer toutes les catégories disponibles
$categories = $pdo->query("SELECT DISTINCT categorie FROM photos ORDER BY categorie")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie Photos - Aujourd'hui vers Demain</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="mobile-responsive.css">
    
    <style>
        body { transition: background-color 0.5s, color 0.5s; }
        
        /* HEADER DE LA GALERIE */
        .gallery-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .gallery-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 30px 30px;
            animation: moveBackground 20s linear infinite;
        }
        
        @keyframes moveBackground {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(30px) translateY(30px); }
        }
        
        .gallery-header h1, .gallery-header p {
            position: relative;
            z-index: 2;
        }
        
        /* FILTRES */
        .filter-bar {
            background: var(--bs-body-bg);
            padding: 20px 0;
            position: sticky;
            top: 70px;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filter-pills .btn {
            border-radius: 50px;
            padding: 8px 20px;
            margin: 0 5px 10px 0;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .filter-pills .btn:hover {
            transform: translateY(-2px);
        }
        
        .sort-select {
            border-radius: 50px;
            padding: 8px 20px;
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .sort-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }
        
        /* CARTES PHOTO */
        .photo-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
        }
        
        .photo-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
        }
        
        .photo-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .photo-card:hover img {
            transform: scale(1.1);
        }
        
        .photo-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.85));
            padding: 60px 20px 20px;
            color: white;
            transform: translateY(100%);
            transition: transform 0.4s ease;
        }
        
        .photo-card:hover .photo-overlay {
            transform: translateY(0);
        }
        
        .photo-badge {
            position: absolute;
            top: 15px;
            left: 15px;
        }
        
        .photo-source {
            position: absolute;
            top: 15px;
            right: 15px;
        }
        
        .photo-date {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        /* LIGHTBOX */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }
        
        .lightbox.active {
            display: flex;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .lightbox img {
            max-width: 90%;
            max-height: 85vh;
            border-radius: 12px;
            box-shadow: 0 0 50px rgba(255,255,255,0.1);
        }
        
        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 30px;
            font-size: 2.5rem;
            color: white;
            cursor: pointer;
            transition: transform 0.3s;
            background: rgba(0,0,0,0.5);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .lightbox-close:hover {
            transform: rotate(90deg);
            background: rgba(255,255,255,0.2);
        }
        
        .lightbox-info {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-align: center;
            background: rgba(0,0,0,0.7);
            padding: 15px 30px;
            border-radius: 12px;
        }
        
        /* COMPTEUR DE PHOTOS */
        .photo-count {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
        }
        
        /* MODE SOMBRE */
        [data-bs-theme="dark"] .photo-card {
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }
        
        [data-bs-theme="dark"] .filter-bar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        [data-bs-theme="dark"] .sort-select {
            border-color: #495057;
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
        }
        
        /* MESSAGE SI AUCUNE PHOTO */
        .no-photos {
            text-align: center;
            padding: 100px 20px;
        }
        
        .no-photos i {
            font-size: 5rem;
            opacity: 0.3;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .gallery-header {
                padding: 50px 0;
            }
            
            .photo-card img {
                height: 200px;
            }
            
            .photo-overlay {
                transform: translateY(0);
                padding: 40px 15px 15px;
            }
            
            .filter-bar {
                top: 60px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Header -->
    <div class="gallery-header">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">
                <i class="bi bi-images me-3"></i>Galerie Photos
            </h1>
            <p class="lead opacity-75 mb-4">Revivez nos moments forts en images</p>
            <span class="photo-count">
                <i class="bi bi-camera-fill me-2"></i><?= count($all_photos) ?> photo<?= count($all_photos) > 1 ? 's' : '' ?>
            </span>
        </div>
    </div>
    
    <!-- Barre de filtres -->
    <div class="filter-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7">
                    <div class="filter-pills">
                        <a href="galerie.php" class="btn <?= $categorie_filtre == '' ? 'btn-primary' : 'btn-outline-primary' ?>">
                            <i class="bi bi-grid-3x3-gap me-1"></i>Toutes
                        </a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="galerie.php?cat=<?= urlencode($cat) ?>&tri=<?= $tri ?>" 
                               class="btn <?= $categorie_filtre == $cat ? 'btn-primary' : 'btn-outline-primary' ?>">
                                <?= htmlspecialchars($cat) ?>
                            </a>
                        <?php endforeach; ?>
                        <a href="galerie.php?cat=evenements&tri=<?= $tri ?>" 
                           class="btn <?= $categorie_filtre == 'evenements' ? 'btn-warning' : 'btn-outline-warning' ?>">
                            <i class="bi bi-calendar-event me-1"></i>Événements
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5 text-md-end mt-3 mt-md-0">
                    <select class="sort-select" onchange="window.location.href=this.value">
                        <option value="galerie.php?cat=<?= urlencode($categorie_filtre) ?>&tri=recent" <?= $tri == 'recent' ? 'selected' : '' ?>>
                            📅 Plus récentes
                        </option>
                        <option value="galerie.php?cat=<?= urlencode($categorie_filtre) ?>&tri=ancien" <?= $tri == 'ancien' ? 'selected' : '' ?>>
                            📅 Plus anciennes
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Galerie -->
    <div class="container py-5">
        <?php if (count($all_photos) > 0): ?>
            <div class="row g-4">
                <?php foreach ($all_photos as $index => $photo): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= min($index * 50, 300) ?>">
                        <div class="photo-card" onclick="openLightbox('uploads/<?= htmlspecialchars($photo['image']) ?>', '<?= htmlspecialchars(addslashes($photo['titre'])) ?>', '<?= date('d/m/Y', strtotime($photo['date_photo'])) ?>')">
                            <img src="uploads/<?= htmlspecialchars($photo['image']) ?>" alt="<?= htmlspecialchars($photo['titre']) ?>">
                            
                            <!-- Badge catégorie -->
                            <span class="photo-badge badge bg-primary"><?= htmlspecialchars($photo['categorie']) ?></span>
                            
                            <!-- Badge source (événement ou galerie) -->
                            <?php if ($photo['source'] == 'evenement'): ?>
                                <span class="photo-source badge bg-warning text-dark">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                            <?php endif; ?>
                            
                            <div class="photo-overlay">
                                <h5 class="mb-1"><?= htmlspecialchars($photo['titre']) ?></h5>
                                <span class="photo-date">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= date('d F Y', strtotime($photo['date_photo'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-photos">
                <i class="bi bi-camera"></i>
                <h3 class="mt-4 text-muted">Aucune photo trouvée</h3>
                <p class="text-muted">
                    <?php if ($categorie_filtre): ?>
                        Aucune photo dans cette catégorie.
                    <?php else: ?>
                        Les photos apparaîtront ici une fois ajoutées.
                    <?php endif; ?>
                </p>
                <a href="galerie.php" class="btn btn-primary rounded-pill mt-3">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Voir toutes les photos
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <span class="lightbox-close" onclick="closeLightbox()">
            <i class="bi bi-x"></i>
        </span>
        <img src="" id="lightbox-img" alt="Photo agrandie">
        <div class="lightbox-info">
            <h4 id="lightbox-title" class="mb-1"></h4>
            <p id="lightbox-date" class="mb-0 opacity-75"></p>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="script_theme.js"></script>
    <script>
        // Initialisation AOS
        AOS.init({ duration: 800, once: true });
        
        // Lightbox
        function openLightbox(src, title, date) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox-title').textContent = title;
            document.getElementById('lightbox-date').textContent = date;
            document.getElementById('lightbox').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });
        
        // Fermer en cliquant en dehors
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) closeLightbox();
        });
    </script>
</body>
</html>
