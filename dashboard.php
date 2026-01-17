<?php
// Démarrage de la session pour accéder aux variables de session
session_start();
require_once 'db.php';

// Vérification si l'utilisateur est connecté
// Si la variable de session 'user_id' n'existe pas, redirection vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'] ?? 'Client'; // Par défaut 'Client' si le rôle n'est pas défini

// Traduction des rôles pour l'affichage (Anglais -> Arabe)
$role_translations = [
    'Client' => 'عميل',        // Client
    'Prestataire' => 'مقدم خدمة' // Prestataire de service
];
$user_role_display = $role_translations[$user_role] ?? $user_role;

// Gestion de l'action de suppression d'un service
// Vérifie si un paramètre 'delete' est présent dans l'URL (GET)
if (isset($_GET['delete'])) {
    $service_id = $_GET['delete'];
    try {
        // Préparation de la requête de suppression
        // IMPORTANT : On vérifie aussi 'user_id' pour s'assurer que l'utilisateur supprime bien SON propre service
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ? AND user_id = ?");
        if ($stmt->execute([$service_id, $user_id])) {
            $message = "تم حذف الخدمة بنجاح."; // Service supprimé avec succès
        } else {
            $message = "حدث خطأ أثناء الحذف."; // Erreur lors de la suppression
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Récupération des services de l'utilisateur connecté
try {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $my_services = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - حسابي</title>
    <!-- Importation de la police Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --bg-color: #f5f6fa;
        }

        /* Styles généraux */
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* En-tête du tableau de bord */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .welcome h2 {
            margin: 0;
        }

        .welcome p {
            margin: 5px 0 0;
            color: #7f8c8d;
        }

        /* Styles des boutons */
        .btn {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }

        .btn-add {
            background-color: var(--secondary-color);
        }

        .btn-home {
            background-color: var(--primary-color);
        }

        /* Contenu du tableau de bord */
        .dashboard-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Tableaux pour lister les services */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: right;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            color: #2c3e50;
        }

        /* Actions (Voir, Supprimer) */
        .actions a {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 0.9em;
            margin-left: 5px;
        }

        .btn-view {
            background-color: #3498db;
            color: white;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        /* État vide (aucun service) */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
        }

        .alert {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- En-tête avec message de bienvenue et boutons de contrôle -->
        <div class="header">
            <div class="welcome">
                <h2>مرحباً،
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </h2>
                <p>نوع الحساب:
                    <?php echo htmlspecialchars($user_role_display); ?>
                </p>
            </div>
            <div class="controls">
                <a href="index.php" class="btn btn-home">الرئيسية</a>
                <a href="logout.php" class="btn" style="background-color: #e74c3c;">خروج</a>
            </div>
        </div>

        <!-- Affichage des messages de notification -->
        <?php if ($message): ?>
            <div class="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="dashboard-content">
            <h3>خدماتي المعروضة</h3>

            <!-- Vérification s'il y a des services -->
            <?php if ($user_role == 'Client' && empty($my_services)): ?>
                <!-- Cas Client sans services -->
                <div class="empty-state">
                    <p>أنت مسجل كـ "زبون". يمكنك تصفح الخدمات في الصفحة الرئيسية.</p>
                    <p>إذا كنت تريد تقديم خدمات، يمكنك إضافة خدمة جديدة.</p>
                    <a href="add_service.php" class="btn btn-add">أضف خدمة الآن</a>
                </div>
            <?php elseif (empty($my_services)): ?>
                <!-- Cas Prestataire sans services -->
                <div class="empty-state">
                    <p>لم تقم بإضافة أي خدمات بعد.</p>
                    <a href="add_service.php" class="btn btn-add">أضف خدمة جديدة</a>
                </div>
            <?php else: ?>
                <!-- Liste des services existants -->
                <div style="margin-bottom: 20px;">
                    <a href="add_service.php" class="btn btn-add">+ إضافة خدمة جديدة</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>الخدمة</th>
                            <th>السعر</th>
                            <th>التاريخ</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($my_services as $service): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($service['title']); ?>
                                </td>
                                <td>
                                    <?php echo number_format($service['price']); ?> أوقية
                                </td>
                                <td>
                                    <?php echo date('Y/m/d', strtotime($service['created_at'])); ?>
                                </td>
                                <td class="actions">
                                    <a href="service_details.php?id=<?php echo $service['id']; ?>" class="btn-view"
                                        target="_blank">عرض</a>
                                    <!-- Lien de suppression avec confirmation JS -->
                                    <a href="?delete=<?php echo $service['id']; ?>" class="btn-delete"
                                        onclick="return confirm('هل أنت متأكد من حذف هذه الخدمة؟');">حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>