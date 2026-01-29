<?php 
/**
 * ===========================================
 * PAGE MENTIONS LÉGALES
 * ===========================================
 * 
 * Page obligatoire pour tout site web.
 * Contient les informations légales de l'association.
 * 
 * SECTIONS :
 * 1. Éditeur du site
 * 2. Hébergement
 * 3. Propriété intellectuelle
 * 4. Données personnelles
 * 5. Cookies
 * 6. Droit applicable
 * 
 * DESIGN :
 * - Cartes numérotées avec hover
 * - Adaptation mode clair/sombre
 */
session_start(); 
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Mentions Légales - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <style>
        /* PAGE MENTIONS LÉGALES - DESIGN MODERNE */
        .legal-page {
            background: var(--bs-body-bg);
            min-height: 100vh;
        }
        
        .legal-header {
            position: relative;
            padding: 60px 0 40px;
            overflow: hidden;
        }
        
        .legal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image: radial-gradient(circle at 20% 50%, rgba(13, 110, 253, 0.3) 0%, transparent 50%);
        }
        
        .legal-title {
            position: relative;
            z-index: 2;
        }
        
        .legal-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        }
        
        .legal-section {
            margin-bottom: 30px;
            padding: 30px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .legal-section:hover {
            transform: translateY(-5px);
        }
        
        .legal-section-number {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .legal-section-title {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .legal-info-item {
            padding: 12px 0;
            border-bottom: 1px solid;
            transition: all 0.3s ease;
        }
        
        .legal-info-item:last-child {
            border-bottom: none;
        }
        
        .legal-info-label {
            font-weight: 600;
            margin-right: 8px;
        }
        
        /* MODE CLAIR */
        [data-bs-theme="light"] .legal-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        [data-bs-theme="light"] .legal-title {
            color: #0d6efd;
        }
        
        [data-bs-theme="light"] .legal-subtitle {
            color: #6c757d;
        }
        
        [data-bs-theme="light"] .legal-section {
            background: #ffffff;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="light"] .legal-section:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        [data-bs-theme="light"] .legal-section-number {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: #ffffff;
        }
        
        [data-bs-theme="light"] .legal-section-title {
            color: #212529;
        }
        
        [data-bs-theme="light"] .legal-section p {
            color: #495057;
        }
        
        [data-bs-theme="light"] .legal-info-item {
            border-color: #e9ecef;
        }
        
        [data-bs-theme="light"] .legal-info-label {
            color: #0d6efd;
        }
        
        /* MODE SOMBRE */
        [data-bs-theme="dark"] .legal-header {
            background: linear-gradient(135deg, #1a1d20 0%, #2d3238 100%);
        }
        
        [data-bs-theme="dark"] .legal-title {
            color: #ffc107;
        }
        
        [data-bs-theme="dark"] .legal-subtitle {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .legal-section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        [data-bs-theme="dark"] .legal-section:hover {
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }
        
        [data-bs-theme="dark"] .legal-section-number {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #212529;
        }
        
        [data-bs-theme="dark"] .legal-section-title {
            color: #f8f9fa;
        }
        
        [data-bs-theme="dark"] .legal-section p {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .legal-info-item {
            border-color: rgba(255, 255, 255, 0.1);
        }
        
        [data-bs-theme="dark"] .legal-info-label {
            color: #ffc107;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 legal-page">
    
    <?php include '../includes/navbar.php'; ?>

    <!-- Header -->
    <div class="legal-header">
        <div class="container">
            <div class="text-center legal-title">
                <h1 class="display-4 fw-bold mb-3">Mentions Légales</h1>
                <p class="lead legal-subtitle">Informations légales et réglementaires</p>
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Section 1 : Éditeur -->
                <div class="legal-section" data-aos="fade-up">
                    <div class="d-flex align-items-start">
                        <div class="legal-section-number">1</div>
                        <div class="flex-grow-1">
                            <h4 class="legal-section-title">Éditeur du site</h4>
                            <p class="mb-3">
                                Le site internet <strong>Aujourd'hui vers Demain</strong> est édité par l'association régie par la loi du 1er juillet 1901.
                            </p>
                            <div class="legal-info-item">
                                <span class="legal-info-label">Nom de l'association :</span>
                                <span>Aujourd'hui vers Demain</span>
                            </div>
                            <div class="legal-info-item">
                                <span class="legal-info-label">Siège social :</span>
                                <span>116 rue de l'Avenir, 93130 Noisy-le-Sec</span>
                            </div>
                            <div class="legal-info-item">
                                <span class="legal-info-label">Email :</span>
                                <span>contact@asso.fr</span>
                            </div>
                            <div class="legal-info-item">
                                <span class="legal-info-label">Téléphone :</span>
                                <span>01 23 45 67 89</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2 : Directeur -->
                <div class="legal-section" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex align-items-start">
                        <div class="legal-section-number">2</div>
                        <div class="flex-grow-1">
                            <h4 class="legal-section-title">Directeur de la publication</h4>
                            <p class="mb-0">
                                Le directeur de la publication est le Président de l'association.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 3 : Hébergement -->
                <div class="legal-section" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex align-items-start">
                        <div class="legal-section-number">3</div>
                        <div class="flex-grow-1">
                            <h4 class="legal-section-title">Hébergement</h4>
                            <p class="mb-3">Ce site est hébergé par :</p>
                            <div class="legal-info-item">
                                <span class="legal-info-label">Hébergeur :</span>
                                <span>OVH (exemple)</span>
                            </div>
                            <div class="legal-info-item">
                                <span class="legal-info-label">Adresse :</span>
                                <span>2 rue Kellermann, 59100 Roubaix - France</span>
                            </div>
                            <div class="legal-info-item">
                                <span class="legal-info-label">Site web :</span>
                                <a href="https://www.ovh.com" target="_blank" class="text-decoration-none">www.ovh.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4 : Propriété intellectuelle -->
                <div class="legal-section" data-aos="fade-up" data-aos-delay="300">
                    <div class="d-flex align-items-start">
                        <div class="legal-section-number">4</div>
                        <div class="flex-grow-1">
                            <h4 class="legal-section-title">Propriété intellectuelle</h4>
                            <p class="mb-0">
                                L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 5 : Données personnelles -->
                <div class="legal-section" data-aos="fade-up" data-aos-delay="400">
                    <div class="d-flex align-items-start">
                        <div class="legal-section-number">5</div>
                        <div class="flex-grow-1">
                            <h4 class="legal-section-title">Protection des données personnelles</h4>
                            <p class="mb-0">
                                Conformément à la loi "Informatique et Libertés" du 6 janvier 1978 modifiée et au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un droit d'accès, de rectification et de suppression des données vous concernant. Pour exercer ce droit, veuillez nous contacter à l'adresse : <strong>contact@asso.fr</strong>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../assets/js/script_theme.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>