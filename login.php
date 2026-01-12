<?php
session_start();
require_once 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE identifiant = ?");
    $stmt->execute([$_POST['identifiant']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Identifiants incorrects.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Connexion</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Espace Admin</h3>
        <?= $message ?>
        <form method="POST">
            <div class="mb-3"><label>Identifiant</label><input type="text" name="identifiant" class="form-control" required></div>
            <div class="mb-3"><label>Mot de passe</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <div class="text-center mt-3"><a href="index.php">Retour au site</a></div>
    </div>
</body>
</html>