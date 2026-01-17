# 📋 Plateforme عدل همك - Marketplace de Services Mauritanien

## 📖 Description du Projet

**عدل همك** (Adel Hamak) est une plateforme web complète de marketplace de services conçue spécifiquement pour la Mauritanie. Elle permet de connecter les prestataires de services avec les clients potentiels à travers toutes les wilayas du pays.

La plateforme est développée en **PHP natif** avec **MySQL** comme base de données, et utilise une interface utilisateur en **arabe (RTL)** avec la police **Tajawal** pour une expérience utilisateur optimale.

## ✨ Fonctionnalités Principales

### 🔐 Authentification et Gestion des Utilisateurs
- **Inscription** (`signup.php`) : Création de compte avec deux types d'utilisateurs
  - **Client** : Recherche et consultation de services
  - **Prestataire** : Publication et gestion de services
- **Connexion** (`login.php`) : Authentification sécurisée avec sessions PHP
- **Déconnexion** (`logout.php`) : Destruction de session
- **Validation** : Validation côté client (JavaScript) et côté serveur (PHP)
- **Sécurité** : Mots de passe hachés avec `password_hash()` (bcrypt)

### 🏠 Page d'Accueil
- **Affichage des services** : Grille responsive de tous les services disponibles
- **Recherche dynamique** : Filtrage en temps réel par :
  - Mot-clé (titre/description)
  - Wilaya (15 wilayas de Mauritanie)
  - Catégorie de service
- **Navigation** : Barre de navigation adaptative selon l'état de connexion
- **Design** : Interface moderne avec cartes de services, ombres et animations au survol

### 📊 Tableau de Bord Utilisateur
- **Gestion des services** (`dashboard.php`) :
  - Affichage de tous les services publiés par l'utilisateur
  - Suppression de services avec confirmation
  - Statistiques personnelles
- **Différenciation par rôle** :
  - Les clients peuvent ajouter des services s'ils le souhaitent
  - Les prestataires ont un accès direct à la gestion de services

### ➕ Ajout de Services
- **Formulaire complet** (`add_service.php`) :
  - Titre du service
  - Description détaillée
  - Prix (en Ouguiya mauritanienne)
  - Catégorie (7 catégories disponibles)
  - Wilaya (localisation)
  - Image du service (upload optionnel)
- **Upload d'images** : Gestion sécurisée des fichiers avec validation d'extension
- **Validation** : Contrôles côté serveur pour tous les champs

### 🔍 Détails du Service
- **Page dédiée** (`service_details.php`) :
  - Affichage complet des informations du service
  - Image en grand format
  - Informations du prestataire
  - Prix et localisation
  - Date de publication
- **Contact WhatsApp** :
  - Bouton de contact direct via WhatsApp
  - Message pré-rempli avec référence au service
  - Gestion automatique du code pays mauritanien (+222)

### 🗂️ Catégories de Services
1. **تصميم وبرمجة** (Design et Programmation)
2. **تسويق إلكتروني** (Marketing Digital)
3. **كتابة وترجمة** (Écriture et Traduction)
4. **تدريب عن بعد** (Formation à Distance)
5. **استشارات** (Consultations)
6. **خدمات منزلية** (Services à Domicile)
7. **أخرى** (Autres)

### 🌍 Wilayas Couvertes
Toutes les 15 wilayas de Mauritanie :
- Adrar, Assaba, Brakna, Dakhlet Nouadhibou
- Gorgol, Guidimaka, Hodh Ech Chargui, Hodh El Gharbi
- Inchiri, Nouakchott-Nord, Nouakchott-Ouest, Nouakchott-Sud
- Tagant, Tiris Zemmour, Trarza

## 🗄️ Structure de la Base de Données

### Table `utilisateurs`
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- Nom_complet (VARCHAR) : Nom complet de l'utilisateur
- Email (VARCHAR, UNIQUE) : Adresse email
- Mot_de_passe (VARCHAR) : Mot de passe haché
- Telephone (VARCHAR) : Numéro de téléphone
- Conte (VARCHAR) : Type de compte (Client/Prestataire)
```

### Table `services`
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- user_id (INT, FOREIGN KEY) : Référence à l'utilisateur
- title (VARCHAR) : Titre du service
- description (TEXT) : Description détaillée
- price (DECIMAL) : Prix en Ouguiya
- category (VARCHAR) : Catégorie du service
- wilaya (VARCHAR) : Localisation
- image_path (VARCHAR) : Chemin vers l'image
- created_at (TIMESTAMP) : Date de création
```

