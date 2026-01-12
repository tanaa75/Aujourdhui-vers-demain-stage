<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';

if (!isset($_GET['id'])) { header("Location: admin_dashboard.php"); exit(); }
$stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = ?");
$stmt->execute([$_GET['id']]);
$event = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("UPDATE evenements SET titre=?, description=?, date_evenement=?, lieu=? WHERE id=?");
    $stmt->execute([$_POST['titre'], $_POST['description'], $_POST['date_evenement'], $_POST['lieu'], $_GET['id']]);
    header("Location: admin_dashboard.php?msg=updated"); exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Modifier Événement</title>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    <div class="container py-5">
        <div class="card shadow col-md-8 mx-auto">
            <div class="card-header bg-warning"><h3>Modifier l'événement</h3></div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3"><label>Titre</label><input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($event['titre']) ?>" required></div>
                    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($event['description']) ?></textarea></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label>Date</label><input type="datetime-local" name="date_evenement" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['date_evenement'])) ?>" required></div>
                        <div class="col-6 mb-3"><label>Lieu</label><input type="text" name="lieu" class="form-control" value="<?= htmlspecialchars($event['lieu']) ?>" required></div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Modifier</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>