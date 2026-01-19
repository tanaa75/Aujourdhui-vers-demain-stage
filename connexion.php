<?php
session_start();
require_once 'db.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM membres WHERE email = ?");
    $stmt->execute([$email]);
    $membre = $stmt->fetch();

    if ($membre && password_verify($password, $membre['mot_de_passe'])) {
        $_SESSION['membre_id'] = $membre['id'];
        $_SESSION['membre_nom'] = $membre['nom'];
        $_SESSION['membre_email'] = $membre['email'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Connexion Membre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Image de fond chaleureuse (Team spirit) */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card-custom {
            /* Effet Glassmorphism (Transparence + Flou) */
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: none;
            width: 100%;
            max-width: 420px;
        }
    </style>
</head>
<body>
    
    <div class="card card-custom shadow-lg p-4 p-md-5">
        <div class="text-center mb-4">
            <img src="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" width="60" class="mb-3">
            <h3 class="fw-bold text-success">Espace Membre</h3>
            <p class="text-muted small">Heureux de vous revoir !</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger text-center shadow-sm border-0"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control rounded-4" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Adresse Email</label>
            </div>
            <div class="form-floating mb-4">
                <input type="password" name="mot_de_passe" class="form-control rounded-4" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Mot de passe</label>
            </div>
            
            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-3 fs-5 shadow hover-scale">
                Se connecter
            </button>
        </form>

        <div class="d-grid gap-2 text-center mt-4">
            <a href="inscription.php" class="btn btn-outline-primary rounded-pill py-2 fw-bold border-2">
                Pas de compte ? Créer un compte
            </a>

            <a href="index.php" class="text-decoration-none text-secondary mt-2 py-2 fs-6">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>

            <div class="border-top pt-3 mt-2">
                <a href="login.php" class="text-decoration-none text-danger small fw-bold opacity-75">
                    <i class="bi bi-shield-lock-fill"></i> Accès Administrateur
                </a>
            </div>
        </div>

    </div>

</body>
</html>