## 📁 Structure des Fichiers

```
Web dy___Annonce des prestations1/
│
├── 📄 index.php              # Page d'accueil avec liste des services
├── 📄 signup.php             # Page d'inscription
├── 📄 login.php              # Page de connexion
├── 📄 logout.php             # Script de déconnexion
├── 📄 dashboard.php          # Tableau de bord utilisateur
├── 📄 add_service.php        # Formulaire d'ajout de service
├── 📄 service_details.php    # Page de détails d'un service
├── 📄 db.php                 # Configuration de la base de données
├── 📄 setup_db_update.php    # Script de mise à jour de la BDD
│
├── 📁 css/
│   ├── hom1_styles.css       # Styles pour la page d'accueil
│   └── inscri_styles.css     # Styles pour l'inscription
│
├── 📁 javascript/
│   └── home.js               # Logique de recherche dynamique
│
├── 📁 uploads/               # Dossier pour les images uploadées
│
└── 📁 lib/                   # Bibliothèques tierces (si nécessaire)
```

## 🚀 Installation et Configuration

### Prérequis
- **XAMPP** (ou tout serveur Apache + MySQL + PHP)
- **PHP 7.4+**
- **MySQL 5.7+**
- Navigateur web moderne

### Étapes d'Installation

1. **Cloner/Copier le projet**
   ```bash
   # Placer le dossier dans le répertoire htdocs de XAMPP
   C:\Xamp\htdocs\Web dy___Annonce des prestations1\
   ```

2. **Démarrer les services XAMPP**
   - Lancer Apache
   - Lancer MySQL

3. **Créer la base de données**
   - Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
   - Créer une base de données nommée : `annonce_prestations`
   - Charset : `utf8mb4_unicode_ci`

4. **Configurer la connexion** (optionnel)
   - Ouvrir `db.php`
   - Vérifier/modifier les paramètres de connexion :
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'annonce_prestations');
     ```

5. **Initialiser les tables**
   - Accéder à : `http://localhost/Web%20dy___Annonce%20des%20prestations1/setup_db_update.php`
   - Ce script va automatiquement :
     - Créer la table `services`
     - Ajouter les colonnes manquantes à la table `utilisateurs`
     - Vérifier l'intégrité de la base de données

