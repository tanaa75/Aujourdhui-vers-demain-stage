<?php
session_start();
// Vérification de sécurité
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
require_once 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $date = $_POST['date_evenement'];
    $lieu = "116 rue de l'Avenir, 93130 Noisy-le-Sec"; // On force l'adresse ici aussi pour la sécurité
    
    // --- GESTION DE L'IMAGE ---
    $image_filename = NULL; // Par défaut, pas d'image

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // On vérifie que c'est bien une image
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // On renomme pour éviter les conflits (event_timestamp.jpg)
            $new_name = "event_" . time() . "." . $ext;
            
            // On déplace le fichier
            if (move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $new_name)) {
                $image_filename = $new_name;
            }
        } else {
            $message = "<div class='alert alert-warning'>⚠️ Format d'image non supporté (JPG, PNG, WEBP uniquement).</div>";
        }
    }

    // Si pas d'erreur critique, on enregistre
    if (strpos($message, 'alert-warning') === false) {
        try {
            $stmt = $pdo->prepare("INSERT INTO evenements (titre, description, date_evenement, lieu, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$titre, $description, $date, $lieu, $image_filename]);
            
            // Redirection vers le dashboard avec succès
            header("Location: admin_dashboard.php?msg=added");
            exit();
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>❌ Erreur SQL : " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un événement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body {
            /* MÊME FOND QUE LE LOGIN ET INSCRIPTION */
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .card-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .form-control, .form-control:focus {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }
        
        /* Input Readonly (Lieu) un peu grisé */
        .input-locked {
            background-color: #e9ecef !important;
            cursor: not-allowed;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="card card-custom p-4 p-md-5">
                    
                    <div class="d-flex align-items-center mb-4 border-bottom pb-3">
                        <i class="bi bi-calendar-plus-fill text-primary display-6 me-3"></i>
                        <h2 class="fw-bold mb-0">Nouvel Événement</h2>
                    </div>

                    <?= $message ?>

                    <form method="POST" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Titre de l'événement</label>
                                <input type="text" name="titre" class="form-control form-control-lg rounded-3" placeholder="Ex: Fête de quartier..." required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date et Heure</label>
                                <input type="datetime-local" name="date_evenement" class="form-control form-control-lg rounded-3" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Lieu <small class="text-muted">(Fixe)</small></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                    <input type="text" name="lieu" class="form-control form-control-lg input-locked" value="116 rue de l'Avenir, 93130 Noisy-le-Sec" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control rounded-3" rows="4" placeholder="Détails de l'événement..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Image de couverture (Optionnel)</label>
                            <input type="file" name="image" class="form-control form-control-lg rounded-3" accept="image/*">
                            <div class="form-text">Formats acceptés : JPG, PNG, WEBP.</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="admin_dashboard.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                                <i class="bi bi-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>