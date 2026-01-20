<?php
/**
 * ===========================================
 * PAGE D'ACCUEIL - AUJOURD'HUI VERS DEMAIN
 * ===========================================
 * 
 * Fichier principal du site de l'association.
 * Affiche toutes les sections importantes.
 * 
 * SECTIONS :
 * - Hero Banner (Bannière d'accueil)
 * - Statistiques animées (Bénévoles, Enfants, Création)
 * - Qui sommes-nous ? (Présentation)
 * - Nos Actions (Aide aux devoirs + formulaire inscription)
 * - Rejoignez l'équipe (Bénévolat + formulaire candidature)
 * - Nos Actualités (Événements + recherche)
 * 
 * FORMULAIRES :
 * - Inscription aide aux devoirs → envoi vers table 'messages'
 * - Candidature bénévole → envoi vers table 'messages' + upload CV
 * 
 * SÉCURITÉ :
 * - Formulaires accessibles uniquement aux utilisateurs connectés
 * - Upload CV : vérification type et taille
 */

// Démarrage de la session
session_start(); 

// Connexion à la base de données
require_once 'db.php';

// ========== VARIABLES DE SUIVI ==========
$inscription_ok = false;   // Inscription devoirs réussie ?
$benevole_ok = false;      // Candidature bénévole réussie ?
$error_msg = "";           // Message d'erreur éventuel

// Vérification si un utilisateur est connecté (membre OU admin)
$est_connecte = (isset($_SESSION['membre_id']) || isset($_SESSION['user_id']));

// ========== TRAITEMENT DES FORMULAIRES ==========
// On ne traite les formulaires QUE si l'utilisateur est connecté
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $est_connecte) {
    
    // ------------------------------------------
    // CAS 1 : INSCRIPTION AIDE AUX DEVOIRS
    // ------------------------------------------
    if ($_POST['form_type'] == 'devoirs') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $classe = $_POST['classe'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        
        // Construction du message formaté
        $msg = "🔔 INSCRIPTION AIDE AUX DEVOIRS\n\nEnfant : $nom $prenom\nClasse : $classe\nTéléphone : $tel\nEmail parent : $email";
        
        // Insertion en base de données
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute(["Parent de $prenom", $email, $msg]);
        $inscription_ok = true;
    }

    // ------------------------------------------
    // CAS 2 : CANDIDATURE BÉNÉVOLE
    // ------------------------------------------
    if ($_POST['form_type'] == 'benevolat') {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $dispo = $_POST['dispo'];
        $skills = $_POST['skills'];
        
        $lien_cv = "Aucun CV fourni";
        
        // Gestion de l'upload du CV
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            // Vérification taille (max 5 Mo)
            if ($_FILES['cv']['size'] <= 5000000) {
                $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
                
                // Vérification extension
                if (in_array($ext, ['pdf', 'doc', 'docx', 'jpg', 'png'])) {
                    // Renommage sécurisé
                    $newname = 'cv_' . preg_replace('/[^a-zA-Z0-9]/', '', $nom) . '_' . time() . '.' . $ext;
                    
                    // Déplacement dans le dossier uploads
                    if(move_uploaded_file($_FILES['cv']['tmp_name'], 'uploads/' . $newname)) {
                        $lien_cv = "📄 TÉLÉCHARGER LE CV : http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/uploads/" . $newname;
                    }
                } else { 
                    $error_msg = "Format invalide."; 
                }
            } else { 
                $error_msg = "Fichier trop lourd."; 
            }
        }

        // Si pas d'erreur, on enregistre la candidature
        if (empty($error_msg)) {
            $msg = "❤️ NOUVEAU BÉNÉVOLE !\n\nNom : $nom\nEmail : $email\nTéléphone : $tel\n\nDispos : $dispo\nAime faire : $skills\n\n$lien_cv";
            $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $msg]);
            $benevole_ok = true;
        }
    }
}

// ========== PRÉ-REMPLISSAGE DES FORMULAIRES ==========
// Si l'utilisateur est un membre connecté, on récupère ses infos
$nom_user = isset($_SESSION['membre_nom']) ? $_SESSION['membre_nom'] : "";
$email_user = isset($_SESSION['membre_email']) ? $_SESSION['membre_email'] : "";

