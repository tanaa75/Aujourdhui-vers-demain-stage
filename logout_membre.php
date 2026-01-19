<?php
session_start();
// On détruit uniquement les variables membres, pas admin si jamais les deux sont connectés (rare mais possible)
unset($_SESSION['membre_id']);
unset($_SESSION['membre_nom']);
unset($_SESSION['membre_email']);

// Si on veut tout vider : session_destroy();
header("Location: index.php");
exit();
?>