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
        header("Location: admin_dashboard.php"); // Redirection vers le tableau de bord
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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }

        .card-login {
            background: rgba(255, 255, 255, 0.95); /* Blanc quasi opaque */
            backdrop-filter: blur(10px); /* Effet de flou derrière */
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            background: #0d6efd; /* Bleu Bootstrap */
            padding: 30px;
            text-align: center;
            color: white;
        }

        .form-floating:focus-within {
            z-index: 2;
        }
        
        .btn-login {
            font-weight: bold;
            letter-spacing: 1px;
            padding: 12px;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="container px-4">
        <div class="d-flex justify-content-center">
            
            <div class="card card-login" data-aos="zoom-in" data-aos-duration="1000">
                
                <div class="login-header">
                    <i class="bi bi-shield-lock-fill display-1"></i>
                    <h3 class="fw-bold mt-3 mb-0">Espace Admin</h3>
                    <p class="small opacity-75 mb-0">Accès sécurisé réservé</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    
                    <?php if (!empty($error_msg)): ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?= $error_msg ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="identifiant" name="identifiant" placeholder="Admin" required>
                            <label for="identifiant"><i class="bi bi-person-badge"></i> Identifiant</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
                            <label for="mot_de_passe"><i class="bi bi-key"></i> Mot de passe</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-login rounded-pill shadow">
                            SE CONNECTER
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="index.php" class="text-decoration-none text-muted small">
                            <i class="bi bi-arrow-left"></i> Retour au site public
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>