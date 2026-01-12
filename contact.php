<?php
require_once 'db.php';
$msg_envoye = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['email'], $_POST['message']]);
        $msg_envoye = true;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4 text-primary">Nous Contacter</h2>
                        
                        <?php if ($msg_envoye): ?>
                            <div class="alert alert-success text-center">
                                ✅ Merci ! Votre message a bien été envoyé. Nous vous répondrons vite.
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Votre Nom</label>
                                <input type="text" name="nom" class="form-control" required placeholder="Jean Dupont">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Votre Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="jean@email.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Votre Message</label>
                                <textarea name="message" class="form-control" rows="5" required placeholder="Bonjour, je voudrais devenir bénévole..."></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Envoyer le message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>