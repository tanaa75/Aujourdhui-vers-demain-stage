<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';

// Supprimer un message
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_messages.php?msg=deleted");
    exit();
}

// Récupérer les messages du plus récent au plus vieux
$messages = $pdo->query("SELECT * FROM messages ORDER BY date_envoi DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h1 class="mb-4">Boîte de réception</h1>

        <?php if (count($messages) == 0): ?>
            <div class="alert alert-info">Aucun message pour le moment.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($messages as $msg): ?>
                <div class="col-12 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>De : <?= htmlspecialchars($msg['nom']) ?> (<?= htmlspecialchars($msg['email']) ?>)</strong>
                            <small class="text-muted"><?= date('d/m/Y à H:i', strtotime($msg['date_envoi'])) ?></small>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn btn-primary btn-sm">Répondre</a>
                            <a href="admin_messages.php?delete=<?= $msg['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce message ?')">Supprimer</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="mt-3">
            <a href="admin_dashboard.php">← Retour au tableau de bord</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>