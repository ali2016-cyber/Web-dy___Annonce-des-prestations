<?php
// Configuration de la Base de Données
// Définition des constantes pour la connexion à la base de données
define('DB_HOST', 'localhost'); // Hôte de la base de données (généralement localhost)
define('DB_USER', 'root');      // Nom d'utilisateur de la base de données
define('DB_PASS', '');          // Mot de passe de la base de données (vide par défaut pour XAMPP)
define('DB_NAME', 'annonce_prestations'); // Nom de la base de données

// Configuration des Noms de Tables
// Définit le nom de la table des utilisateurs pour une utilisation facile dans les requêtes
define('TABLE_USERS', 'utilisateurs');

// Configuration des Noms de Colonnes
// Définit les noms des colonnes de la table 'utilisateurs' pour éviter les erreurs de frappe
define('COL_ID', 'id');             // Identifiant unique (Clé primaire)
define('COL_FULLNAME', 'Nom_complet'); // Nom complet de l'utilisateur
define('COL_EMAIL', 'Email');       // Adresse email de l'utilisateur
define('COL_PASSWORD', 'Mot_de_passe'); // Mot de passe haché
define('COL_PHONE', 'Telephone');   // Numéro de téléphone
define('COL_USER_TYPE', 'Conte');   // Type de compte (Client ou Prestataire)

// Création de la Connexion PDO
try {
    // Chaîne de connexion DSN (Data Source Name)
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    // Initialisation de l'instance PDO
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    // Configuration des options PDO pour une meilleure gestion des erreurs et des données
    // Lance une exception en cas d'erreur SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Récupère les résultats sous forme de tableau associatif par défaut
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Gestion des erreurs de connexion
    // En production, il est préférable de loguer l'erreur plutôt que de l'afficher
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}
?>