<?php
require_once 'db.php';

try {
    // 1. On supprime l'ancien utilisateur "admin" pour ne pas avoir de doublons
    $pdo->exec("DELETE FROM utilisateurs WHERE identifiant = 'admin'");

    // 2. On génère le mot de passe crypté via TON serveur
    // Le mot de passe sera : admin123
    $nouveau_mdp_hache = password_hash("admin123", PASSWORD_DEFAULT);

    // 3. On insère le nouvel admin
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (identifiant, mot_de_passe) VALUES (?, ?)");
    $stmt->execute(['admin', $nouveau_mdp_hache]);

    echo "<h1 style='color:green'>✅ Succès !</h1>";
    echo "<p>L'utilisateur <b>admin</b> a été recréé.</p>";
    echo "<p>Nouveau mot de passe : <b>admin123</b></p>";
    echo "<a href='login.php'>Clique ici pour te connecter</a>";

} catch (PDOException $e) {
    echo "<h1 style='color:red'>Erreur</h1>";
    echo "Détail : " . $e->getMessage();
}
?>