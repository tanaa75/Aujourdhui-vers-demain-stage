<footer class="bg-dark text-white py-5 mt-auto border-top border-secondary">
    <div class="container">
        <div class="row gy-4">
            
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-warning mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" width="30" class="me-2">
                    Aujourd'hui vers Demain
                </h5>
                <p class="text-white-50">
                    Notre mission est de renforcer les liens sociaux et d'apporter une aide concrète aux habitants de Noisy-le-Sec.
                </p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-white fs-5 opacity-75 hover-opacity-100">📘</a>
                    <a href="#" class="text-white fs-5 opacity-75 hover-opacity-100">📷</a>
                    <a href="#" class="text-white fs-5 opacity-75 hover-opacity-100">🐦</a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-3">Navigation</h5>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2"><a href="index.php" class="text-decoration-none text-white-50 hover-text-white">🏠 Accueil</a></li>
                    <li class="mb-2"><a href="index.php#events" class="text-decoration-none text-white-50 hover-text-white">📅 Événements</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-decoration-none text-white-50 hover-text-white">✉️ Contact</a></li>
                    <li class="mb-2"><a href="login.php" class="text-decoration-none text-white-50 hover-text-white">🔒 Espace Admin</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3">📍 Nous trouver</h5>
                <p class="text-white-50 mb-1">116 rue de l'Avenir</p>
                <p class="text-white-50 mb-3">93130 Noisy-le-Sec</p>
                <p class="text-white-50 mb-1">📞 01 23 45 67 89</p>
                <p class="text-white-50">📧 contact@asso-noisy.fr</p>
            </div>

            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3">Restez informé</h5>
                <p class="text-white-50 small">Recevez nos dernières actualités.</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-dark text-white border-secondary" placeholder="Votre email">
                    <button class="btn btn-warning" type="button">OK</button>
                </div>
            </div>

        </div>

        <div class="border-top border-secondary pt-4 mt-4 text-center text-white-50 small">
            &copy; <?= date('Y') ?> Association Aujourd'hui vers Demain - Tous droits réservés.
        </div>
    </div>
</footer>

<style>
    /* Petit effet hover sur les liens du footer */
    .hover-text-white:hover { color: white !important; padding-left: 5px; transition: 0.3s; }
    .hover-opacity-100:hover { opacity: 1 !important; transform: scale(1.2); transition: 0.3s; }
</style>