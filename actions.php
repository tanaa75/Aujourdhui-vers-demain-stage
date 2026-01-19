<?php
session_start();
require_once 'db.php';
$inscription_ok = false;

// Vérification : Membre OU Admin connecté ?
$est_connecte = (isset($_SESSION['membre_id']) || isset($_SESSION['user_id']));

// TRAITEMENT (Seulement si connecté)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $est_connecte) {
    if ($_POST['form_type'] == 'devoirs') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $classe = $_POST['classe'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        
        $message_complet = "🔔 INSCRIPTION AIDE AUX DEVOIRS\n\nEnfant : $nom $prenom\nClasse : $classe\nTéléphone : $tel\nEmail parent : $email";
        
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute(["Parent de $prenom", $email, $message_complet]);
        $inscription_ok = true;
    }
}

// Pré-remplissage (Uniquement si c'est un membre, sinon vide pour l'admin)
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
            transform: translateY(-15px); /* Monte vers le haut */
            box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important; /* Ombre plus forte */
        }
        .hover-card .emoji-icon {
            display: inline-block;
            transition: transform 0.3s;
        }
        .hover-card:hover .emoji-icon {
            transform: scale(1.3) rotate(10deg); /* L'emoji grossit et tourne */
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h1 class="text-center mb-5 fw-bold text-primary" data-aos="fade-down">Nos Champs d'Action</h1>

        <div class="row mb-5 align-items-center" id="devoirs">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card shadow border-0 h-100 bg-body-tertiary">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-warning mb-3">✏️ L’Aide aux Devoirs</h3>
                        <p class="text-muted">
                            L'aide aux devoirs chez <strong>Aujourd'hui vers Demain</strong>, c'est un espace bienveillant où chaque enfant bénéficie d'une attention particulière.
                        </p>
                        <p class="text-muted">
                            Nos bénévoles ne se contentent pas de vérifier que les exercices sont faits ; ils transmettent des méthodes de travail et redonnent confiance.
                        </p>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2">📅 <strong>Quand ?</strong> Lundi, Mardi, Jeudi, Vendredi</li>
                            <li class="mb-2">🕒 <strong>Heure ?</strong> De 16h30 à 18h00</li>
                            <li class="mb-2">👩‍🏫 <strong>Pour qui ?</strong> Enfants du CP au CM2</li>
                        </ul>
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