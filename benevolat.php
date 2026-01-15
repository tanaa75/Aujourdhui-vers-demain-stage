<?php
require_once 'db.php';
$benevole_ok = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == 'benevolat') {
    $nom = $_POST['nom'];
    $dispo = $_POST['dispo'];
    $skills = $_POST['skills'];
    $msg_complet = "❤️ NOUVEAU BÉNÉVOLE !\nNom : $nom\nDispos : $dispo\nAime faire : $skills";
    
    $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$nom, "Benevole", $msg_complet]);
    $benevole_ok = true;
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
                    Que vous soyez étudiant, actif ou retraité, votre expérience est une richesse. Nous ne recherchons pas des experts, mais des personnes motivées, prêtes à transmettre un savoir, à encadrer une sortie ou simplement à offrir une oreille attentive.
                </p>
                <p class="mb-4">
                    En nous rejoignant, vous participez activement à la vie de Noisy-le-Sec et vous vivez des moments de convivialité uniques avec une équipe soudée.
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
                            <div class="alert alert-success">👏 Merci ! On vous recontacte vite.</div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="form_type" value="benevolat">
                            <div class="mb-3"><label>Nom & Prénom</label><input type="text" name="nom" class="form-control" required></div>
                            <div class="mb-3"><label>Mes disponibilités</label><input type="text" name="dispo" class="form-control" placeholder="Ex: Mercredi après-midi" required></div>
                            <div class="mb-3"><label>Ce que j'aime faire</label><textarea name="skills" class="form-control" rows="3" placeholder="J'aime cuisiner, aider en maths..."></textarea></div>
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