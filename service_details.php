<?php
// Démarrage de la session
session_start();
require_once 'db.php';

// Vérification si l'identifiant du service est passé en paramètre URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirection vers l'accueil si aucun ID n'est fourni
    header("Location: index.php");
    exit;
}

$serviceId = $_GET['id'];

try {
    // Récupération des détails du service et des informations du prestataire via une jointure SQL
    $sql = "SELECT s.*, u." . COL_FULLNAME . " as provider_name, u." . COL_PHONE . " as provider_phone 
            FROM services s
            JOIN " . TABLE_USERS . " u ON s.user_id = u." . COL_ID . "
            WHERE s.id = ?";

    // Préparation et exécution
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch();

    // Si le service n'existe pas en base
    if (!$service) {
        die("الخدمة غير موجودة."); // Le service n'existe pas
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Nettoyage du numéro de téléphone pour le lien WhatsApp
// Supprime tout ce qui n'est pas un chiffre
$phone = preg_replace('/[^0-9]/', '', $service['provider_phone']);

// Ajout du code pays Mauritanie (222) si le numéro fait 8 chiffres
if (strlen($phone) == 8) {
    $phone = '222' . $phone;
}

// Génération du lien WhatsApp pré-rempli avec un message d'accueil
$whatsappLink = "https://wa.me/" . $phone . "?text=" . urlencode("مرحباً، أنا مهتم بخدمتك: " . $service['title'] . " المعروضة على منصة عدل همك.");

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo htmlspecialchars($service['title']); ?> - منصة عدل همك
    </title>
    <!-- Importation de la police Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --bg-color: #f5f6fa;
            --text-color: #2c3e50;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
        }

        /* Conteneur principal */
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Barre de navigation retour */
        .nav-back {
            padding: 20px;
            background: #eee;
        }

        .nav-back a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
        }

        /* Image du service (grande taille) */
        .service-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            background-color: #eee;
        }

        .content {
            padding: 30px;
        }

        /* Badge pour la catégorie */
        .category-badge {
            display: inline-block;
            background-color: #e8f5e9;
            color: var(--secondary-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        h1 {
            margin: 0 0 20px 0;
            color: var(--primary-color);
        }

        /* Métadonnées (Prestataire, Lieu, Date) */
        .meta-info {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            color: #7f8c8d;
            font-size: 0.95em;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        /* Prix */
        .price-tag {
            font-size: 1.5em;
            color: var(--secondary-color);
            font-weight: bold;
            margin-bottom: 20px;
        }

        .description {
            line-height: 1.8;
            margin-bottom: 40px;
            font-size: 1.1em;
        }

        /* Zone d'action (Bouton WhatsApp) */
        .action-area {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .btn-whatsapp {
            display: inline-block;
            background-color: #25D366;
            /* Couleur officielle WhatsApp */
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.2em;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(37, 211, 102, 0.3);
            transition: transform 0.2s;
        }

        .btn-whatsapp:hover {
            transform: scale(1.05);
            background-color: #20bd5a;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .service-image {
                height: 250px;
            }

            .meta-info {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Lien de retour -->
        <div class="nav-back">
            <a href="index.php">← العودة للقائمة</a>
        </div>

        <?php
        // Affichage de l'image (placeholder si vide)
        $img = !empty($service['image_path']) ? $service['image_path'] : 'https://via.placeholder.com/800x400?text=Service+Image';
        ?>
        <img src="<?php echo htmlspecialchars($img); ?>" class="service-image"
            alt="<?php echo htmlspecialchars($service['title']); ?>">

        <div class="content">
            <span class="category-badge">
                <?php echo htmlspecialchars($service['category']); ?>
            </span>

            <h1>
                <?php echo htmlspecialchars($service['title']); ?>
            </h1>

            <div class="meta-info">
                <span>👤 المعلم:
                    <?php echo htmlspecialchars($service['provider_name']); ?>
                </span>
                <span>📍 الموقع:
                    <?php echo htmlspecialchars($service['wilaya']); ?>
                </span>
                <span>📅 نُشر في:
                    <?php echo date('Y-m-d', strtotime($service['created_at'])); ?>
                </span>
            </div>

            <div class="price-tag">
                <?php echo number_format($service['price']); ?> أوقية جديدة
            </div>

            <div class="description">
                <h3>تفاصيل الخدمة:</h3>
                <p>
                    <!-- nl2br convertit les sauts de ligne en balises <br> pour l'affichage HTML -->
                    <?php echo nl2br(htmlspecialchars($service['description'])); ?>
                </p>
            </div>

            <!-- Bouton d'action pour contacter via WhatsApp -->
            <div class="action-area">
                <p>هل تعجبك هذه الخدمة؟ تواصل مع مقدم الخدمة مباشرة.</p>
                <a href="<?php echo $whatsappLink; ?>" target="_blank" class="btn-whatsapp">
                    تواصل عبر واتساب 💬
                </a>
            </div>
        </div>
    </div>

</body>

</html>