<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
        <img src="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" alt="Logo" width="35" height="35" class="d-inline-block align-text-top me-2">
        Aujourd'hui vers Demain
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">🏠 Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">✉️ Contact</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">⚙️ Gestion</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="admin_dashboard.php">Gérer les événements</a></li>
                    <li><a class="dropdown-item" href="admin_messages.php">Voir les messages</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="admin_security.php">🔐 Sécurité</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Déconnexion</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">🔒 Connexion</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>