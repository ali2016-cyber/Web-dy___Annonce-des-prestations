<?php
require_once 'db.php';

echo "<h2>Outil de mise à jour de la base de données</h2>";

try {
    // 1. Vérification de l'existence de la colonne 'Telephone'
    // Récupération des informations sur les colonnes de la table utilisateurs
    $checkCol = $pdo->prepare("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table AND COLUMN_NAME = :col");
    $checkCol->execute([
        ':db' => DB_NAME,
        ':table' => TABLE_USERS,
        ':col' => COL_PHONE
    ]);

    // Si la colonne n'existe pas, on l'ajoute
    if ($checkCol->rowCount() == 0) {
        echo "Ajout de la colonne '" . COL_PHONE . "' à la table '" . TABLE_USERS . "'...<br>";

        // Requête ALTER TABLE pour ajouter la colonne
        $sql = "ALTER TABLE " . TABLE_USERS . " ADD COLUMN " . COL_PHONE . " VARCHAR(50) NOT NULL AFTER " . COL_PASSWORD;
        $pdo->exec($sql);

        echo "<span style='color:green'>Succès : Colonne '" . COL_PHONE . "' ajoutée avec succès.</span><br>";
    } else {
        echo "<span style='color:blue'>Info : La colonne '" . COL_PHONE . "' existe déjà.</span><br>";
    }

    // 2. Vérification de l'existence de la colonne 'Conte' (Type de compte)
    $checkCol->execute([
        ':db' => DB_NAME,
        ':table' => TABLE_USERS,
        ':col' => COL_USER_TYPE
    ]);

    if ($checkCol->rowCount() == 0) {
        echo "Ajout de la colonne '" . COL_USER_TYPE . "' à la table '" . TABLE_USERS . "'...<br>";
        // Ajout de la colonne avec 'Client' comme valeur par défaut
        $sql = "ALTER TABLE " . TABLE_USERS . " ADD COLUMN " . COL_USER_TYPE . " VARCHAR(50) NOT NULL DEFAULT 'Client'";
        $pdo->exec($sql);
        echo "<span style='color:green'>Succès : Colonne '" . COL_USER_TYPE . "' ajoutée avec succès.</span><br>";
    } else {
        echo "<span style='color:blue'>Info : La colonne '" . COL_USER_TYPE . "' existe déjà.</span><br>";
    }

    // 3. Création de la table 'services' si elle n'existe pas
    $checkTable = $pdo->query("SHOW TABLES LIKE 'services'");
    if ($checkTable->rowCount() == 0) {
        echo "Création de la table 'services'...<br>";

        // Définition de la structure de la table services
        $sql = "CREATE TABLE services (
            id INT AUTO_INCREMENT PRIMARY KEY,      -- Identifiant unique du service
            user_id INT NOT NULL,                   -- ID de l'utilisateur qui propose le service
            title VARCHAR(255) NOT NULL,            -- Titre du service
            description TEXT NOT NULL,              -- Description détaillée
            price DECIMAL(10, 2) NOT NULL,          -- Prix
            category VARCHAR(100) NOT NULL,         -- Catégorie du service
            wilaya VARCHAR(100) NOT NULL,           -- Ville/Wilaya
            image_path VARCHAR(255),                -- Chemin vers l'image du service
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date de création
            FOREIGN KEY (user_id) REFERENCES " . TABLE_USERS . "(" . COL_ID . ") ON DELETE CASCADE -- Clé étrangère liée à la table utilisateurs
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $pdo->exec($sql);
        echo "<span style='color:green'>Succès : Table 'services' créée avec succès.</span><br>";
    } else {
        echo "<span style='color:blue'>Info : La table 'services' existe déjà.</span><br>";
    }

    echo "<h3>La base de données est prête à l'emploi !</h3>";
    echo "<a href='signup.php'>Aller à la page d'inscription</a> | <a href='login.php'>Aller à la page de connexion</a>";

} catch (PDOException $e) {
    echo "<span style='color:red'>Erreur : " . $e->getMessage() . "</span>";
}
?>