<?php
// Démarrage de la session pour gérer les données utilisateur (connexion, etc.)
session_start();

// Inclusion du fichier de configuration de la base de données
require_once 'db.php';


try {
    // Requête SQL pour récupérer les services et les noms des prestataires
    // Utilisation d'une jointure (JOIN) pour lier la table des services à la table des utilisateurs
    $sql = "SELECT services.*, " . TABLE_USERS . "." . COL_FULLNAME . " as provider_name 
            FROM services 
            JOIN " . TABLE_USERS . " ON services.user_id = " . TABLE_USERS . "." . COL_ID . " 
            ORDER BY created_at DESC"; // Tri par date de création décroissante (les plus récents en premier)

    // Préparation et exécution de la requête
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Récupération de tous les résultats dans un tableau associatif
    $services = $stmt->fetchAll();

} catch (PDOException $e) {
    // Gestion des erreurs lors de la récupération des services
    die("Erreur lors de la récupération des services : " . $e->getMessage());
}



// Liste des Wilayas de Mauritanie pour le filtre de recherche
$wilayas = [
    "Adrar",
    "Assaba",
    "Brakna",
    "Dakhlet Nouadhibou",
    "Gorgol",
    "Guidimaka",
    "Hodh Ech Chargui",
    "Hodh El Gharbi",
    "Inchiri",
    "Nouakchott-Nord",
    "Nouakchott-Ouest",
    "Nouakchott-Sud",
    "Tagant",
    "Tiris Zemmour",
    "Trarza"
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة عدل همك - الرئيسية</title>
    <!-- Importation de la police Tajawal depuis Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Définition des variables CSS pour les couleurs et le style global */
        :root {
            --primary-color: #2c3e50;
            /* Couleur primaire (bleu foncé) */
            --secondary-color: #27ae60;
            /* Couleur secondaire (vert) */
            --bg-color: #f5f6fa;
            /* Couleur de fond */
            --text-color: #2c3e50;
            /* Couleur du texte */
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            /* Ombre des cartes */

        }

        /* Styles généraux pour le corps de la page */
        body {
            font-family: 'Tajawal', sans-serif;
            /* Utilisation de la police Tajawal */
            background-color: var(--bg-color);
            margin: 0;
            color: var(--text-color);
        }

        /* Styles de la barre de navigation */
        .navbar {
            background-color: #fff;
            padding: 15px 5%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            /* Légère ombre pour la séparation */
            display: flex;
            justify-content: space-between;
            /* Espacement entre le logo et les liens */
            align-items: center;
        }

        .logo {
            font-weight: bold;
            font-size: 24px;
            color: var(--secondary-color);
            text-decoration: none;
        }

        /* Styles des liens de navigation */
        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
        }

        /* Style pour les boutons primaires (ex: Inscription) */
        .btn-primary {
            background-color: var(--secondary-color);
            color: #fff;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #219150;
            /* Couleur au survol */
        }


        /* Section Héros (Bannière principale) */
        .hero {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            /* Dégradé de fond */
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        /* Formulaire de recherche */
        .search-form {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            /* Permet le passage à la ligne sur petits écrans */
            justify-content: center;
        }

        .search-input,
        .search-select {
            padding: 12px;
            border-radius: 5px;
            border: none;
            font-family: 'Tajawal', sans-serif;
        }

        .search-input {
            flex: 2;
            /* Prend plus d'espace */
            min-width: 200px;
        }

        .search-select {
            flex: 1;
            min-width: 150px;
        }

        .search-btn {
            padding: 12px 30px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Tajawal', sans-serif;
        }


        /* Conteneur principal */
        .container {
            padding: 40px 5%;
            max-width: 1200px;
            margin: 0 auto;
            /* Centrage horizontal */
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Grille des services */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            /* Grille responsive */
            gap: 25px;
        }

        /* Carte de service individuelle */
        .service-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            /* Empêche le contenu de déborder (pour les images) */
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-5px);
            /* Effet de soulèvement au survol */
        }

        .card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            /* L'image couvre la zone sans déformation */
            background-color: #eee;
        }

        .card-body {
            padding: 15px;
            flex-grow: 1;
            /* Remplit l'espace disponible */
            display: flex;
            flex-direction: column;
        }

        .card-category {
            font-size: 0.8em;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .card-title {
            font-size: 1.1em;
            font-weight: bold;
            margin: 0 0 10px 0;
        }

        .card-provider {
            font-size: 0.9em;
            color: #34495e;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: auto;
            /* Pousse vers le bas */
        }

        .card-footer {
            padding: 15px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-weight: bold;
            color: var(--secondary-color);
        }

        .location {
            font-size: 0.85em;
            color: #95a5a6;
        }


        /* Styles Responsive pour mobile */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .nav-links {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .nav-links a {
                margin: 0;
            }

            .search-form {
                flex-direction: column;
            }

            .search-input,
            .search-select,
            .search-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Barre de navigation -->
    <nav class="navbar">
        <a href="index.php" class="logo">منصة عدل همك</a>
        <div class="nav-links">
            <a href="index.php">الرئيسية</a>
            <!-- Affichage conditionnel selon si l'utilisateur est connecté -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Si connecté : Afficher liens dashboard et déconnexion -->
                <a href="dashboard.php" style="color: var(--secondary-color);">+ أضف خدمة</a>
                <a href="dashboard.php">لوحة التحكم</a>
                <a href="dashboard.php">حسابي (
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>)
                </a>
                <a href="logout.php">خروج</a>
            <?php else: ?>
                <!-- Si non connecté : Afficher connexion et inscription -->
                <a href="login.php">دخول</a>
                <a href="signup.php" class="btn-primary">حساب جديد</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- En-tête Hero avec recherche -->
    <header class="hero">
        <h1>ابحث عن الخدمات التي تحتاجها في موريتانيا</h1>
        <p>سوق خدمات متكامل يجمع بين المحترفين والزبناء</p>

        <!-- Formulaire de recherche (le filtrage est géré par JS) -->
        <form class="search-form" id="search-form" onsubmit="event.preventDefault();">
            <input type="text" name="search" class="search-input" id="search-input"
                placeholder="ماذا تبحث عنه؟ (مثال: سباك، مصمم، مترجم...)">

            <!-- Liste déroulante des Wilayas -->
            <select name="wilaya" class="search-select" id="wilaya-filter">
                <option value="">كل الولايات</option>
                <?php foreach ($wilayas as $p_wilaya): ?>
                    <option value="<?php echo $p_wilaya; ?>">
                        <?php echo $p_wilaya; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Liste déroulante des catégories -->
            <select name="category" class="search-select" id="category-filter">
                <option value="">كل التصنيفات</option>
                <option value="تصميم وبرمجة">تصميم وبرمجة</option>
                <option value="تسويق إلكتروني">تسويق إلكتروني</option>
                <option value="خدمات منزلية">خدمات منزلية</option>
            </select>

            <button type="submit" class="search-btn">بحث</button>
        </form>
    </header>

    <!-- Conteneur des services -->
    <div class="container">
        <h2 class="section-title">أحدث الخدمات المضافة</h2>

        <div class="services-grid">
            <!-- Vérification s'il y a des services à afficher -->
            <?php if (count($services) > 0): ?>
                <!-- Boucle sur chaque service -->
                <?php foreach ($services as $service): ?>
                    <div class="service-card" data-category="<?php echo htmlspecialchars($service['category']); ?>"
                        data-wilaya="<?php echo htmlspecialchars($service['wilaya']); ?>">
                        <!-- Lien vers les détails du service -->
                        <a href="service_details.php?id=<?php echo $service['id']; ?>"
                            style="text-decoration: none; color: inherit; display: contents;">
                            <?php
                            // Image par défaut si aucune image n'est fournie
                            $img = !empty($service['image_path']) ? $service['image_path'] : 'https://via.placeholder.com/300x200?text=Service';
                            ?>
                            <img src="<?php echo htmlspecialchars($img); ?>"
                                alt="<?php echo htmlspecialchars($service['title']); ?>" class="card-img">

                            <div class="card-body">
                                <div class="card-category">
                                    <?php echo htmlspecialchars($service['category']); ?>
                                </div>
                                <h3 class="card-title">
                                    <?php echo htmlspecialchars($service['title']); ?>
                                </h3>
                                <div class="card-provider">
                                    👤
                                    <?php echo htmlspecialchars($service['provider_name']); ?>
                                </div>
                            </div>
                        </a>

                        <div class="card-footer">
                            <span class="price">
                                <?php echo number_format($service['price']); ?> أوقية
                            </span>
                            <span class="location">
                                <?php echo htmlspecialchars($service['wilaya']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Message si aucun service n'est trouvé -->
                <p style="grid-column: 1/-1; text-align: center;">لا توجد خدمات مطابقة للبحث حالياً.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Script JavaScript pour la logique front-end (recherche dynamique, etc.) -->
    <script src="javascript/home.js"></script>
</body>

</html>