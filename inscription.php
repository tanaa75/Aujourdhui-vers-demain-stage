<?php
require_once 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM membres WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        $message = "<div class='alert alert-warning text-center border-0 shadow-sm'>⚠️ Cet email est déjà utilisé.</div>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO membres (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        if ($stmt->execute([$nom, $email, $password])) {
            $message = "<div class='alert alert-success text-center border-0 shadow-sm'>✅ Compte créé ! <a href='connexion.php' class='fw-bold text-decoration-none'>Connectez-vous ici</a>.</div>";
        } else {
            $message = "<div class='alert alert-danger text-center border-0 shadow-sm'>❌ Une erreur technique est survenue.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Même image que la connexion pour la cohérence */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card-custom {
            /* Effet Glassmorphism */
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: none;
            width: 100%;
            max-width: 450px; /* Un peu plus large pour l'inscription */
        }
        
        .form-control-lg {
            font-size: 1rem;
            padding: 0.8rem 1rem;
        }
    </style>
</head>
<body>

    <div class="card card-custom shadow-lg p-4 p-md-5">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" width="60" class="mb-3">
            <h3 class="fw-bold text-primary">Créer un compte</h3>
            <p class="text-muted small">Rejoignez l'association en quelques clics</p>
        </div>
        
        <?= $message ?>

        <form method="POST">
            <div class="form-floating mb-3">
                <input type="text" name="nom" class="form-control rounded-4" id="nomInput" placeholder="Jean Dupont" required>
                <label for="nomInput"><i class="bi bi-person"></i> Nom complet</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control rounded-4" id="emailInput" placeholder="name@example.com" required>
                <label for="emailInput"><i class="bi bi-envelope"></i> Email</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" name="mot_de_passe" class="form-control rounded-4" id="passInput" placeholder="Password" required>
                <label for="passInput"><i class="bi bi-key"></i> Mot de passe</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-3 fs-5 shadow hover-scale">
                S'inscrire maintenant
            </button>
        </form>

        <div class="d-grid gap-2 text-center mt-4">
            <a href="connexion.php" class="btn btn-outline-secondary rounded-pill py-2 fw-bold border-2">
                Déjà membre ? Se connecter
            </a>

            <a href="index.php" class="text-decoration-none text-secondary mt-2 py-2 fs-6">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>

</body>
</html>