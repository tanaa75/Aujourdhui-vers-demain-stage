<?php
/**
 * ===========================================
 * PAGE "NOS ACTIONS" - AIDE AUX DEVOIRS
 * ===========================================
 * 
 * Cette page présente l'action principale de l'association :
 * l'aide aux devoirs pour les enfants.
 * 
 * Fonctionnalités :
 * - Présentation de la mission
 * - Informations pratiques (jours, horaires, niveaux)
 * - Formulaire d'inscription (réservé aux connectés)
 * 
 * Le formulaire envoie un message dans la table 'messages'
 * qui sera visible dans l'espace admin.
 */

// Démarrage de la session
session_start();

// Connexion à la base de données
require_once 'db.php';

// Variable pour suivre si l'inscription a réussi
$inscription_ok = false;

// Vérification : est-ce qu'un membre OU un admin est connecté ?
$est_connecte = (isset($_SESSION['membre_id']) || isset($_SESSION['user_id']));

// ========== TRAITEMENT DU FORMULAIRE ==========
// On traite seulement si connecté et si c'est le bon formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $est_connecte) {
    
    // Vérification du type de formulaire
    if ($_POST['form_type'] == 'devoirs') {
        // Récupération des données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $classe = $_POST['classe'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        
        // Construction du message formaté
        $message_complet = "🔔 INSCRIPTION AIDE AUX DEVOIRS\n\nEnfant : $nom $prenom\nClasse : $classe\nTéléphone : $tel\nEmail parent : $email";
        
        // Insertion en base de données
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute(["Parent de $prenom", $email, $message_complet]);
        $inscription_ok = true;
    }
}

// Pré-remplissage de l'email si l'utilisateur est un membre connecté
$email_user = isset($_SESSION['membre_email']) ? $_SESSION['membre_email'] : "";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Nos Actions - Aujourd'hui vers Demain</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body { transition: background-color 0.5s; }
        
        /* EFFET SURVOL DES CARTES */
        .hover-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
        }
        .hover-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
        }
        .hover-card .emoji-icon {
            display: inline-block;
            transition: transform 0.3s;
        }
        .hover-card:hover .emoji-icon {
            transform: scale(1.3) rotate(10deg);
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

    <div class="actions-section py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-down">
                
                <h2 class="display-5 fw-bold mb-3">L'Aide aux Devoirs</h2>
                <p class="lead actions-subtitle">Accompagner chaque enfant vers la réussite</p>
            </div>
            
            <div class="row align-items-center g-5" id="devoirs">
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
                                    L'aide aux devoirs chez <strong>Aujourd'hui vers Demain</strong>, c'est un espace bienveillant où chaque enfant bénéficie d'une attention particulière.
                                </p>
                                <p class="actions-text">
                                    Nos bénévoles ne se contentent pas de vérifier que les exercices sont faits ; ils transmettent des méthodes de travail et redonnent confiance.
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
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">📝 Inscrire mon enfant</h5>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($est_connecte): ?>
                            
                            <?php if ($inscription_ok): ?>
                                <div class="alert alert-success">✅ Demande d'inscription envoyée !</div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <input type="hidden" name="form_type" value="devoirs">
                                <div class="row">
                                    <div class="col-6 mb-3"><label>Nom de l'enfant</label><input type="text" name="nom" class="form-control" required></div>
                                    <div class="col-6 mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label>Email du parent</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email_user) ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3"><label>Classe</label><input type="text" name="classe" class="form-control" placeholder="Ex: CM1" required></div>
                                    <div class="col-6 mb-3"><label>Téléphone</label><input type="tel" name="tel" class="form-control" required></div>
                                </div>
                                <button type="submit" class="btn btn-warning w-100 fw-bold">Valider l'inscription</button>
                            </form>

                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="mb-3 display-4">🔒</div>
                                <h5 class="fw-bold">Espace réservé</h5>
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

        <hr class="my-5">

        <div class="row mt-5" id="quartier">
            <div class="col-12 text-center mb-5" data-aos="fade-up">
                <h3 class="fw-bold text-success">🏘️ Vie de Quartier & Citoyenneté</h3>
                <p class="text-muted">Parce qu'un quartier vivant, c'est l'affaire de tous.</p>
            </div>
            
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="card p-4 shadow-sm border-0 h-100 hover-card text-center">
                    <div class="display-4 mb-3 emoji-icon">🎉</div>
                    <h5 class="fw-bold">Animations Locales</h5>
                    <p class="small text-muted mb-0">
                        Fêtes de quartier, repas partagés, sorties culturelles... Nous créons des occasions pour se rencontrer et tisser des liens entre voisins.
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="card p-4 shadow-sm border-0 h-100 hover-card text-center">
                    <div class="display-4 mb-3 emoji-icon">🗣️</div>
                    <h5 class="fw-bold">Conseil Citoyen</h5>
                    <p class="small text-muted mb-0">
                        Votre voix compte ! Nous faisons le relais entre les habitants et les institutions (Mairie, Est Ensemble) pour améliorer notre cadre de vie.
                    </p>
                </div>
            </div>

            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="card p-4 shadow-sm border-0 h-100 hover-card text-center">
                    <div class="display-4 mb-3 emoji-icon">🤝</div>
                    <h5 class="fw-bold">Médiation Sociale</h5>
                    <p class="small text-muted mb-0">
                        Besoin d'aide pour des démarches ? D'une oreille attentive ? Nous orientons les familles vers les bons interlocuteurs et aidons à résoudre les conflits.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>