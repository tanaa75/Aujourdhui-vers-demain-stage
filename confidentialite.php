<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Politique de Confidentialité - Aujourd'hui vers Demain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" type="image/png">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="card shadow-sm border-0 p-5">
            <h1 class="mb-4 fw-bold text-success">Politique de Confidentialité</h1>
            <p class="lead">L'association <strong>Aujourd'hui vers Demain</strong> s'engage à protéger la vie privée de ses membres et utilisateurs conformément au Règlement Général sur la Protection des Données (RGPD).</p>

            <hr class="my-4">

            <section class="mb-4">
                <h4 class="fw-bold">1. Les données que nous collectons</h4>
                <p>Dans le cadre de nos activités (aide aux devoirs, bénévolat, contact), nous sommes amenés à collecter les informations suivantes via nos formulaires :</p>
                <ul>
                    <li><strong>Identité :</strong> Nom, Prénom, Classe de l'enfant.</li>
                    <li><strong>Coordonnées :</strong> Adresse email, Numéro de téléphone.</li>
                    <li><strong>Professionnel :</strong> CV pour les candidatures bénévoles.</li>
                </ul>
            </section>

            <section class="mb-4">
                <h4 class="fw-bold">2. Utilisation des données</h4>
                <p>Vos données sont utilisées exclusivement pour :</p>
                <ul>
                    <li>Gérer les inscriptions à l'aide aux devoirs.</li>
                    <li>Traiter les candidatures de bénévolat.</li>
                    <li>Répondre à vos demandes de contact.</li>
                    <li>Vous envoyer des informations sur la vie de l'association (si vous l'avez accepté).</li>
                </ul>
                <p class="text-danger">⚠️ Nous ne vendons ni ne louons jamais vos données à des tiers.</p>
            </section>

            <section class="mb-4">
                <h4 class="fw-bold">3. Durée de conservation</h4>
                <p>Les données sont conservées uniquement le temps nécessaire à la réalisation des finalités citées ci-dessus, et pour une durée maximale de <strong>3 ans</strong> après le dernier contact.</p>
            </section>

            <section class="mb-4">
                <h4 class="fw-bold">4. Sécurité</h4>
                <p>Nous mettons en œuvre des mesures de sécurité techniques (mots de passe hachés, accès sécurisé à l'administration) pour protéger vos données contre tout accès non autorisé.</p>
            </section>

            <section class="mb-4">
                <h4 class="fw-bold">5. Vos droits</h4>
                <p>Conformément à la loi, vous disposez d'un droit d'accès, de rectification et de suppression de vos données.</p>
                <p>Pour exercer ce droit, contactez-nous par mail à : <strong>contact@asso-noisy.fr</strong> ou par courrier au 116 rue de l'Avenir, 93130 Noisy-le-Sec.</p>
            </section>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script_theme.js"></script>
</body>
</html>