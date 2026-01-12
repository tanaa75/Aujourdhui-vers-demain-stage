<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM evenements WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: admin_dashboard.php?msg=deleted");
    exit();
}
$events = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard Admin</title>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between mb-4">
            <h1>Gestion des Événements</h1>
            <a href="admin_add_event.php" class="btn btn-primary">+ Nouvel Événement</a>
        </div>
        <?php if(isset($_GET['msg'])) echo "<div class='alert alert-success'>Action effectuée !</div>"; ?>
        <table class="table table-hover bg-white shadow rounded">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>