<?php
require_once 'db.php';

// --- 1. LOGIQUE DE TRAITEMENT DES FORMULAIRES ---

$inscription_ok = false;
$benevole_ok = false;
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type'])) {
    
    // --- CAS 1 : INSCRIPTION AIDE AUX DEVOIRS ---
    if ($_POST['form_type'] == 'devoirs') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $classe = $_POST['classe'];
        $tel = $_POST['tel'];
        $email = $_POST['email']; // Champ Email du parent
        
        $msg_complet = "🔔 INSCRIPTION AIDE AUX DEVOIRS\n\nEnfant : $nom $prenom\nClasse : $classe\nTéléphone : $tel\nEmail parent : $email";
        
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute(["Parent de $prenom", $email, $msg_complet]);
        $inscription_ok = true;
    }

    // --- CAS 2 : CANDIDATURE BÉNÉVOLAT (Avec CV) ---
    if ($_POST['form_type'] == 'benevolat') {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $dispo = $_POST['dispo'];
        $skills = $_POST['skills'];
        
        // Gestion du CV (Upload)
        $lien_cv = "Aucun CV fourni";
        
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            // 1. Vérifier la taille (5 Mo max)
            if ($_FILES['cv']['size'] <= 5000000) {
                // 2. Vérifier l'extension
                $fileInfo = pathinfo($_FILES['cv']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'png'];
                
                if (in_array($extension, $allowedExtensions)) {
                    // 3. Sauvegarder le fichier
                    // On renomme le fichier pour éviter les doublons (ex: cv_nom_timestamp.pdf)
                    $new_filename = 'cv_' . preg_replace('/[^a-zA-Z0-9]/', '', $nom) . '_' . time() . '.' . $extension;
                    move_uploaded_file($_FILES['cv']['tmp_name'], 'uploads/' . $new_filename);
                    
                    // On crée le lien pour l'admin
                    $lien_cv = "📄 TÉLÉCHARGER LE CV : http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/uploads/" . $new_filename;
                } else {
                    $error_msg = "Format de fichier non supporté (PDF, Word ou Image seulement).";
                }
            } else {
                $error_msg = "Le fichier est trop lourd (Max 5 Mo).";
            }
        }

        if (empty($error_msg)) {
            $msg_complet = "❤️ NOUVEAU BÉNÉVOLE !\n\nNom : $nom\nEmail : $email\nTéléphone : $tel\n\nDispos : $dispo\nAime faire : $skills\n\n$lien_cv";
            
            $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $msg_complet]);
            $benevole_ok = true;
        }
    }
}

