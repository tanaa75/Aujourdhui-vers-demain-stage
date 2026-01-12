<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $stmt = $pdo->prepare("INSERT INTO evenements (titre, description, date_evenement, lieu) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['titre'], $_POST['description'], $_POST['date_evenement'], $_POST['lieu']]);
        $message = "<div class='alert alert-success'>Ajouté avec succès !</div>";
    } catch (PDOException $e) { $message = "<div class='alert alert-danger'>Erreur !</div>"; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ajout Événement</title>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    <div class="container py-5">
        <div class="card shadow col-md-8 mx-auto">
            <div class="card-header bg-primary text-white"><h3>Ajouter un événement</h3></div>
            <div class="card-body">
                <?= $message ?>
                <form method="POST">
                    <div class="mb-3"><label>Titre</label><input type="text" name="titre" class="form-control" required></div>
                    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label>Date</label><input type="datetime-local" name="date_evenement" class="form-control" required></div>
                        <div class="col-6 mb-3"><label>Lieu</label><input type="text" name="lieu" class="form-control" required></div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>