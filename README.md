<div align="center">

# 🌟 Aujourd'hui vers Demain

### Plateforme Web de Gestion Associative

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

*Application web complète développée pour l'association "Aujourd'hui vers Demain" de Noisy-le-Sec*

[📖 Documentation](#-fonctionnalités) • [🚀 Installation](#-installation-rapide) • [📸 Captures](#-captures-décran) • [👥 Équipe](#-équipe)

---

![Aperçu de l'application](image_71fdc5.jpg)

</div>

## 📋 À Propos

> **Projet de stage** réalisé dans le cadre de notre formation en développement web.

L'association **Aujourd'hui vers Demain** accompagne les habitants du quartier dans leur quotidien : aide aux devoirs, événements de quartier, bénévolat... Ce projet vise à **digitaliser** leurs activités grâce à une plateforme moderne et intuitive.

### 🎯 Objectifs du Projet

| Objectif | Description |
|----------|-------------|
| 🗄️ **Base de données** | Conception et modélisation d'une BDD relationnelle complète |
| 💻 **Développement Full-Stack** | Interface utilisateur moderne + logique serveur robuste |
| 🔐 **Back-Office sécurisé** | Espace d'administration pour l'association |
| 📱 **Responsive Design** | Compatible mobile, tablette et desktop |

---

## ✨ Fonctionnalités

### 🌐 Site Public (Front-Office)

<table>
<tr>
<td width="50%">

**🏠 Page d'Accueil**
- Design moderne "One Page"
- Animations fluides (AOS Library)
- Mode Sombre / Clair
- Section héro dynamique

</td>
<td width="50%">

**📅 Gestion des Événements**
- Affichage des événements à venir
- Moteur de recherche intégré
- Cartes avec images et détails
- Animations au survol

</td>
</tr>
<tr>
<td>

**📝 Inscriptions**
- Formulaire Aide aux Devoirs
- Formulaire Bénévolat avec CV
- Pré-remplissage automatique
- Validation des données

</td>
<td>

**🖼️ Galerie Photos**
- Affichage dynamique par catégories
- Filtres et tri par date
- Effet Lightbox au clic
- Photos événements + galerie

</td>
</tr>
</table>

### 🔧 Espace Administrateur (Back-Office)

| Fonctionnalité | Description |
|----------------|-------------|
| 🔐 **Connexion sécurisée** | Authentification avec hachage bcrypt |
| 📊 **Dashboard** | Vue d'ensemble des événements |
| ➕ **CRUD Événements** | Créer, modifier, supprimer avec upload d'images |
| 🖼️ **Gestion Galerie** | Ajouter/supprimer des photos par catégorie |
| 📬 **Messagerie** | Centralisation des demandes (contact, inscriptions, bénévolat) |
| 🛡️ **Sécurité** | Logs de connexion et gestion des sessions |

### 🛡️ Sécurité Implémentée

- ✅ Protection CSRF sur les formulaires
- ✅ Hachage des mots de passe (`password_hash`)
- ✅ Requêtes préparées (PDO) contre les injections SQL
- ✅ Validation et échappement des données
- ✅ Protection des uploads (types de fichiers autorisés)
- ✅ Sessions sécurisées avec timeout

---

## 🛠️ Stack Technique

<div align="center">

| Catégorie | Technologies |
|-----------|--------------|
| **Back-End** | ![PHP](https://img.shields.io/badge/PHP_8-777BB4?style=flat-square&logo=php&logoColor=white) |
| **Base de Données** | ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) |
| **Front-End** | ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black) |
| **Framework CSS** | ![Bootstrap](https://img.shields.io/badge/Bootstrap_5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white) |
| **Icônes** | ![Bootstrap Icons](https://img.shields.io/badge/Bootstrap_Icons-7952B3?style=flat-square&logo=bootstrap&logoColor=white) |
| **Animations** | ![AOS](https://img.shields.io/badge/AOS-Animate_On_Scroll-blue?style=flat-square) |
| **Outils** | ![VS Code](https://img.shields.io/badge/VS_Code-007ACC?style=flat-square&logo=visualstudiocode&logoColor=white) ![Laragon](https://img.shields.io/badge/Laragon-0E83CD?style=flat-square&logo=laragon&logoColor=white) ![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git&logoColor=white) |

</div>

---

## 🚀 Installation Rapide

### Prérequis

- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur local (Laragon, WAMP, XAMPP...)

### Étapes d'installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/tanaa75/Aujourdhui-vers-demain-stage.git

# 2. Accéder au dossier
cd Aujourdhui-vers-demain-stage
```

### Configuration de la base de données

1. **Créer la base de données** dans phpMyAdmin :
   ```sql
   CREATE DATABASE asso_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Importer les tables** : Exécutez le script SQL fourni ou créez les tables manuellement

3. **Configurer la connexion** dans `db.php` :
   ```php
   $host = 'localhost';
   $dbname = 'asso_db';
   $username = 'root';
   $password = '';
   ```

### Accès à l'application

| Page | URL | Identifiants |
|------|-----|--------------|
| 🏠 Site public | `http://localhost/aujourdhui-vers-demain/` | - |
| 🔐 Connexion Admin | `http://localhost/aujourdhui-vers-demain/login.php` | `admin` / `admin123` |
| 📊 Dashboard | `http://localhost/aujourdhui-vers-demain/admin_dashboard.php` | Connexion requise |

---

## 📁 Structure du Projet

```
aujourdhui-vers-demain/
├── 📄 index.php              # Page d'accueil principale
├── 📄 db.php                 # Configuration base de données
├── 📄 navbar.php             # Barre de navigation
├── 📄 footer.php             # Pied de page
│
├── 🌐 Pages Publiques
│   ├── galerie.php           # Galerie photos dynamique
│   ├── actions.php           # Nos actions (aide aux devoirs)
│   ├── benevolat.php         # Devenir bénévole
│   └── contact.php           # Formulaire de contact
│
├── 🔐 Authentification
│   ├── login.php             # Connexion admin
│   ├── inscription.php       # Inscription membre
│   ├── connexion.php         # Connexion membre
│   └── logout.php            # Déconnexion
│
├── 🔧 Administration
│   ├── admin_dashboard.php   # Gestion événements
│   ├── admin_galerie.php     # Gestion galerie photos
│   ├── admin_add_photo.php   # Ajout de photos
│   ├── admin_messages.php    # Messagerie centralisée
│   └── admin_security.php    # Logs de sécurité
│
├── 🎨 Assets
│   ├── mobile-responsive.css # Styles responsive
│   ├── script_theme.js       # Gestion thème jour/nuit
│   └── uploads/              # Images uploadées
│
└── 📋 Documentation
    ├── README.md             # Ce fichier
    └── .htaccess             # Configuration Apache
```

---

## 📸 Captures d'écran

<div align="center">

| Page d'Accueil | Galerie Photos |
|----------------|----------------|
| ![Accueil](image_71fdc5.jpg) | *Ajoutez une capture* |

| Admin Dashboard | Mode Sombre |
|-----------------|-------------|
| *Ajoutez une capture* | *Ajoutez une capture* |

</div>

---

## 🗄️ Schéma de la Base de Données

```mermaid
erDiagram
    UTILISATEURS ||--o{ MESSAGES : envoie
    MEMBRES ||--o{ MESSAGES : envoie
    
    UTILISATEURS {
        int id PK
        varchar email
        varchar mot_de_passe
        datetime date_ajout
    }
    
    MEMBRES {
        int id PK
        varchar nom
        varchar email
        varchar mot_de_passe
        datetime date_inscription
    }
    
    EVENEMENTS {
        int id PK
        varchar titre
        text description
        date date_evenement
        varchar lieu
        varchar image
    }
    
    PHOTOS {
        int id PK
        varchar titre
        text description
        varchar image
        varchar categorie
        datetime date_ajout
    }
    
    MESSAGES {
        int id PK
        varchar nom
        varchar email
        text message
        datetime date_envoi
    }
```

---

## 👥 Équipe

<div align="center">

| Développeur | Rôle |
|-------------|------|
| **CA TANAVONG** | Développeur Full-Stack |
| **BEDJOU AYOUB** | Développeur Full-Stack |

</div>

---

## 📄 Licence

Ce projet a été réalisé dans le cadre d'un **stage de formation**.  
Tous droits réservés © 2026 - CA TANAVONG & BEDJOU AYOUB

---

<div align="center">

**⭐ Si ce projet vous a plu, n'hésitez pas à lui donner une étoile !**

[![Made with ❤️](https://img.shields.io/badge/Made%20with-❤️-red?style=for-the-badge)](https://github.com/tanaa75)

</div>