// --- 2. LOGIQUE DE RECHERCHE ---
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
            margin-bottom: 0;
        }
        
        .key-figures {
            background-color: #ffc107;
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
                <a href="#actions" class="btn btn-warning btn-lg px-4 gap-3 fw-bold rounded-pill shadow-lg">📚 Aide aux devoirs</a>
                <a href="#benevolat" class="btn btn-outline-light btn-lg px-4 rounded-pill">🤝 Devenir Bénévole</a>
            </div>
        </div>
    </div>

    <div class="key-figures text-center shadow">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-3" data-aos="flip-left"><h2 class="display-4 fw-bold">15+</h2><p class="mb-0 text-uppercase">Bénévoles engagés</p></div>
                <div class="col-md-4 mb-3" data-aos="flip-left" data-aos-delay="200"><h2 class="display-4 fw-bold">50+</h2><p class="mb-0 text-uppercase">Enfants accompagnés</p></div>
                <div class="col-md-4 mb-3" data-aos="flip-left" data-aos-delay="400"><h2 class="display-4 fw-bold">2020</h2><p class="mb-0 text-uppercase">Année de création</p></div>
            </div>
        </div>
    </div>

    <div class="container py-5 my-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="fw-bold text-primary mb-4">Qui sommes-nous ?</h2>
                <p class="lead text-muted">L'association <strong>Aujourd'hui vers Demain</strong> est née d'une volonté simple : celle des habitants de Noisy-le-Sec de s'entraider pour construire un avenir meilleur.</p>
                <p>Depuis notre création en 2020, nous agissons au cœur des quartiers Langevin et de la Boissière. Ce qui nous anime ? La conviction que la solidarité locale est le moteur le plus puissant.</p>
                <p class="mb-4">Que ce soit à travers l'aide aux devoirs, nos ateliers de français ou nos événements festifs, chaque action est pensée pour et par les habitants.</p>
            </div>
            <div class="col-lg-6" data-aos="zoom-in">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Équipe" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>

    <hr class="container my-5">

    <div class="container py-5" id="actions">
        <h2 class="text-center mb-5 fw-bold text-primary" data-aos="fade-down">Nos Champs d'Action</h2>

        <div class="row mb-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card shadow border-0 h-100 bg-body-tertiary">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-warning mb-3">✏️ L’Aide aux Devoirs</h3>
                        <p class="text-muted">L'aide aux devoirs chez <strong>Aujourd'hui vers Demain</strong>, c'est bien plus qu'une simple étude surveillée. C'est un espace bienveillant où chaque enfant de primaire bénéficie d'une attention particulière.</p>
                        <p class="text-muted">Nos bénévoles transmettent des méthodes de travail, encouragent la curiosité et redonnent confiance aux élèves.</p>
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
                    <div class="card-header bg-primary text-white text-center"><h5 class="mb-0">📝 Inscrire mon enfant</h5></div>
                    <div class="card-body">
                        <?php if ($inscription_ok): ?><div class="alert alert-success">✅ Demande d'inscription envoyée !</div><?php endif; ?>
                        
                        <form method="POST" action="#actions">
                            <input type="hidden" name="form_type" value="devoirs">
                            <div class="row">
                                <div class="col-6 mb-3"><label>Nom de l'enfant</label><input type="text" name="nom" class="form-control" required></div>
                                <div class="col-6 mb-3"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
                            </div>
                            
                            <div class="mb-3">
                                <label>Email du parent</label>
                                <input type="email" name="email" class="form-control" placeholder="parent@email.com" required>
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3"><label>Classe</label><input type="text" name="classe" class="form-control" placeholder="Ex: CM1" required></div>
                                <div class="col-6 mb-3"><label>Téléphone</label><input type="tel" name="tel" class="form-control" required></div>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 fw-bold">Valider l'inscription</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row align-items-center mt-5">
            <div class="col-lg-12 text-center mb-4"><h3 class="fw-bold text-success">🏘️ Vie de Quartier & Citoyenneté</h3></div>
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="card h-100 shadow-sm border-0 text-center p-3">
                    <div class="fs-1 mb-2">🎉</div><h5>Animations Locales</h5><p class="small text-muted">Fêtes de quartier, sorties culturelles et sportives.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="card h-100 shadow-sm border-0 text-center p-3">
                    <div class="fs-1 mb-2">🗣️</div><h5>Conseil Citoyen</h5><p class="small text-muted">Relais entre habitants et institutions pour améliorer le cadre de vie.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="card h-100 shadow-sm border-0 text-center p-3">
                    <div class="fs-1 mb-2">🤝</div><h5>Médiation Sociale</h5><p class="small text-muted">Orientation des familles vers les bons interlocuteurs.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-body-tertiary py-5" id="benevolat">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="fw-bold mb-4">Rejoignez l'aventure !</h2>
                    <p class="lead">Devenir bénévole au sein de notre association, c'est choisir de consacrer un peu de son temps pour faire une grande différence dans la vie du quartier.</p>
                    <p>Que vous soyez étudiant, actif ou retraité, votre expérience est une richesse. Nous ne recherchons pas des experts, mais des personnes motivées.</p>
                    <h5 class="fw-bold mt-4 border-top pt-3">Nos besoins actuels :</h5>
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent">📚 Aide aux devoirs</li>
                        <li class="list-group-item bg-transparent">💻 Atelier Informatique</li>
                        <li class="list-group-item bg-transparent">🎪 Logistique événements</li>
                    </ul>
                </div>
                
                <div class="col-lg-6" data-aos="zoom-in">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5">
                            <h3 class="text-center mb-4 text-primary">Je me lance !</h3>
                            
                            <?php if ($benevole_ok): ?>
                                <div class="alert alert-success">👏 Candidature envoyée !</div>
                            <?php endif; ?>
                            <?php if (!empty($error_msg)): ?>
                                <div class="alert alert-danger">⚠️ <?= $error_msg ?></div>
                            <?php endif; ?>

                            <form method="POST" action="#benevolat" enctype="multipart/form-data">
                                <input type="hidden" name="form_type" value="benevolat">
                                
                                <div class="mb-3">
                                    <label>Nom & Prénom</label>
                                    <input type="text" name="nom" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label>Téléphone</label>
                                        <input type="tel" name="tel" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label>Votre CV (PDF, Word - Max 5 Mo)</label>
                                    <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                                </div>

                                <div class="mb-3">
                                    <label>Mes disponibilités</label>
                                    <input type="text" name="dispo" class="form-control" placeholder="Ex: Mercredi après-midi" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label>Ce que j'aime faire</label>
                                    <textarea name="skills" class="form-control" rows="3" placeholder="J'aime cuisiner, aider en maths..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">Envoyer ma candidature</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5" id="events">
        <h2 class="text-center mb-5 text-primary fw-bold" data-aos="fade-up">
            <span class="border-bottom border-3 border-primary pb-2">Nos Actualités & Événements</span>
        </h2>

        <div class="row justify-content-center mb-5" data-aos="flip-up">
            <div class="col-md-6">
                <form method="GET" action="#events" class="d-flex gap-2 p-2 bg-body-tertiary rounded-pill shadow">
                    <input type="text" name="search" class="form-control form-control-lg border-0 bg-transparent rounded-pill ps-4" placeholder="Rechercher (ex: Collecte...)" value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary btn-lg rounded-circle" type="submit" style="width: 50px; height: 50px;">🔍</button>
                    <?php if(!empty($search)): ?>
                        <a href="index.php#events" class="btn btn-secondary btn-lg rounded-circle d-flex align-items-center justify-content-center ms-1" style="width: 50px; height: 50px;">✖</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

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
        <?php else: ?>
            <div class="alert alert-warning text-center py-5 shadow rounded-4" data-aos="zoom-in">
                <h4>😕 Aucun événement trouvé</h4>
                <a href="index.php" class="btn btn-dark mt-3 rounded-pill">Tout afficher</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>