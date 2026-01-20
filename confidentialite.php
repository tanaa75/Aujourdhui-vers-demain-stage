<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Politique de Confidentialité - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <style>
        /* PAGE CONFIDENTIALITÉ - DESIGN MODERNE */
        .privacy-page {
            background: var(--bs-body-bg);
            min-height: 100vh;
        }
        
        .privacy-header {
            position: relative;
            padding: 60px 0 40px;
            overflow: hidden;
        }
        
        .privacy-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image: radial-gradient(circle at 80% 50%, rgba(25, 135, 84, 0.3) 0%, transparent 50%);
        }
        
        .privacy-title {
            position: relative;
            z-index: 2;
        }
        
        .privacy-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #198754, #157347);
            color: white;
            box-shadow: 0 8px 20px rgba(25, 135, 84, 0.3);
        }
        
        .privacy-section {
            margin-bottom: 30px;
            padding: 30px;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .privacy-section:hover {
            transform: translateY(-5px);
        }
        
        .privacy-section-number {
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
        
        .privacy-section-title {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .privacy-list {
            list-style: none;
            padding-left: 0;
        }
        
        .privacy-list li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
        }
        
        .privacy-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .privacy-alert {
            padding: 15px 20px;
            border-radius: 12px;
            border-left: 4px solid;
            margin-top: 15px;
        }
        
        /* MODE CLAIR */
        [data-bs-theme="light"] .privacy-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        [data-bs-theme="light"] .privacy-title {
            color: #198754;
        }
        
        [data-bs-theme="light"] .privacy-subtitle {
            color: #6c757d;
        }
        
        [data-bs-theme="light"] .privacy-section {
            background: #ffffff;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        [data-bs-theme="light"] .privacy-section:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        [data-bs-theme="light"] .privacy-section-number {
            background: linear-gradient(135deg, #198754, #157347);
            color: #ffffff;
        }
        
        [data-bs-theme="light"] .privacy-section-title {
            color: #212529;
        }
        
        [data-bs-theme="light"] .privacy-section p {
            color: #495057;
        }
        
        [data-bs-theme="light"] .privacy-list li::before {
            color: #198754;
        }
        
        [data-bs-theme="light"] .privacy-alert {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        
        /* MODE SOMBRE */
        [data-bs-theme="dark"] .privacy-header {
            background: linear-gradient(135deg, #1a1d20 0%, #2d3238 100%);
        }
        
        [data-bs-theme="dark"] .privacy-title {
            color: #198754;
        }
        
        [data-bs-theme="dark"] .privacy-subtitle {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .privacy-section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        [data-bs-theme="dark"] .privacy-section:hover {
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }
        
        [data-bs-theme="dark"] .privacy-section-number {
            background: linear-gradient(135deg, #198754, #157347);
            color: #ffffff;
        }
        
        [data-bs-theme="dark"] .privacy-section-title {
            color: #f8f9fa;
        }
        
        [data-bs-theme="dark"] .privacy-section p {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .privacy-list li::before {
            color: #198754;
        }
        
        [data-bs-theme="dark"] .privacy-alert {
            background: rgba(255, 193, 7, 0.15);
            border-color: #ffc107;
            color: #ffc107;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 privacy-page">
    
    <?php include 'navbar.php'; ?>

    <!-- Header -->
    <div class="privacy-header">
        <div class="container">
            <div class="text-center privacy-title">
                <h1 class="display-4 fw-bold mb-3">Politique de Confidentialité</h1>
                <p class="lead privacy-subtitle">Protection de vos données personnelles</p>
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Introduction -->
                <div class="privacy-section" data-aos="fade-up">
                    <p class="lead mb-0">
                        L'association <strong>Aujourd'hui vers Demain</strong> s'engage à protéger la vie privée de ses membres et utilisateurs conformément au Règlement Général sur la Protection des Données (RGPD).
                    </p>
                </div>

                <!-- Section 1 : Données collectées -->
                <div class="privacy-section" data-aos="fade-up" data-aos-delay="100">
                    <div class="d-flex align-items-start">
                        <div class="privacy-section-number">1</div>
                        <div class="flex-grow-1">
                            <h4 class="privacy-section-title">Les données que nous collectons</h4>
                            <p class="mb-3">
                                Dans le cadre de nos activités (aide aux devoirs, bénévolat, contact), nous sommes amenés à collecter les informations suivantes via nos formulaires :
                            </p>
                            <ul class="privacy-list">
                                <li><strong>Identité :</strong> Nom, Prénom, Classe de l'enfant</li>
                                <li><strong>Coordonnées :</strong> Adresse email, Numéro de téléphone</li>
                                <li><strong>Professionnel :</strong> CV pour les candidatures bénévoles</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Section 2 : Utilisation -->
                <div class="privacy-section" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex align-items-start">
                        <div class="privacy-section-number">2</div>
                        <div class="flex-grow-1">
                            <h4 class="privacy-section-title">Utilisation des données</h4>
                            <p class="mb-3">Vos données sont utilisées exclusivement pour :</p>
                            <ul class="privacy-list">
                                <li>Gérer les inscriptions à l'aide aux devoirs</li>
                                <li>Traiter les candidatures de bénévolat</li>
                                <li>Répondre à vos demandes de contact</li>
                                <li>Vous envoyer des informations sur la vie de l'association (si vous l'avez accepté)</li>
                            </ul>
                            <div class="privacy-alert">
                                <strong>⚠️ Important :</strong> Nous ne vendons ni ne louons jamais vos données à des tiers.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3 : Durée -->
                <div class="privacy-section" data-aos="fade-up" data-aos-delay="300">
                    <div class="d-flex align-items-start">
                        <div class="privacy-section-number">3</div>
                        <div class="flex-grow-1">
                            <h4 class="privacy-section-title">Durée de conservation</h4>
                            <p class="mb-0">
                                Les données sont conservées uniquement le temps nécessaire à la réalisation des finalités citées ci-dessus, et pour une durée maximale de <strong>3 ans</strong> après le dernier contact.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 4 : Sécurité -->
                <div class="privacy-section" data-aos="fade-up" data-aos-delay="400">
                    <div class="d-flex align-items-start">
                        <div class="privacy-section-number">4</div>
                        <div class="flex-grow-1">
                            <h4 class="privacy-section-title">Sécurité</h4>
                            <p class="mb-0">
                                Nous mettons en œuvre des mesures de sécurité techniques (mots de passe hachés, accès sécurisé à l'administration) pour protéger vos données contre tout accès non autorisé.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 5 : Vos droits -->
                <div class="privacy-section" data-aos="fade-up" data-aos-delay="500">
                    <div class="d-flex align-items-start">
                        <div class="privacy-section-number">5</div>
                        <div class="flex-grow-1">
                            <h4 class="privacy-section-title">Vos droits</h4>
                            <p class="mb-3">
                                Conformément à la loi, vous disposez d'un droit d'accès, de rectification et de suppression de vos données.
                            </p>
                            <p class="mb-0">
                                Pour exercer ce droit, contactez-nous par mail à : <strong>contact@asso.fr</strong> ou par courrier au 116 rue de l'Avenir, 93130 Noisy-le-Sec.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="script_theme.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>