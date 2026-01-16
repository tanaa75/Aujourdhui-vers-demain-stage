<?php
session_start();
require_once 'db.php';

// Vérification de sécurité
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Suppression
if (isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$_POST['delete_id']]);
    header("Location: admin_messages.php");
    exit();
}

// Récupération des messages
$query = $pdo->query("SELECT * FROM messages ORDER BY date_envoi DESC");
$messages = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Messagerie Admin - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-message { transition: transform 0.2s; border: none; border-left: 5px solid #0d6efd; }
        .card-message:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .type-benevole { border-left-color: #dc3545; }
        .type-devoirs { border-left-color: #ffc107; }
        .icon-box { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        
        /* Petit message toast pour la copie */
        #copyToast { visibility: hidden; min-width: 250px; background-color: #333; color: #fff; text-align: center; border-radius: 2px; padding: 16px; position: fixed; z-index: 1; left: 50%; bottom: 30px; transform: translateX(-50%); font-size: 17px; }
        #copyToast.show { visibility: visible; -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
        @keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;} }
        @keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;} }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="bi bi-inbox-fill text-primary"></i> Boîte de réception</h2>
            <span class="badge bg-primary rounded-pill fs-6"><?= count($messages) ?> messages</span>
        </div>

        <?php if (count($messages) == 0): ?>
            <div class="alert alert-info text-center p-5 rounded-4 shadow-sm">
                <i class="bi bi-chat-square-dots display-1"></i>
                <p class="mt-3 fs-5">Aucun message pour le moment.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($messages as $msg): ?>
                    <?php 
                        // --- ANALYSE ---
                        $border_class = "border-primary";
                        $icon = "bi-envelope";
                        $bg_icon = "bg-primary";
                        
                        if (strpos($msg['message'], 'BÉNÉVOLE') !== false) {
                            $border_class = "type-benevole";
                            $icon = "bi-heart-fill";
                            $bg_icon = "bg-danger";
                        } elseif (strpos($msg['message'], 'DEVOIRS') !== false) {
                            $border_class = "type-devoirs";
                            $icon = "bi-book-fill";
                            $bg_icon = "bg-warning";
                        }

                        $cv_link = null;
                        if (preg_match('/http\S+/', $msg['message'], $matches)) {
                            $cv_link = $matches[0];
                            $msg_display = str_replace($cv_link, '', $msg['message']);
                            $msg_display = str_replace("📄 TÉLÉCHARGER LE CV :", "", $msg_display);
                        } else {
                            $msg_display = $msg['message'];
                        }

                        $phone = "";
                        if (preg_match('/Téléphone : (\d+)/', $msg['message'], $matches_tel)) {
                            $phone = $matches_tel[1];
                        }
                    ?>

                    <div class="col-12 mb-4">
                        <div class="card card-message shadow-sm <?= $border_class ?>">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box <?= $bg_icon ?> text-white shadow">
                                            <i class="bi <?= $icon ?> fs-5"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-0"><?= htmlspecialchars($msg['nom']) ?></h5>
                                            <div class="text-muted small">
                                                <i class="bi bi-clock"></i> <?= date('d/m/Y à H:i', strtotime($msg['date_envoi'])) ?>
                                                &bull; 
                                                <span style="cursor: pointer;" onclick="copyToClipboard('<?= htmlspecialchars($msg['email']) ?>')" title="Clique pour copier">
                                                    <i class="bi bi-envelope-at"></i> <?= htmlspecialchars($msg['email']) ?> <i class="bi bi-clipboard"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <form method="POST" onsubmit="return confirm('Supprimer ?');">
                                        <input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>

                                <hr class="my-3 opacity-10">
                                <div class="bg-light p-3 rounded-3 mb-3">
                                    <pre class="mb-0" style="font-family: inherit; white-space: pre-wrap; color: #555;"><?= htmlspecialchars(trim($msg_display)) ?></pre>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=Re: Votre demande&body=Bonjour <?= htmlspecialchars($msg['nom']) ?>,..." 
                                       class="btn btn-primary btn-sm px-3 rounded-pill">
                                        <i class="bi bi-reply-fill"></i> Répondre
                                    </a>

                                    <button onclick="copyToClipboard('<?= htmlspecialchars($msg['email']) ?>')" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                                        <i class="bi bi-clipboard-check"></i> Copier Email
                                    </button>

                                    <?php if (!empty($phone)): ?>
                                        <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-success btn-sm px-3 rounded-pill">
                                            <i class="bi bi-telephone-fill"></i> Appeler
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($cv_link): ?>
                                        <a href="<?= htmlspecialchars($cv_link) ?>" target="_blank" class="btn btn-dark btn-sm px-3 rounded-pill">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> Voir CV
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="copyToast">✅ Adresse email copiée !</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonction JavaScript pour copier le texte
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                var x = document.getElementById("copyToast");
                x.className = "show";
                setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
            }, function(err) {
                console.error('Erreur lors de la copie : ', err);
            });
        }
    </script>
</body>
</html>