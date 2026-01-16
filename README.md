# Plateforme Web - Association "Aujourd'hui vers Demain"

Ce dépôt contient le code source de la solution web développée pour l'association **Aujourd'hui vers Demain** (Noisy-le-Sec). Ce projet a été réalisé dans le cadre du BTS SIO, répondant à un besoin de digitalisation des processus de l'association (gestion des bénévoles, communication événementielle et aide aux devoirs).

![Aperçu de l'application](image_71fdc5.jpg)

## 👥 Équipe de développement
* **CA TANAVONG**
* **BEDJOU AYOUB**

---

## 🛠️ Stack Technique

Le projet repose sur une architecture web standardisée, sans dépendance lourde, garantissant maintenabilité et performance.

* **Back-End :** PHP 8 (Natif)
* **Base de Données :** MySQL / MariaDB
* **Front-End :** HTML5, CSS3, Bootstrap 5.3
* **Scripting Client :** JavaScript (ES6+)
* **Bibliothèques :** AOS (Animate On Scroll) pour les interactions UI.

---

## 💻 Fonctionnalités Implémentées

### 1. Interface Publique (Front-Office)
L'interface a été conçue sous forme de **One Page** pour optimiser le parcours utilisateur.
* **UX/UI Design :** Navigation fluide (Smooth Scroll), Design Responsive (Mobile First).
* **Accessibilité :** Module de **Thème Sombre/Clair (Dark Mode)** avec persistance des préférences (LocalStorage).
* **Modules Interactifs :**
    * Moteur de recherche d'événements (Requêtes SQL préparées `LIKE`).
    * Formulaires dynamiques (Candidature Bénévolat & Inscription Aide aux Devoirs).
    * Intégration API Google Maps (iFrame).

### 2. Interface d'Administration (Back-Office)
Espace sécurisé dédié à la gestion de contenu (CMS sur-mesure).
* **Authentification :** Sécurisation des accès via hachage de mots de passe (`password_hash` / `password_verify`).
* **Gestion des Événements (CRUD) :**
    * Création, Lecture, Mise à jour, Suppression.
    * Gestion de l'upload d'images serveur.
* **Centralisation des Messages :** Réception et tri des soumissions de formulaires (Contact, Bénévolat, Inscriptions) en base de données.
* **Sécurité du Compte :** Module de réinitialisation de mot de passe administrateur.

---

## ⚙️ Guide d'Installation (Déploiement Local)

Pour déployer le projet sur un environnement de développement (Laragon, XAMPP, WAMP) :

### 1. Configuration des fichiers
Cloner le dépôt dans le répertoire public du serveur web.

```bash
git clone [https://github.com/tanaa75/Aujourdhui-vers-demain-stage.git](https://github.com/tanaa75/Aujourdhui-vers-demain-stage.git)
