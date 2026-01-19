<?php
session_start();
require_once 'db.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE identifiant = ?");
    $stmt->execute([$identifiant]);
    $user = $stmt->fetch();

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['identifiant'] = $user['identifiant'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error_msg = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* MÊME IMAGE DE FOND POUR LA COHÉRENCE */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .card-custom {
            /* EFFET GLASSMORPHISM UNIFIÉ */
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
            <i class="bi bi-shield-lock-fill text-primary" style="font-size: 3rem;"></i>
            <h3 class="fw-bold text-primary mt-2">Espace Administrateur</h3>
            <p class="text-muted small">Accès sécurisé réservé à la gestion</p>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?= $error_msg ?></div>
            </div>
        <?php endif; ?>

        <form method="POST">
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-4" id="identifiant" name="identifiant" placeholder="Admin" required>
                <label for="identifiant"><i class="bi bi-person-badge"></i> Identifiant</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control rounded-4" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
                <label for="mot_de_passe"><i class="bi bi-key"></i> Mot de passe</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-3 fs-5 shadow hover-scale">
                SE CONNECTER
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-outline-secondary rounded-pill py-2 px-4 fw-bold border-2">
                <i class="bi bi-arrow-left"></i> Retour au site public
            </a>
        </div>

    </div>

</body>
</html>