// ========== RÉCUPÉRATION DES ÉVÉNEMENTS ==========
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Recherche par mot-clé
    $search = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT * FROM evenements WHERE titre LIKE :s OR description LIKE :s OR lieu LIKE :s ORDER BY date_evenement DESC");
    $stmt->execute(['s' => "%$search%"]);
    $events = $stmt->fetchAll();
} else {
    // Tous les événements
    $events = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC")->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png">
    <style>
        body { transition: background-color 0.5s; }
        .hero-banner { background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-attachment: fixed; color: white; padding: 150px 0; }
        
        /* CSS POUR LES CARTES QUI BOUGENT */
        .card-event:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important; }
        
        .hover-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
        }
        .hover-card:hover {
            transform: translateY(-15px); /* Monte vers le haut */
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important; /* Ombre plus forte */
            background-color: #fff;
        }
        .hover-card .emoji-icon {
            display: inline-block;
            transition: transform 0.3s;
        }
        .hover-card:hover .emoji-icon {
            transform: scale(1.3) rotate(10deg); /* L'emoji grossit et tourne */
        }
        
        /* ANIMATIONS STATISTIQUES */
        .stat-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .hover-stat:hover {
            transform: translateY(-15px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.4) !important;
        }
        
        .stat-icon {
            transition: transform 0.4s ease;
        }
        
        .hover-stat:hover .stat-icon {
            transform: scale(1.2) rotate(360deg);
        }
        
        .counter {
            display: inline-block;
            font-variant-numeric: tabular-nums;
        }
        
        .letter-spacing-wide {
            letter-spacing: 2px;
            font-weight: 600;
        }
        
        /* Formes flottantes décoratives */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            animation: float 6s ease-in-out infinite;
        }
        
        .shape-1 {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .shape-3 {
            width: 80px;
            height: 80px;
            bottom: 15%;
            left: 15%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.6;
            }
        }
        
        /* Animation pulse pour les icônes */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        
        .stat-icon span {
            display: inline-block;
            animation: pulse 2s ease-in-out infinite;
        }
        
        /* SECTION BÉNÉVOLAT */
        .need-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        
        .need-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }
        
        .need-card .rounded-circle {
            transition: transform 0.3s ease;
        }
        
        .need-card:hover .rounded-circle {
            transform: rotate(360deg) scale(1.1);
        }
        
        .volunteer-form-card {
            transition: transform 0.3s ease;
        }
        
        .volunteer-form-card:hover {
            transform: translateY(-5px);
        }
        
        .volunteer-form-card .card-header {
            position: relative;
            overflow: hidden;
        }
        
        .volunteer-form-card .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
            50% { transform: translate(-30%, -30%) rotate(180deg); }
        }
        
        /* ADAPTATION MODE SOMBRE POUR SECTION BÉNÉVOLAT */
        .benevolat-section {
            background: var(--bs-body-bg);
        }
        
        /* Overlay avec gradient adaptatif */
        .benevolat-bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.4;
            background-image: radial-gradient(circle at 20% 50%, rgba(13, 110, 253, 0.15) 0%, transparent 50%), 
                              radial-gradient(circle at 80% 80%, rgba(255, 193, 7, 0.15) 0%, transparent 50%);
        }
        
        /* Mode clair */
        [data-bs-theme="light"] .benevolat-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        [data-bs-theme="light"] .benevolat-text {
            color: #6c757d !important;
        }
        
        [data-bs-theme="light"] .benevolat-alert {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        [data-bs-theme="light"] .benevolat-alert-text {
            color: #6c757d !important;
        }
        
        [data-bs-theme="light"] .need-card {
            background-color: #ffffff !important;
            color: #212529;
        }
        
        [data-bs-theme="light"] .need-card-text {
            color: #6c757d !important;
        }
        
        [data-bs-theme="light"] .benevolat-footer-text {
            color: #6c757d !important;
        }
        
        /* Mode sombre */
        [data-bs-theme="dark"] .benevolat-section {
            background: linear-gradient(135deg, #1a1d20 0%, #2d3238 100%);
        }
        
        [data-bs-theme="dark"] .benevolat-text {
            color: #adb5bd !important;
        }
        
        [data-bs-theme="dark"] .benevolat-alert {
            background-color: rgba(13, 110, 253, 0.15);
            color: #f8f9fa;
            border-color: #0d6efd !important;
        }
        
        [data-bs-theme="dark"] .benevolat-alert-text {
            color: #adb5bd !important;
        }
        
        [data-bs-theme="dark"] .need-card {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #f8f9fa;
            backdrop-filter: blur(10px);
        }
        
        [data-bs-theme="dark"] .need-card:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        [data-bs-theme="dark"] .need-card-text {
            color: #adb5bd !important;
        }
        
        [data-bs-theme="dark"] .benevolat-footer-text {
            color: #adb5bd !important;
        }
        
        /* HEADER FORMULAIRE - ADAPTATION THÈME */
        /* Mode clair : texte noir sur fond blanc/clair */
        [data-bs-theme="light"] .form-header-title,
        [data-bs-theme="light"] .form-header-subtitle {
            color: #212529 !important;
        }
        
        /* Mode sombre : texte blanc sur fond sombre */
        [data-bs-theme="dark"] .form-header-title,
        [data-bs-theme="dark"] .form-header-subtitle {
            color: #ffffff !important;
        }
        
        /* SECTION ACTIONS - ADAPTATION THÈME */
        .actions-section {
            background: var(--bs-body-bg);
        }
        
        .actions-icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        
        .actions-info-card:hover .actions-icon-wrapper {
            transform: rotate(10deg) scale(1.1);
        }
        
        .info-badge {
            transition: all 0.3s ease;
        }
        
        .info-badge:hover {
            transform: translateY(-3px);
        }
        
        /* Mode clair */
        [data-bs-theme="light"] .actions-subtitle {
            color: #6c757d;
        }
        
        [data-bs-theme="light"] .actions-info-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
        }
        
        [data-bs-theme="light"] .actions-icon-wrapper {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        }
        
        [data-bs-theme="light"] .actions-title {
            color: #212529;
        }
        
        [data-bs-theme="light"] .actions-text {
            color: #6c757d;
        }
        
        [data-bs-theme="light"] .actions-border {
            border-color: #e9ecef !important;
        }
        
        [data-bs-theme="light"] .actions-subtitle-small {
            color: #495057;
        }
        
        [data-bs-theme="light"] .info-badge {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        
        [data-bs-theme="light"] .actions-label {
            color: #6c757d;
        }
        
        [data-bs-theme="light"] .actions-value {
            color: #212529;
        }
        
        /* Mode sombre */
        [data-bs-theme="dark"] .actions-subtitle {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .actions-info-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        [data-bs-theme="dark"] .actions-icon-wrapper {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        }
        
        [data-bs-theme="dark"] .actions-title {
            color: #f8f9fa;
        }
        
        [data-bs-theme="dark"] .actions-text {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .actions-border {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        [data-bs-theme="dark"] .actions-subtitle-small {
            color: #dee2e6;
        }
        
        [data-bs-theme="dark"] .info-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        [data-bs-theme="dark"] .actions-label {
            color: #868e96;
        }
        
        [data-bs-theme="dark"] .actions-value {
            color: #f8f9fa;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include 'navbar.php'; ?>

    <div class="hero-banner text-center">
        <div class="container">
            <h1 class="display-3 fw-bold" data-aos="fade-down">Construire demain, dès aujourd'hui.</h1>
            <p class="lead mb-4" data-aos="fade-in">Au cœur de Noisy-le-Sec, pour l'avenir de nos quartiers.</p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center" data-aos="zoom-in">
                <a href="#actions" class="btn btn-warning btn-lg rounded-pill shadow fw-bold">📚 Aide aux devoirs</a>
                <a href="#benevolat" class="btn btn-outline-light btn-lg rounded-pill">🤝 Devenir Bénévole</a>
            </div>
        </div>
    </div>

    <!-- Section Statistiques Animées -->
    <div class="bg-warning text-dark py-4 text-center fw-bold shadow position-relative overflow-hidden">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row g-3">
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-card p-3 rounded-3 bg-white bg-opacity-25 backdrop-blur h-100 hover-stat">
                        <div class="stat-icon mb-2">
                            <span class="fs-1">🤝</span>
                        </div>
                        <h2 class="display-6 fw-bold mb-1">
                            <span class="counter" data-target="15">0</span>+
                        </h2>
                        <p class="fs-6 mb-0 text-uppercase letter-spacing-wide">Bénévoles</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-card p-3 rounded-3 bg-white bg-opacity-25 backdrop-blur h-100 hover-stat">
                        <div class="stat-icon mb-2">
                            <span class="fs-1">👦</span>
                        </div>
                        <h2 class="display-6 fw-bold mb-1">
                            <span class="counter" data-target="50">0</span>+
                        </h2>
                        <p class="fs-6 mb-0 text-uppercase letter-spacing-wide">Enfants</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-card p-3 rounded-3 bg-white bg-opacity-25 backdrop-blur h-100 hover-stat">
                        <div class="stat-icon mb-2">
                            <span class="fs-1">🎯</span>
                        </div>
                        <h2 class="display-6 fw-bold mb-1">
                            <span class="counter" data-target="2020">2000</span>
                        </h2>
                        <p class="fs-6 mb-0 text-uppercase letter-spacing-wide">Création</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Éléments décoratifs animés -->
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>

    <div class="container py-5 my-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="fw-bold text-primary mb-4">Qui sommes-nous ?</h2>
                <p class="lead text-muted">
                    Plus qu'une simple association, <strong>Aujourd'hui vers Demain</strong> est le fruit d'une solidarité entre voisins. Tout a commencé en 2020, dans les quartiers Langevin et La Boissière, avec une idée simple : on est plus forts ensemble.
                </p>
                <p>
                    Ici, pas de grands discours, mais du concret. Nous sommes des habitants, des parents et des jeunes qui avons décidé de nous bouger pour notre ville. Notre but ? Que chacun trouve sa place, que ce soit par le soutien scolaire pour les plus jeunes ou l'organisation de moments festifs pour tous.
                </p>
                <p class="mb-4">
                    On croit en la force du collectif pour changer les choses, une action à la fois.
                </p>
            </div>
            <div class="col-lg-6" data-aos="zoom-in">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Équipe" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>

    <hr class="container my-5">

    <div class="actions-section py-5" id="actions">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-down">
                
                <h2 class="display-5 fw-bold mb-3">L'Aide aux Devoirs</h2>
                <p class="lead actions-subtitle">Accompagner chaque enfant vers la réussite</p>
            </div>
            
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="actions-info-card p-4 rounded-4 shadow-lg h-100">
                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="actions-icon-wrapper">
                                    <span class="fs-1">✏️</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h3 class="fw-bold mb-3 actions-title">Notre Mission</h3>
                                <p class="actions-text mb-3">
                                    L'école, ce n'est pas toujours facile, et à la maison, on n'a pas toujours le temps ou les clés pour aider. Notre accompagnement ne sert pas juste à "finir les devoirs", mais à <strong>redonner confiance</strong>.
                                </p>
                                <p class="actions-text">
                                    Dans une ambiance calme, nos bénévoles prennent le temps d'expliquer, de réviser les bases et surtout d'apprendre à s'organiser. L'objectif : que chaque enfant reparte fier de son travail et l'esprit plus léger.
                                </p>
                            </div>
                        </div>
                        
                        <div class="border-top actions-border pt-4">
                            <h5 class="fw-bold mb-3 actions-subtitle-small">📋 Informations pratiques</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-badge p-3 rounded-3">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 fs-5">📅</span>
                                            <div>
                                                <small class="d-block text-uppercase fw-semibold actions-label">Jours</small>
                                                <span class="fw-bold actions-value">Lun, Mar, Jeu, Ven</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-badge p-3 rounded-3">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 fs-5">🕒</span>
                                            <div>
                                                <small class="d-block text-uppercase fw-semibold actions-label">Horaires</small>
                                                <span class="fw-bold actions-value">16h30 - 18h00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-badge p-3 rounded-3">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 fs-5">👩‍🏫</span>
                                            <div>
                                                <small class="d-block text-uppercase fw-semibold actions-label">Niveaux</small>
                                                <span class="fw-bold actions-value">Du CP au CM2</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card border-primary shadow">
                    <div class="card-header bg-primary text-white text-center"><h5 class="mb-0">📝 Inscrire mon enfant</h5></div>
                    <div class="card-body">
                        
                        <?php if ($est_connecte): ?>
                            
                            <?php if ($inscription_ok): ?><div class="alert alert-success">✅ Inscription envoyée !</div><?php endif; ?>
                            <form method="POST" action="#actions">
                                <input type="hidden" name="form_type" value="devoirs">
                                <div class="row">
                                    <div class="col-6 mb-3"><label>Nom enfant</label><input type="text" name="nom" class="form-control" required></div>
                                    <div class="col-6 mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
                                </div>
                                <div class="mb-3"><label>Email parent</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email_user) ?>" required></div>
                                <div class="row">
                                    <div class="col-6 mb-3"><label>Classe</label><input type="text" name="classe" class="form-control" placeholder="Ex: CM1" required></div>
                                    <div class="col-6 mb-3"><label>Téléphone</label><input type="tel" name="tel" class="form-control" required></div>
                                </div>
                                <button class="btn btn-warning w-100 fw-bold">Valider l'inscription</button>
                            </form>

                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="mb-3 display-4">🔒</div>
                                <h5 class="fw-bold">Réservé aux membres</h5>
                                <p class="text-muted mb-4">Connectez-vous pour inscrire votre enfant.</p>
                                <div class="d-grid gap-2">
                                    <a href="connexion.php" class="btn btn-primary rounded-pill fw-bold">Se connecter</a>
                                    <a href="inscription.php" class="btn btn-outline-primary rounded-pill fw-bold">Créer un compte</a>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 pt-4">
            <div class="col-12 text-center mb-5" data-aos="fade-up">
                <h3 class="fw-bold text-success">🏘️ Vie de Quartier & Citoyenneté</h3>
                <p class="text-muted">Parce qu'un quartier vivant, c'est l'affaire de tous.</p>
            </div>
            
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="card p-4 shadow-sm border-0 h-100 hover-card text-center">
                    <div class="display-4 mb-3 emoji-icon">🎉</div>
                    <h5 class="fw-bold">Animations Locales</h5>
                    <p class="small text-muted mb-0">Fêtes de quartier, repas partagés et sorties culturelles pour tous.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="card p-4 shadow-sm border-0 h-100 hover-card text-center">
                    <div class="display-4 mb-3 emoji-icon">🗣️</div>
                    <h5 class="fw-bold">Conseil Citoyen</h5>
                    <p class="small text-muted mb-0">Votre voix compte ! Participez aux décisions pour améliorer la vie de la cité.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="card p-4 shadow-sm border-0 h-100 hover-card text-center">
                    <div class="display-4 mb-3 emoji-icon">🤝</div>
                    <h5 class="fw-bold">Médiation Sociale</h5>
                    <p class="small text-muted mb-0">Une oreille attentive pour orienter les familles et résoudre les conflits.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative py-5 overflow-hidden benevolat-section" id="benevolat">
        <!-- Éléments décoratifs -->
        <div class="benevolat-bg-overlay"></div>
        
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="mb-4">
                        <h2 class="display-5 fw-bold mb-3">Rejoignez l'aventure !</h2>
                        <p class="lead benevolat-text mb-4">
                            Devenir bénévole, c'est choisir de consacrer un peu de son temps pour faire une grande différence dans la vie du quartier.
                        </p>
                    </div>
                    
                    <div class="alert benevolat-alert border-start border-primary border-4 shadow-sm mb-4" role="alert">
                        <p class="mb-2 fw-semibold">💡 Aucune expertise requise !</p>
                        <p class="small benevolat-alert-text mb-0">Ce qui compte, c'est votre envie d'être utile et de partager un moment avec la communauté.</p>
                    </div>
                    
                    <h5 class="fw-bold mb-3">
                        <span class="border-bottom border-warning border-3 pb-1">Nos besoins actuels</span>
                    </h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="need-card p-3 rounded-3 border border-2 border-primary h-100 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <span class="fs-4">📚</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Aide aux devoirs</h6>
                                        <p class="small need-card-text mb-0">Accompagnement scolaire</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                            <div class="need-card p-3 rounded-3 border border-2 border-info h-100 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-info bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <span class="fs-4">💻</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Atelier informatique</h6>
                                        <p class="small need-card-text mb-0">Initiation numérique</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                            <div class="need-card p-3 rounded-3 border border-2 border-warning h-100 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-warning bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <span class="fs-4">🎪</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Organisation fêtes</h6>
                                        <p class="small need-card-text mb-0">Événements quartier</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                            <div class="need-card p-3 rounded-3 border border-2 border-success h-100 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <span class="fs-4">🎨</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Ateliers créatifs</h6>
                                        <p class="small need-card-text mb-0">Art & bricolage</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2 benevolat-footer-text">
                        <svg width="20" height="20" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                        </svg>
                        <small class="fw-semibold">Flexible : 1h/semaine ou plus selon vos disponibilités</small>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card shadow-lg border-0 rounded-4 overflow-hidden volunteer-form-card">
                        <div class="card-header bg-gradient text-white text-center py-4 border-0" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                            <div class="mb-2">
                                <span class="fs-1">🚀</span>
                            </div>
                            <h3 class="mb-1 fw-bold form-header-title">Je me lance !</h3>
                            <p class="small mb-0 fw-semibold form-header-subtitle">Remplissez le formulaire ci-dessous</p>
                        </div>
                        <div class="card-body p-4">
                        
                        <?php if ($est_connecte): ?>
                            
                            <?php if ($benevole_ok): ?><div class="alert alert-success">Candidature envoyée !</div><?php endif; ?>
                            <?php if (!empty($error_msg)): ?><div class="alert alert-danger"><?= $error_msg ?></div><?php endif; ?>

                            <form method="POST" action="#benevolat" enctype="multipart/form-data">
                                <input type="hidden" name="form_type" value="benevolat">
                                <div class="mb-3"><label>Nom & Prénom</label><input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom_user) ?>" required></div>
                                <div class="row">
                                    <div class="col-6 mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email_user) ?>" required></div>
                                    <div class="col-6 mb-3"><label>Téléphone</label><input type="tel" name="tel" class="form-control" required></div>
                                </div>
                                <div class="mb-3"><label>CV (PDF/Word)</label><input type="file" name="cv" class="form-control"></div>
                                <div class="mb-3"><label>Disponibilités</label><input type="text" name="dispo" class="form-control" required></div>
                                <div class="mb-3"><textarea name="skills" class="form-control" rows="2" placeholder="Ce que j'aime faire..."></textarea></div>
                                <button class="btn btn-primary w-100 rounded-pill fw-bold">Envoyer</button>
                            </form>

                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3 display-4">🔒</div>
                                <h5 class="fw-bold">Espace réservé</h5>
                                <p class="text-muted mb-4">Vous devez être membre pour postuler.</p>
                                <div class="d-grid gap-2">
                                    <a href="connexion.php" class="btn btn-primary rounded-pill fw-bold">Se connecter</a>
                                    <a href="inscription.php" class="btn btn-outline-primary rounded-pill fw-bold">Créer un compte</a>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5" id="events">
        <h2 class="text-center mb-5 text-primary fw-bold">Nos Actualités</h2>
        
        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <form method="GET" action="#events" class="d-flex gap-2 p-2 bg-body-tertiary rounded-pill shadow" style="position: relative; z-index: 10;">
                    <input type="text" name="search" class="form-control border-0 bg-transparent rounded-pill ps-4" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>" style="cursor: text;">
                    <button class="btn btn-primary rounded-circle" type="submit" style="min-width: 45px;">🔍</button>
                    <?php if(!empty($search)): ?><a href="index.php#events" class="btn btn-secondary rounded-circle" style="min-width: 45px;">✖</a><?php endif; ?>
                </form>
            </div>
        </div>

        <?php if(empty($events)): ?>
            <div class="alert alert-warning text-center">Aucun événement trouvé.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach($events as $evt): ?>
                    <div class="col-md-4 mb-3" data-aos="fade-up">
                        <div class="card h-100 shadow border-0 card-event">
                            <div class="card-body">
                                <span class="badge bg-warning text-dark mb-2"><?= date('d/m/Y', strtotime($evt['date_evenement'])) ?></span>
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($evt['titre']) ?></h5>
                                <p class="small text-muted mb-2">📍 <?= htmlspecialchars($evt['lieu']) ?></p>
                                <p class="card-text"><?= nl2br(htmlspecialchars($evt['description'])) ?></p>
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
    
    <script>
        // Animation des compteurs
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000; // 2 secondes
            const start = parseInt(element.textContent);
            const increment = (target - start) / (duration / 16); // 60 FPS
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if ((increment > 0 && current >= target) || (increment < 0 && current <= target)) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }
        
        // Détecter quand les compteurs sont visibles
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        if (!counter.classList.contains('animated')) {
                            counter.classList.add('animated');
                            animateCounter(counter);
                        }
                    });
                }
            });
        }, observerOptions);
        
        // Observer la section des statistiques
        document.addEventListener('DOMContentLoaded', () => {
            const statsSection = document.querySelector('.bg-warning');
            if (statsSection) {
                observer.observe(statsSection);
            }
            
            // Initialiser AOS
            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });
        });
    </script>
</body>
</html>