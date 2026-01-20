<?php
/**
 * ===========================================
 * PAGE GALERIE PHOTOS
 * ===========================================
 * 
 * Cette page affiche toutes les images des événements
 * passés dans une galerie moderne avec effet lightbox.
 * 
 * Les images sont récupérées depuis la table 'evenements'
 * où le champ 'image' contient le nom du fichier.
 */

session_start();
require_once 'db.php';

// Récupérer tous les événements qui ont une image
$stmt = $pdo->query("SELECT id, titre, date_evenement, image FROM evenements WHERE image IS NOT NULL AND image != '' ORDER BY date_evenement DESC");
$evenements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie Photos - Aujourd'hui vers Demain</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
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
        
        .gallery-header h1 {
            position: relative;
            z-index: 2;
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
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            padding: 60px 20px 20px;
            color: white;
            transform: translateY(100%);
            transition: transform 0.4s ease;
        }
        
        .photo-card:hover .photo-overlay {
            transform: translateY(0);
        }
        
        .photo-date {
            font-size: 0.8rem;
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
            max-height: 90vh;
            border-radius: 8px;
            box-shadow: 0 0 50px rgba(255,255,255,0.1);
        }
        
        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 30px;
            font-size: 2rem;
            color: white;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .lightbox-close:hover {
            transform: rotate(90deg);
        }
        
        .lightbox-info {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-align: center;
        }
        
        /* MODE SOMBRE */
        [data-bs-theme="dark"] .photo-card {
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
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
            <p class="lead opacity-75">Revivez nos moments forts en images</p>
        </div>
    </div>
    
    <!-- Galerie -->
    <div class="container py-5">
        <?php if (count($evenements) > 0): ?>
            <div class="row g-4">
                <?php foreach ($evenements as $index => $event): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                        <div class="photo-card" onclick="openLightbox('uploads/<?= htmlspecialchars($event['image']) ?>', '<?= htmlspecialchars($event['titre']) ?>', '<?= date('d/m/Y', strtotime($event['date_evenement'])) ?>')">
                            <img src="uploads/<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['titre']) ?>">
                            <div class="photo-overlay">
                                <h5 class="mb-1"><?= htmlspecialchars($event['titre']) ?></h5>
                                <span class="photo-date">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= date('d F Y', strtotime($event['date_evenement'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-photos">
                <i class="bi bi-camera"></i>
                <h3 class="mt-4 text-muted">Aucune photo pour le moment</h3>
                <p class="text-muted">Les photos des événements apparaîtront ici.</p>
                <a href="index.php" class="btn btn-primary rounded-pill mt-3">
                    <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <img src="" id="lightbox-img" alt="Photo agrandie">
        <div class="lightbox-info">
            <h4 id="lightbox-title"></h4>
            <p id="lightbox-date" class="opacity-75"></p>
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
