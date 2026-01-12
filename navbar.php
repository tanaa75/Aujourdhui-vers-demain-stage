<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Aujourd'hui vers Demain</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">🏠 Accueil</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">⚙️ Gestion</a></li>
            <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Déconnexion</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">🔒 Connexion</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>