<?php
/**
 * ===========================================
 * TABLEAU DE BORD ADMINISTRATEUR
 * ===========================================
 * 
 * Page principale de l'espace admin.
 * Permet de gérer les événements de l'association.
 * 
 * Fonctionnalités :
 * - Liste de tous les événements
 * - Bouton pour ajouter un événement
 * - Boutons pour modifier/supprimer chaque événement
 * 
 * Sécurité :
 * - Accessible uniquement aux administrateurs connectés
 */

// Démarrage de la session
session_start();

// Vérification de sécurité : redirection si non connecté
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Connexion à la base de données
require_once 'db.php';

// ========== SUPPRESSION D'UN ÉVÉNEMENT ==========
// Si le paramètre 'delete' est présent dans l'URL, on supprime l'événement
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM evenements WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    
    // Redirection avec message de confirmation
    header("Location: admin_dashboard.php?msg=deleted");
    exit();
}

// Récupération de tous les événements (du plus récent au plus ancien)
$events = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="mobile-responsive.css">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
    <title>Dashboard Admin</title>
    <style>
        body { transition: background-color 0.5s, color 0.5s; }
        .table { transition: color 0.5s; }
    </style>
</head>
<body class="bg-body-tertiary"> <?php include 'navbar.php'; ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between mb-4">
            <h1>Gestion des Événements</h1>
            <a href="admin_add_event.php" class="btn btn-primary">+ Nouvel Événement</a>
        </div>
        <?php if(isset($_GET['msg'])) echo "<div class='alert alert-success'>Action effectuée !</div>"; ?>
        
        <div class="table-responsive">
            <table class="table table-hover shadow rounded overflow-hidden">
                <thead class="table-dark"><tr><th>Titre</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['titre']) ?></td>
                        <td><?= date('d/m/Y', strtotime($event['date_evenement'])) ?></td>
                        <td>
                            <a href="admin_edit_event.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <a href="admin_dashboard.php?delete=<?= $event['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sûr ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>