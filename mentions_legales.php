<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Mentions Légales - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="card shadow-sm border-0 p-5">
            <h1 class="mb-4 fw-bold text-primary">Mentions Légales</h1>
            
            <section class="mb-5">
                <h4 class="text-secondary border-bottom pb-2">1. Éditeur du site</h4>
                <p>
                    Le site internet <strong>Aujourd'hui vers Demain</strong> est édité par l'association régie par la loi du 1er juillet 1901.<br>
                    <strong>Nom de l'association :</strong> Aujourd'hui vers Demain<br>
                    <strong>Siège social :</strong> 116 rue de l'Avenir, 93130 Noisy-le-Sec<br>
                    <strong>Email :</strong> contact@asso-noisy.fr<br>
                    <strong>Téléphone :</strong> 01 23 45 67 89
                </p>
            </section>

            <section class="mb-5">
                <h4 class="text-secondary border-bottom pb-2">2. Directeur de la publication</h4>
                <p>Le directeur de la publication est le Président de l'association.</p>
            </section>

            <section class="mb-5">
                <h4 class="text-secondary border-bottom pb-2">3. Hébergement</h4>
                <p>
                    Ce site est hébergé par :<br>
                    <em>(Indiquer ici l'hébergeur réel si le site est en ligne, ex: OVH, Ionos...)</em><br>
                    Adresse : 2 rue Kellermann, 59100 Roubaix - France.<br>
                    Site web : www.ovh.com
                </p>
            </section>

            <section class="mb-5">
                <h4 class="text-secondary border-bottom pb-2">4. Propriété intellectuelle</h4>
                <p>
                    L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.
                </p>
            </section>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>