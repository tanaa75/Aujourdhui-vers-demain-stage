<?php
require_once 'db.php';
$inscription_ok = false;

// TRAITEMENT DU FORMULAIRE AIDE AUX DEVOIRS
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'devoirs') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $classe = $_POST['classe'];
    $tel = $_POST['tel'];
    $email = $_POST['email']; // NOUVEAU
    
    $message_complet = "🔔 INSCRIPTION AIDE AUX DEVOIRS\n\nEnfant : $nom $prenom\nClasse : $classe\nTéléphone : $tel\nEmail parent : $email";
    
    $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
    // On enregistre le vrai email ici
    $stmt->execute(["Parent de $prenom", $email, $message_complet]);
    $inscription_ok = true;
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Nos Actions - Aujourd'hui vers Demain</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>body { transition: background-color 0.5s; }</style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h1 class="text-center mb-5 fw-bold text-primary" data-aos="fade-down">Nos Champs d'Action</h1>

        <div class="row mb-5 align-items-center" id="devoirs">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card shadow border-0 h-100">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-warning mb-3">✏️ L’Aide aux Devoirs</h3>
                        <p class="text-muted">
                            L'aide aux devoirs chez <strong>Aujourd'hui vers Demain</strong>, c'est bien plus qu'une simple étude surveillée. C'est un espace bienveillant où chaque enfant de primaire bénéficie d'une attention particulière pour surmonter ses difficultés.
                        </p>
                        <p class="text-muted">
                            Nos bénévoles ne se contentent pas de vérifier que les exercices sont faits ; ils transmettent des méthodes de travail, encouragent la curiosité et redonnent confiance aux élèves.
                        </p>
                        <hr>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2">📅 <strong>Quand ?</strong> Lundi, Mardi, Jeudi, Vendredi</li>
                            <li class="mb-2">🕒 <strong>Heure ?</strong> De 16h30 à 18h00</li>
                            <li class="mb-2">👩‍🏫 <strong>Pour qui ?</strong> Enfants du CP au CM2 (6-12 ans)</li>
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
                                <input type="email" name="email" class="form-control" placeholder="parent@exemple.com" required>
                            </div>

                            <div class="mb-3"><label>Classe</label><input type="text" name="classe" class="form-control" placeholder="Ex: CM1" required></div>
                            <div class="mb-3"><label>Téléphone du parent</label><input type="tel" name="tel" class="form-control" required></div>
                            <button type="submit" class="btn btn-warning w-100 fw-bold">Valider l'inscription</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="row align-items-center" id="quartier">
            <div class="col-lg-12 text-center mb-4" data-aos="fade-up">
                <h3 class="fw-bold text-success">🏘️ Vie de Quartier & Citoyenneté</h3>
                <p class="text-muted">Nous ne sommes pas qu'une école de soutien, mais un acteur social complet.</p>
            </div>
            
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="card h-100 shadow-sm border-0 text-center p-3">
                    <div class="fs-1 mb-2">🎉</div>
                    <h5>Animations Locales</h5>
                    <p class="small text-muted">Fêtes de quartier, sorties culturelles et sportives pour les jeunes de la Boissière.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="card h-100 shadow-sm border-0 text-center p-3">
                    <div class="fs-1 mb-2">🗣️</div>
                    <h5>Conseil Citoyen</h5>
                    <p class="small text-muted">Relais entre habitants et institutions (Mairie, Est Ensemble) pour améliorer le cadre de vie.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="card h-100 shadow-sm border-0 text-center p-3">
                    <div class="fs-1 mb-2">🤝</div>
                    <h5>Médiation Sociale</h5>
                    <p class="small text-muted">Orientation des familles vers les bons interlocuteurs (CAF, assistantes sociales).</p>
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