<?php
require_once 'db.php';
$benevole_ok = false;
$error_msg = "";

// TRAITEMENT DU FORMULAIRE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'benevolat') {
    $nom = $_POST['nom'];
    $email = $_POST['email']; // NOUVEAU
    $tel = $_POST['tel'];     // NOUVEAU
    $dispo = $_POST['dispo'];
    $skills = $_POST['skills'];
    
    // --- GESTION DU CV (UPLOAD) ---
    $lien_cv = "Aucun CV fourni"; // Par défaut
    
    // Si un fichier est envoyé sans erreur
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        // 1. Vérif taille (5 Mo max)
        if ($_FILES['cv']['size'] <= 5000000) {
            // 2. Vérif extension
            $fileInfo = pathinfo($_FILES['cv']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'png'];
            
            if (in_array($extension, $allowedExtensions)) {
                // 3. Enregistrement
                // On crée un nom unique pour ne pas écraser les autres CV
                $new_filename = 'cv_' . preg_replace('/[^a-zA-Z0-9]/', '', $nom) . '_' . time() . '.' . $extension;
                
                // On déplace le fichier dans le dossier 'uploads'
                if (move_uploaded_file($_FILES['cv']['tmp_name'], 'uploads/' . $new_filename)) {
                    // On crée le lien pour l'admin
                    $lien_cv = "📄 TÉLÉCHARGER LE CV : http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/uploads/" . $new_filename;
                } else {
                    $error_msg = "Erreur lors de l'enregistrement du fichier sur le serveur.";
                }
            } else {
                $error_msg = "Format non supporté (Utilisez PDF, Word ou Image).";
            }
        } else {
            $error_msg = "Fichier trop lourd (Max 5 Mo).";
        }
    }

    // Si pas d'erreur, on enregistre en base de données
    if (empty($error_msg)) {
        // On compile toutes les infos dans le message
        $msg_complet = "❤️ NOUVEAU BÉNÉVOLE !\n\nNom : $nom\nEmail : $email\nTéléphone : $tel\n\nDispos : $dispo\nAime faire : $skills\n\n$lien_cv";
        
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $msg_complet]);
        $benevole_ok = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Nous Rejoindre - Aujourd'hui vers Demain</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>body { transition: background-color 0.5s; }</style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row align-items-center">
            
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="fw-bold mb-4">Rejoignez l'aventure !</h1>
                <p class="lead">
                    Devenir bénévole au sein de notre association, c'est choisir de consacrer un peu de son temps pour faire une grande différence dans la vie du quartier.
                </p>
                <p>
                    Que vous soyez étudiant, actif ou retraité, votre expérience est une richesse. Nous ne recherchons pas des experts, mais des personnes motivées.
                </p>
                
                <h5 class="fw-bold mt-4 border-top pt-3">Nos besoins actuels :</h5>
                <ul class="list-group list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent">📚 Aide aux devoirs (16h30-18h)</li>
                    <li class="list-group-item bg-transparent">💻 Atelier Informatique</li>
                    <li class="list-group-item bg-transparent">🎪 Logistique événements</li>
                </ul>
            </div>

            <div class="col-lg-6" data-aos="zoom-in">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4 text-primary">Je me lance !</h3>
                        
                        <?php if ($benevole_ok): ?>
                            <div class="alert alert-success">👏 Candidature envoyée ! On vous recontacte vite.</div>
                        <?php endif; ?>
                        
                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger">⚠️ <?= $error_msg ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
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

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>