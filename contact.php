<?php
session_start();
require_once 'db.php';
$msg_envoye = false;

// Vérification : Membre OU Admin connecté ?
$est_connecte = (isset($_SESSION['membre_id']) || isset($_SESSION['user_id']));

// TRAITEMENT (Seulement si connecté)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $est_connecte) {
    if (!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['email'], $_POST['message']]);
        $msg_envoye = true;
    }
}

// Pré-remplissage
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
    <style>
        body { transition: background-color 0.5s, color 0.5s; }
        .map-container iframe { width: 100%; height: 400px; border-radius: 15px; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h2 class="text-center mb-5 display-5 fw-bold text-primary">Nous Contacter</h2>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card shadow border-0 h-100">
                    <div class="card-body p-4 p-md-5">
                        
                        <?php if ($est_connecte): ?>

                            <h4 class="mb-4">💌 Envoyez-nous un message</h4>
                            
                            <?php if ($msg_envoye): ?>
                                <div class="alert alert-success text-center animate__animated animate__fadeIn">
                                    ✅ Message envoyé ! On vous répond très vite.
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="form-floating mb-3">
                                    <input type="text" name="nom" class="form-control" id="floatingNom" required placeholder="Nom" value="<?= htmlspecialchars($nom_user) ?>">
                                    <label for="floatingNom">Votre Nom</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control" id="floatingEmail" required placeholder="Email" value="<?= htmlspecialchars($email_user) ?>">
                                    <label for="floatingEmail">Votre Email</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <textarea name="message" class="form-control" id="floatingMsg" style="height: 150px" required placeholder="Message"></textarea>
                                    <label for="floatingMsg">Votre Message</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill">🚀 Envoyer le message</button>
                            </form>

                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-3" style="font-size: 4rem;">🔒</div>
                                <h4 class="fw-bold">Espace réservé aux membres</h4>
                                <p class="text-muted mb-4">Vous devez avoir un compte pour nous envoyer un message.</p>
                                <div class="d-grid gap-2 col-8 mx-auto">
                                    <a href="connexion.php" class="btn btn-primary rounded-pill fw-bold">Me connecter</a>
                                    <a href="inscription.php" class="btn btn-outline-primary rounded-pill fw-bold">Créer un compte</a>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="h-100 d-flex flex-column gap-4">
                    <div class="card shadow border-0 bg-primary text-white">
                        <div class="card-body p-4">
                            <h4 class="mb-3">📍 Nos Coordonnées</h4>
                            <p class="mb-1"><strong>Adresse :</strong> 116 rue de l'Avenir, 93130 Noisy-le-Sec</p>
                            <p class="mb-1"><strong>Téléphone :</strong> 01 23 45 67 89</p>
                            <p class="mb-0"><strong>Email :</strong> contact@asso-noisy.fr</p>
                        </div>
                    </div>

                    <div class="card shadow border-0 flex-grow-1 map-container">
                        <iframe 
                            src="https://maps.google.com/maps?q=116+rue+de+l'Avenir+93130+Noisy-le-Sec&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>