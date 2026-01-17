<?php
// Démarrage de la session pour la gestion des utilisateurs connectés
session_start();
require_once 'db.php';

// Vérification si l'utilisateur est connecté
// Si la variable de session 'user_id' n'existe pas, redirection vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
$messageType = '';

// Liste des Wilayas de Mauritanie (utilisée pour le menu déroulant)
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

// Liste des catégories de services
$categories = [
    "تصميم وبرمجة",
    "تسويق إلكتروني",
    "كتابة وترجمة",
    "تدريب عن بعد",
    "استشارات",
    "خدمات منزلية",
    "أخرى"
];

// Traitement du formulaire lorsque la méthode est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage et récupération des données du formulaire
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
    $wilaya = filter_input(INPUT_POST, 'wilaya', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validation des champs obligatoires
    if (empty($title) || empty($description) || empty($price) || empty($category) || empty($wilaya)) {
        $message = "يرجى ملء جميع الحقول المطلوبة."; // Veuillez remplir tous les champs
        $messageType = "error";
    } else {
        // Gestion de l'upload de l'image
        $imagePath = '';
        // Vérification si un fichier a été uploadé sans erreur
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif']; // Extensions autorisées
            $filename = $_FILES['image']['name'];
            $filetype = $_FILES['image']['type'];
            $filesize = $_FILES['image']['size'];

            // Récupération de l'extension du fichier
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            // Vérification de l'extension
            if (in_array($ext, $allowed)) {
                // Création du dossier 'uploads' s'il n'existe pas
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                // Génération d'un nom de fichier unique pour éviter les écrasements
                $newFilename = uniqid() . "." . $ext;
                $uploadDest = 'uploads/' . $newFilename;

                // Déplacement du fichier uploadé vers le dossier de destination
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDest)) {
                    $imagePath = $uploadDest;
                } else {
                    $message = "حدث خطأ أثناء رفع الصورة."; // Erreur lors de l'upload
                    $messageType = "error";
                }
            } else {
                $message = "صيغة الملف غير مدعومة. يرجى رفع صورة (JPG, PNG, GIF)."; // Format non supporté
                $messageType = "error";
            }
        }

        // Si aucune erreur (notamment d'upload), on procède à l'insertion en base
        if (empty($message)) { 
            try {
                // Requête SQL d'insertion
                $sql = "INSERT INTO services (user_id, title, description, price, category, wilaya, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                // Exécution de la requête avec les valeurs
                if ($stmt->execute([$_SESSION['user_id'], $title, $description, $price, $category, $wilaya, $imagePath])) {
                    $message = "تم نشر الخدمة بنجاح!"; // Service publié avec succès
                    $messageType = "success";
                } else {
                    $message = "حدث خطأ في قاعدة البيانات."; // Erreur base de données
                    $messageType = "error";
                }
            } catch (PDOException $e) {
                // Gestion des exceptions PDO
                $message = "خطأ: " . $e->getMessage();
                $messageType = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة خدمة جديدة</title>
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

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            color: var(--primary-color);
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        /* Champs de saisie, textarea et select */
        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Tajawal', sans-serif;
            font-size: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical; /* Redimensionnement vertical uniquement */
            min-height: 100px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--secondary-color);
            outline: none;
        }

        /* Bouton de soumission */
        button.btn-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-family: 'Tajawal', sans-serif;
            cursor: pointer;
            transition: background 0.3s;
        }

        button.btn-submit:hover {
            background-color: #219150;
        }

        /* Styles des messages */
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .header-nav {
            text-align: left;
            margin-bottom: 20px;
        }

        .header-nav a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Lien de retour -->
        <div class="header-nav"><a href="index.php">← العودة للرئيسية</a></div>
        <h2>إضافة خدمة جديدة</h2>

        <!-- Affichage des messages de statut -->
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout de service -->
        <!-- 'enctype="multipart/form-data"' est nécessaire pour l'upload de fichiers -->
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>عنوان الخدمة</label>
                <input type="text" name="title" required placeholder="مثال: تصميم شعار احترافي">
            </div>

            <div class="form-group">
                <label>التصنيف</label>
                <select name="category" required>
                    <option value="">اختر التصنيف...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>">
                            <?php echo $cat; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>الولاية</label>
                <select name="wilaya" required>
                    <option value="">اختر الولاية...</option>
                    <?php foreach ($wilayas as $p_wilaya): ?>
                        <option value="<?php echo $p_wilaya; ?>">
                            <?php echo $p_wilaya; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>وصف الخدمة</label>
                <textarea name="description" required placeholder="اكتب تفاصيل خدمتك بوضوح..."></textarea>
            </div>

            <div class="form-group">
                <label>السعر (أوقية جديدة)</label>
                <input type="number" name="price" required min="100" step="10">
            </div>

            <div class="form-group">
                <label>صورة الخدمة (اختياري)</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">نشر الخدمة</button>
        </form>
    </div>

</body>

</html>