6. **Créer la table utilisateurs** (si elle n'existe pas)
   ```sql
   CREATE TABLE utilisateurs (
       id INT AUTO_INCREMENT PRIMARY KEY,
       Nom_complet VARCHAR(255) NOT NULL,
       Email VARCHAR(255) UNIQUE NOT NULL,
       Mot_de_passe VARCHAR(255) NOT NULL,
       Telephone VARCHAR(50) NOT NULL,
       Conte VARCHAR(50) NOT NULL DEFAULT 'Client'
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
   ```

7. **Accéder à l'application**
   ```
   http://localhost/Web%20dy___Annonce%20des%20prestations1/
   ```

## 🎨 Technologies Utilisées

### Backend
- **PHP 7.4+** : Langage serveur
- **PDO (PHP Data Objects)** : Interface d'accès à la base de données
- **MySQL** : Système de gestion de base de données
- **Sessions PHP** : Gestion de l'authentification

### Frontend
- **HTML5** : Structure sémantique
- **CSS3** : Styles avec variables CSS et Flexbox/Grid
- **JavaScript (Vanilla)** : Interactivité et recherche dynamique
- **Google Fonts** : Police Tajawal pour l'arabe

### Sécurité
- **password_hash()** : Hachage bcrypt des mots de passe
- **password_verify()** : Vérification sécurisée
- **filter_input()** : Nettoyage des entrées utilisateur
- **htmlspecialchars()** : Protection contre XSS
- **Prepared Statements** : Protection contre les injections SQL

### Design
- **Direction RTL** : Interface adaptée à l'arabe
- **Responsive Design** : Compatible mobile et desktop
- **Palette de couleurs** :
  - Primaire : `#2c3e50` (Bleu foncé)
  - Secondaire : `#27ae60` (Vert)
  - Fond : `#f5f6fa` (Gris clair)

## 🔧 Fonctionnalités Techniques

### Gestion des Sessions
- Démarrage automatique avec `session_start()`
- Variables de session :
  - `$_SESSION['user_id']` : ID de l'utilisateur
  - `$_SESSION['user_name']` : Nom complet
  - `$_SESSION['user_role']` : Type de compte

### Upload de Fichiers
- Extensions autorisées : JPG, JPEG, PNG, GIF
- Génération de noms uniques avec `uniqid()`
- Stockage dans le dossier `uploads/`
- Validation côté serveur

### Recherche Dynamique (JavaScript)
- Filtrage en temps réel sans rechargement de page
- Recherche insensible à la casse
- Combinaison de plusieurs critères
- Affichage/masquage des cartes de services

### Intégration WhatsApp
- Génération automatique de liens `wa.me`
- Ajout du code pays mauritanien (+222)
- Message pré-rempli personnalisé
- Ouverture dans un nouvel onglet

## 📱 Responsive Design

L'application est entièrement responsive avec des breakpoints pour :
- **Desktop** : > 768px
- **Tablette** : 768px - 480px
- **Mobile** : < 480px

Adaptations mobiles :
- Navigation verticale
- Formulaires pleine largeur
- Grille de services adaptative
- Images optimisées

## 🔒 Sécurité

### Mesures Implémentées
1. **Authentification** :
   - Hachage bcrypt des mots de passe
   - Vérification sécurisée avec `password_verify()`
   
2. **Protection XSS** :
   - `htmlspecialchars()` sur toutes les sorties
   - `filter_input()` pour le nettoyage des entrées

3. **Protection SQL Injection** :
   - Utilisation exclusive de requêtes préparées (Prepared Statements)
   - Paramètres liés avec `execute()`

4. **Validation** :
   - Validation côté client (JavaScript)
   - Validation côté serveur (PHP)
   - Vérification des types de fichiers

5. **Contrôle d'accès** :
   - Vérification de session pour les pages protégées
   - Redirection automatique si non connecté

## 🌐 Localisation

- **Langue** : Arabe (العربية)
- **Direction** : RTL (Right-to-Left)
- **Monnaie** : Ouguiya mauritanienne (أوقية)
- **Format de date** : YYYY-MM-DD
- **Police** : Tajawal (Google Fonts)

## 🐛 Dépannage

### Problèmes Courants

**Erreur de connexion à la base de données**
- Vérifier que MySQL est démarré dans XAMPP
- Vérifier les identifiants dans `db.php`
- Vérifier que la base `annonce_prestations` existe

**Images non affichées**
- Vérifier que le dossier `uploads/` existe
- Vérifier les permissions du dossier (777)
- Vérifier le chemin dans la base de données

**Session non maintenue**
- Vérifier que `session_start()` est appelé en premier
- Vérifier la configuration PHP pour les sessions
- Vider le cache du navigateur

**Recherche ne fonctionne pas**
- Vérifier que `javascript/home.js` est chargé
- Ouvrir la console du navigateur pour les erreurs
- Vérifier les attributs `data-category` et `data-wilaya` sur les cartes

## 📝 Notes de Développement

### Conventions de Code
- **Nommage** : Variables en camelCase, constantes en UPPER_CASE
- **Commentaires** : Tous les fichiers sont commentés en français
- **Indentation** : 4 espaces
- **Encodage** : UTF-8 (pour le support de l'arabe)

### Améliorations Futures Possibles
- [ ] Système de messagerie interne
- [ ] Évaluations et avis sur les services
- [ ] Système de paiement en ligne (Bankily, Masrivi)
- [ ] Notifications par email
- [ ] Panel d'administration
- [ ] Statistiques avancées
- [ ] API REST pour application mobile
- [ ] Système de favoris
- [ ] Historique des transactions

## 👥 Contribution

Ce projet est un système complet de marketplace. Pour toute modification :
1. Tester localement avec XAMPP
2. Vérifier la compatibilité mobile
3. Maintenir les commentaires en français
4. Respecter la direction RTL pour l'arabe

## 📄 Licence

Projet développé pour le marché mauritanien des services en ligne.

## 📞 Support

Pour toute question ou problème :
- Vérifier la documentation ci-dessus
- Consulter les commentaires dans le code
- Tester avec `setup_db_update.php` pour les problèmes de BDD

---

**Développé avec ❤️ pour la Mauritanie** 🇲🇷
