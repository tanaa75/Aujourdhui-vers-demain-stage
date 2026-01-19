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
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <title>Connexion</title>
    <style>body { transition: background-color 0.5s; }</style>
</head>
<body class="bg-body-tertiary d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
             <img src="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" width="50" class="mb-3">
             <h3>Espace Admin</h3>
        </div>
        <?= $message ?>
        <form method="POST">
            <div class="mb-3"><label>Identifiant</label><input type="text" name="identifiant" class="form-control" required></div>
            <div class="mb-3"><label>Mot de passe</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <div class="text-center mt-3"><a href="index.php">Retour au site</a></div>
    </div>
    
    <script src="script_theme.js"></script>
</body>
</html>