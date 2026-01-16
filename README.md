# Plateforme Web - Association "Aujourd'hui vers Demain"

> **Projet réalisé dans le cadre d'un stage en développement web.**

Ce dépôt regroupe l'intégralité du travail technique effectué pour la digitalisation de l'association **Aujourd'hui vers Demain** (Noisy-le-Sec). Notre mission a consisté à concevoir, développer et déployer une solution complète incluant un **site web dynamique** et une **base de données relationnelle**.

![Aperçu de l'application](image_71fdc5.jpg)

## 👥 Équipe de développement
* **CA TANAVONG**
* **BEDJOU AYOUB**

---

## 🎯 Objectifs du Stage

Durant ce projet, nous avons géré l'ensemble du cycle de développement :
1.  **Conception BDD :** Modélisation et création de la base de données (Tables, Relations, Contraintes).
2.  **Développement Full-Stack :** Création de l'interface utilisateur (Front) et de la logique serveur (Back).
3.  **Administration :** Gestion des données via PhpMyAdmin et création d'un Back-Office sécurisé pour l'association.

---

## 🛠️ Stack Technique

* **Langage Back-End :** PHP 8 (Natif)
* **Base de Données :** MySQL (Gestion via SQL & PhpMyAdmin)
* **Front-End :** HTML5, CSS3, Bootstrap 5.3
* **Scripting :** JavaScript (ES6+)
* **Outils :** Visual Studio Code, Laragon/WAMP, Git.

---

## 💻 Fonctionnalités Développées

### 1. Gestion de la Base de Données & Back-Office
Nous avons développé une interface d'administration sécurisée permettant à l'association de gérer ses données en toute autonomie :
* **Authentification sécurisée** (Hachage `password_hash`).
* **CRUD complet** sur les événements (Ajout, Modification, Suppression avec upload d'images).
* **Centralisation des messages** : Stockage en base de données des formulaires de contact et d'inscriptions.

### 2. Site Web Public (Front-Office)
Une interface moderne "One Page" pour les visiteurs :
* **UX/UI :** Design responsive, Mode Sombre/Clair, Animations (AOS).
* **Interactions avec la BDD :** Moteur de recherche d'événements, formulaires d'inscription (Aide aux devoirs & Bénévolat).
* **Services Tiers :** Intégration Google Maps.

---

## ⚙️ Installation du Projet

1.  **Cloner le dépôt** :
    ```bash
    git clone [https://github.com/tanaa75/Aujourdhui-vers-demain-stage.git](https://github.com/tanaa75/Aujourdhui-vers-demain-stage.git)
    ```
2.  **Base de Données** :
    * Importer le script SQL fourni dans votre SGBD (ex: PhpMyAdmin).
    * Nom de la base : `asso_db`.
3.  **Configuration** :
    * Vérifier les identifiants BDD dans le fichier `db.php`.
4.  **Accès Admin** :
    * URL : `/login.php`
    * Login défaut : `admin` / `admin123`

---

© 2026 - CA TANAVONG & BEDJOU AYOUB.
