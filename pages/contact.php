<?php
/**
 * ===========================================
 * PAGE DE CONTACT
 * ===========================================
 * 
 * Cette page permet aux membres/admins d'envoyer un message
 * à l'association via un formulaire.
 * 
 * Fonctionnalités :
 * - Formulaire de contact (réservé aux connectés)
 * - Affichage des coordonnées de l'association
 * - Carte Google Maps intégrée
 * 
 * Sécurité :
 * - Seuls les utilisateurs connectés peuvent envoyer un message
 * - Les données sont pré-remplies si l'utilisateur est connecté
 */

// Démarrage de la session
session_start();

// Connexion à la base de données
require_once '../includes/db.php';

// Variable pour suivre si le message a été envoyé
$msg_envoye = false;

// Vérification : est-ce qu'un membre OU un admin est connecté ?
$est_connecte = (isset($_SESSION['membre_id']) || isset($_SESSION['user_id']));

// ========== TRAITEMENT DU FORMULAIRE ==========
// On traite seulement si l'utilisateur est connecté
if ($_SERVER["REQUEST_METHOD"] == "POST" && $est_connecte) {
    // Vérification que tous les champs sont remplis
    if (!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        // Insertion du message en base de données
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['email'], $_POST['message']]);
        $msg_envoye = true;
    }
}

