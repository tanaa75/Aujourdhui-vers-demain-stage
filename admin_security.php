<?php
session_start();
// Vérification de sécurité : si pas connecté, on renvoie au login
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // 1. On récupère les infos de l'admin connecté
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // 2. On vérifie l'ancien mot de passe
    if (!password_verify($current_pass, $user['mot_de_passe'])) {
        $message = "<div class='alert alert-danger'>❌ L'ancien mot de passe est incorrect.</div>";
    } 
    // 3. On vérifie que les deux nouveaux sont pareils
    elseif ($new_pass !== $confirm_pass) {
        $message = "<div class='alert alert-danger'>❌ Les nouveaux mots de passe ne correspondent pas.</div>";
    } 
    // 4. Si tout est bon, on met à jour !
    else {
        $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
        $stmt->execute([$new_hash, $_SESSION['user_id']]);
        $message = "<div class='alert alert-success'>✅ Mot de passe modifié avec succès !</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sécurité du compte</title>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h3 class="mb-0">🔐 Modifier mon mot de passe</h3>
                    </div>
                    <div class="card-body">
                        <?= $message ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger">Enregistrer le changement</button>
                                <a href="admin_dashboard.php" class="btn btn-light">Annuler</a>
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