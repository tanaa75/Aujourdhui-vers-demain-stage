<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 sticky-top shadow">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
        <img src="https://cdn-icons-png.flaticon.com/512/2904/2904869.png" alt="Logo" width="35" height="35" class="d-inline-block align-text-top me-2 animate-logo">
        Aujourd'hui vers Demain
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php">🏠 Accueil</a></li>
        
        <li class="nav-item"><a class="nav-link" href="actions.php">📚 Nos Actions</a></li>
        
        <li class="nav-item"><a class="nav-link" href="benevolat.php">🤝 Bénévolat</a></li>

        <li class="nav-item"><a class="nav-link" href="contact.php">✉️ Contact</a></li>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle btn btn-warning text-dark px-3 rounded-pill fw-bold shadow-sm" href="#" role="button" data-bs-toggle="dropdown">
                    ⚙️ ADMIN
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><a class="dropdown-item" href="admin_dashboard.php">📅 Gérer événements</a></li>
                    <li><a class="dropdown-item" href="admin_messages.php">📬 Messagerie</a></li>
                    <li><a class="dropdown-item" href="admin_security.php">🔐 Sécurité</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Déconnexion</a></li>
                </ul>
            </li>

        <?php elseif (isset($_SESSION['membre_id'])): ?>
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle btn bg-white text-dark px-3 rounded-pill fw-bold shadow-sm border-0" href="#" role="button" data-bs-toggle="dropdown" style="transition: transform 0.2s;">
                    👤 <?= htmlspecialchars($_SESSION['membre_nom']) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><span class="dropdown-item-text text-muted small"><i class="bi bi-envelope"></i> <?= htmlspecialchars($_SESSION['membre_email']) ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger fw-bold" href="logout_membre.php"><i class="bi bi-box-arrow-right"></i> Se déconnecter</a></li>
                </ul>
            </li>

        <?php else: ?>
            <li class="nav-item ms-2">
                <a class="btn btn-outline-light rounded-pill px-4 fw-bold" href="connexion.php">Se connecter</a>
            </li>
        <?php endif; ?>

        <li class="nav-item ms-2">
            <button class="btn btn-outline-light rounded-circle" onclick="toggleTheme()" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <span id="theme-icon">🌙</span>
            </button>
        </li>
      </ul>
    </div>
  </div>
</nav>