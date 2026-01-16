# 🌍 Aujourd'hui vers Demain - Site Web Associatif

> Site web dynamique réalisé pour l'association "Aujourd'hui vers Demain" à Noisy-le-Sec.  
> Projet développé dans le cadre de mon stage de BTS SIO.

![Aperçu du site](image_71fdc5.jpg)

## 🚀 À propos du projet

Ce projet est une solution web complète permettant à l'association de gérer sa communication et ses bénévoles. Il se compose d'une **interface publique moderne (One Page)** et d'un **panneau d'administration sécurisé**.

### 🛠️ Technologies utilisées
* **Langage :** PHP 8 (Natif, sans framework)
* **Base de données :** MySQL
* **Front-end :** HTML5, CSS3, Bootstrap 5
* **Animations :** AOS Library (Animate On Scroll)
* **Scripting :** JavaScript (Gestion du Dark Mode)

---

## ✨ Fonctionnalités Principales

### 🎨 Partie Publique (Utilisateurs)
* **Design One Page :** Navigation fluide entre les sections (Accueil, Actions, Bénévolat).
* **Mode Sombre / Clair :** Thème dynamique avec persistance du choix utilisateur.
* **Animations :** Effets d'apparition au scroll et survol 3D sur les cartes.
* **Interactivité :**
    * Barre de recherche d'événements (Moteur SQL `LIKE`).
    * Formulaire d'inscription "Aide aux devoirs".
    * Formulaire de candidature "Devenir Bénévolat".
* **Google Maps :** Intégration sur la section Contact.

### ⚙️ Partie Administration (Back-Office)
* **Authentification Sécurisée :** Système de login avec hachage de mot de passe (`password_hash`).
* **Dashboard :** Vue d'ensemble des événements.
* **CRUD Événements :** Ajouter, Modifier et Supprimer des événements avec **Upload d'images**.
* **Messagerie :** Réception centralisée des demandes de contact, inscriptions et candidatures bénévoles.
* **Sécurité Admin :** Possibilité pour l'admin de changer son propre mot de passe.

---

## 💻 Installation (Localhost)

1.  **Cloner le projet** dans votre dossier serveur (ex: `www` de Laragon ou `htdocs` de XAMPP).
2.  **Base de données :**
    * Ouvrir PhpMyAdmin.
    * Créer une base nommée `asso_db`.
    * Importer le script SQL de structure.
3.  **Configuration :**
    * Vérifier les identifiants dans le fichier `db.php`.
4.  **Connexion Admin :**
    * URL : `/login.php`
    * Identifiant par défaut : `admin` / `admin123`

---

## 👤 Auteur
Projet réalisé par **[Ton Prénom] [Ton Nom]**.
