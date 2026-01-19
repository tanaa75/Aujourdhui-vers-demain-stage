<?php
session_start(); 
require_once 'db.php';

// --- LOGIQUE TRAITEMENT (PROTECTION SESSION) ---
$inscription_ok = false;
$benevole_ok = false;
$error_msg = "";

// On vérifie si MEMBRE OU ADMIN est connecté
$est_connecte = (isset($_SESSION['membre_id']) || isset($_SESSION['user_id']));

// On ne traite les formulaires QUE si connecté
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $est_connecte) {
    
    // CAS 1 : DEVOIRS
    if ($_POST['form_type'] == 'devoirs') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $classe = $_POST['classe'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $msg = "🔔 INSCRIPTION AIDE AUX DEVOIRS\n\nEnfant : $nom $prenom\nClasse : $classe\nTéléphone : $tel\nEmail parent : $email";
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute(["Parent de $prenom", $email, $msg]);
        $inscription_ok = true;
    }

    // CAS 2 : BENEVOLAT
    if ($_POST['form_type'] == 'benevolat') {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $dispo = $_POST['dispo'];
        $skills = $_POST['skills'];
        
        $lien_cv = "Aucun CV fourni";
        // Gestion Upload CV
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            if ($_FILES['cv']['size'] <= 5000000) {
                $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['pdf', 'doc', 'docx', 'jpg', 'png'])) {
                    $newname = 'cv_' . preg_replace('/[^a-zA-Z0-9]/', '', $nom) . '_' . time() . '.' . $ext;
                    if(move_uploaded_file($_FILES['cv']['tmp_name'], 'uploads/' . $newname)) {
                        $lien_cv = "📄 TÉLÉCHARGER LE CV : http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/uploads/" . $newname;
                    }
                } else { $error_msg = "Format invalide."; }
            } else { $error_msg = "Fichier trop lourd."; }
        }

        if (empty($error_msg)) {
            $msg = "❤️ NOUVEAU BÉNÉVOLE !\n\nNom : $nom\nEmail : $email\nTéléphone : $tel\n\nDispos : $dispo\nAime faire : $skills\n\n$lien_cv";
            $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $msg]);
            $benevole_ok = true;
        }
    }
}

// Données utilisateur pour pré-remplissage (Seulement si Membre, sinon vide pour Admin)
$nom_user = isset($_SESSION['membre_nom']) ? $_SESSION['membre_nom'] : "";
$email_user = isset($_SESSION['membre_email']) ? $_SESSION['membre_email'] : "";

// Recherche Événements
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT * FROM evenements WHERE titre LIKE :s OR description LIKE :s OR lieu LIKE :s ORDER BY date_evenement DESC");
    $stmt->execute(['s' => "%$search%"]);
    $events = $stmt->fetchAll();
} else {
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

    <div class="bg-warning text-dark py-5 text-center fw-bold shadow">
        <div class="container"><div class="row"><div class="col-4"><h2>15+</h2>Bénévoles</div><div class="col-4"><h2>50+</h2>Enfants</div><div class="col-4"><h2>2020</h2>Création</div></div></div>
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

    <div class="container py-5" id="actions">
        <h2 class="text-center mb-5 fw-bold text-primary">Nos Actions</h2>
        <div class="row align-items-center mb-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card shadow border-0 h-100 bg-body-tertiary">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-warning mb-3">✏️ L’Aide aux Devoirs</h3>
                        <p class="text-muted">
                            L'école, ce n'est pas toujours facile, et à la maison, on n'a pas toujours le temps ou les clés pour aider. Notre accompagnement ne sert pas juste à "finir les devoirs", mais à <strong>redonner confiance</strong>.
                        </p>
                        <p class="text-muted">
                            Dans une ambiance calme, nos bénévoles prennent le temps d'expliquer, de réviser les bases et surtout d'apprendre à s'organiser. L'objectif : que chaque enfant reparte fier de son travail et l'esprit plus léger.
                        </p>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2">📅 Lundi, Mardi, Jeudi, Vendredi</li>
                            <li class="mb-2">🕒 16h30 - 18h00</li>
                            <li class="mb-2">👩‍🏫 Du CP au CM2</li>
                        </ul>
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

    <div class="bg-body-tertiary py-5" id="benevolat">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="fw-bold mb-4">Rejoignez l'équipe !</h2>
                    <p class="lead">
                        On a souvent l'impression qu'il faut être un expert pour être bénévole. Chez nous, c'est faux. Ce qui compte, c'est l'envie d'être utile.
                    </p>
                    <p class="text-muted">
                        Que vous ayez une heure par semaine ou tout votre mercredi après-midi, vous êtes les bienvenus. Venez partager vos connaissances en informatique, aider un petit à faire sa soustraction ou juste donner un coup de main pour installer la salle avant une fête.
                    </p>
                    <p class="fw-bold text-primary mt-3">C'est ça, l'esprit de quartier.</p>
                    
                    <h6 class="mt-4 border-bottom pb-2">On cherche du renfort pour :</h6>
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent">📚 L'aide aux devoirs</li>
                        <li class="list-group-item bg-transparent">💻 Les ateliers numériques</li>
                        <li class="list-group-item bg-transparent">🎪 L'organisation des fêtes</li>
                    </ul>
                </div>
                
                <div class="col-lg-6" data-aos="zoom-in">
                    <div class="card shadow-lg border-0 rounded-4 p-4">
                        <h3 class="text-center text-primary mb-3">Je me lance !</h3>
                        
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
                <form method="GET" action="#events" class="d-flex gap-2 p-2 bg-body-tertiary rounded-pill shadow">
                    <input type="text" name="search" class="form-control border-0 bg-transparent rounded-pill ps-4" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary rounded-circle" type="submit">🔍</button>
                    <?php if(!empty($search)): ?><a href="index.php#events" class="btn btn-secondary rounded-circle">✖</a><?php endif; ?>
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
</body>
</html>