// Pré-remplissage des champs avec les infos du membre connecté
$nom_user = isset($_SESSION['membre_nom']) ? $_SESSION['membre_nom'] : "";
$email_user = isset($_SESSION['membre_email']) ? $_SESSION['membre_email'] : "";
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Aujourd'hui vers Demain</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/mobile-responsive.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body { transition: background-color 0.5s, color 0.5s; }
        
        /* HEADER CONTACT */
        .contact-header {
            position: relative;
            padding: 60px 0 40px;
            overflow: hidden;
        }
        
        .contact-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image: radial-gradient(circle at 30% 50%, rgba(13, 110, 253, 0.3) 0%, transparent 50%);
        }
        
        .contact-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.35);
        }
        
        /* CARTES CONTACT */
        .contact-form-card {
            border-radius: 20px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .contact-form-card:hover {
            transform: translateY(-5px);
        }
        
        .contact-info-card {
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        
        .contact-info-card:hover {
            transform: translateY(-3px);
        }
        
        .contact-info-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .contact-info-item:hover {
            transform: translateX(5px);
        }
        
        .contact-info-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .contact-info-item:hover .contact-info-icon {
            transform: scale(1.1);
        }
        
        .map-container {
            border-radius: 16px;
            overflow: hidden;
        }
        
        .map-container iframe {
            width: 100%;
            height: 250px;
            border: none;
        }
        
        /* MODE CLAIR */
        [data-bs-theme="light"] .contact-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        [data-bs-theme="light"] .contact-title {
            color: #0d6efd;
        }
        
        [data-bs-theme="light"] .contact-subtitle {
            color: #6c757d;
        }
        
        [data-bs-theme="light"] .contact-form-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        
        [data-bs-theme="light"] .contact-info-item {
            background: #f8f9fa;
        }
        
        [data-bs-theme="light"] .contact-info-icon {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border: 2px solid rgba(13, 110, 253, 0.2);
        }
        
        [data-bs-theme="light"] .contact-info-label {
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        [data-bs-theme="light"] .contact-info-value {
            color: #212529;
            font-weight: 600;
        }
        
        /* MODE SOMBRE */
        [data-bs-theme="dark"] .contact-header {
            background: linear-gradient(135deg, #1a1d20 0%, #2d3238 100%);
        }
        
        [data-bs-theme="dark"] .contact-title {
            color: #ffc107;
        }
        
        [data-bs-theme="dark"] .contact-subtitle {
            color: #adb5bd;
        }
        
        [data-bs-theme="dark"] .contact-form-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }
        
        [data-bs-theme="dark"] .contact-info-item {
            background: rgba(255, 255, 255, 0.05);
        }
        
        [data-bs-theme="dark"] .contact-info-icon {
            background: rgba(13, 110, 253, 0.15);
            color: #4dabf7;
            border: 2px solid rgba(13, 110, 253, 0.3);
        }
        
        [data-bs-theme="dark"] .contact-info-label {
            color: #868e96;
            font-size: 0.85rem;
        }
        
        [data-bs-theme="dark"] .contact-info-value {
            color: #f8f9fa;
            font-weight: 600;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include '../includes/navbar.php'; ?>

    <!-- Header -->
    <div class="contact-header">
        <div class="container">
            <div class="text-center">
                <h1 class="display-4 fw-bold mb-3 contact-title">Nous Contacter</h1>
                <p class="lead contact-subtitle">Une question ? Un projet ? Parlons-en !</p>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            <!-- Formulaire -->
            <div class="col-lg-7" data-aos="fade-right">
                <div class="contact-form-card h-100">
                    <div class="p-4 p-md-5">
                        
                        <?php if ($est_connecte): ?>

                            <h4 class="mb-4 fw-bold"><i class="bi bi-envelope-heart-fill me-2 text-primary"></i>Envoyez-nous un message</h4>
                            
                            <?php if ($msg_envoye): ?>
                                <div class="alert alert-success text-center border-0 shadow-sm">
                                    <i class="bi bi-check-circle-fill me-2"></i>Message envoyé ! On vous répond très vite.
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="form-floating mb-3">
                                    <input type="text" name="nom" class="form-control rounded-3" id="floatingNom" required placeholder="Nom" value="<?= htmlspecialchars($nom_user) ?>">
                                    <label for="floatingNom">Votre Nom</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control rounded-3" id="floatingEmail" required placeholder="Email" value="<?= htmlspecialchars($email_user) ?>">
                                    <label for="floatingEmail">Votre Email</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <textarea name="message" class="form-control rounded-3" id="floatingMsg" style="height: 150px" required placeholder="Message"></textarea>
                                    <label for="floatingMsg">Votre Message</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow">
                                    <i class="bi bi-send-fill me-2"></i>Envoyer le message
                                </button>
                            </form>

                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3" style="font-size: 4rem;"><i class="bi bi-lock-fill text-secondary"></i></div>
                                <h4 class="fw-bold">Espace réservé aux membres</h4>
                                <p class="text-muted mb-4">Vous devez avoir un compte pour nous envoyer un message.</p>
                                <div class="d-grid gap-2 col-8 mx-auto">
                                    <a href="../auth/connexion.php" class="btn btn-primary rounded-pill fw-bold py-2">Me connecter</a>
                                    <a href="../auth/inscription.php" class="btn btn-outline-primary rounded-pill fw-bold py-2">Créer un compte</a>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Infos -->
            <div class="col-lg-5" data-aos="fade-left">
                <div class="d-flex flex-column gap-4">
                    
                    <!-- Coordonnées -->
                    <div class="contact-info-card p-4">
                        <h5 class="fw-bold mb-4"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Nos Coordonnées</h5>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <div class="contact-info-label">Adresse</div>
                                <div class="contact-info-value">116 rue de l'Avenir, 93130 Noisy-le-Sec</div>
                            </div>
                        </div>
                        
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <div class="contact-info-label">Téléphone</div>
                                <div class="contact-info-value">01 23 45 67 89</div>
                            </div>
                        </div>
                        
                        <div class="contact-info-item mb-0">
                            <div class="contact-info-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <div class="contact-info-label">Email</div>
                                <div class="contact-info-value">contact@asso.fr</div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte -->
                    <div class="map-container shadow">
                        <iframe 
                            src="https://maps.google.com/maps?q=116+rue+de+l'Avenir+93130+Noisy-le-Sec&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            allowfullscreen
                            loading="lazy">
                        </iframe>
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