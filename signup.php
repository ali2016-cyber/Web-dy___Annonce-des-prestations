<?php
// Inclusion de la connexion à la base de données
require_once 'db.php';

// Variables pour les messages à l'utilisateur
$message = '';
$messageType = '';

// Traitement du formulaire lorsque la méthode est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage et récupération des entrées
    $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS); // Échappe les caractères spéciaux pour éviter les failles XSS
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL); // Supprime les caractères illégaux de l'email
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
    $user_type = filter_input(INPUT_POST, 'user_type', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password']; // Le mot de passe sera haché, pas besoin de le sanitiser à ce stade

    $errors = []; // Tableau pour stocker les erreurs de validation

    // Validation côté serveur
    // Vérification que tous les champs sont remplis
    if (empty($fullname) || empty($email) || empty($password) || empty($phone)) {
        $errors[] = "جميع الحقول مطلوبة."; // Tous les champs sont requis
    }

    // Vérification de la validité de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "البريد الإلكتروني غير صالح."; // Email invalide
    }

    // Vérification stricte : le point doit être après le @ (exigence spécifique)
    if (strpos($email, '@') === false || strpos($email, '.') === false || strrpos($email, '.') < strpos($email, '@')) {
        $errors[] = "تنسيق البريد الإلكتروني غير صحيح."; // Format email incorrect
    }

    // Vérification du rôle utilisateur
    $valid_roles = ['Client', 'Prestataire'];
    if (!in_array($user_type, $valid_roles)) {
        $errors[] = "نوع الحساب غير صالح."; // Type de compte invalide
    }

    // Si aucune erreur, on procède à l'inscription
    if (empty($errors)) {
        // Hachage du mot de passe pour la sécurité (utilisation de l'algorithme par défaut, ex: bcrypt)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Vérification si l'email existe déjà en base
            $checkStmt = $pdo->prepare("SELECT " . COL_ID . " FROM " . TABLE_USERS . " WHERE " . COL_EMAIL . " = ?");
            $checkStmt->execute([$email]);

            if ($checkStmt->rowCount() > 0) {
                // L'email est déjà pris
                $message = "البريد الإلكتروني مسجل بالفعل.";
                $messageType = "error";
            } else {
                // Insertion du nouvel utilisateur
                $sql = "INSERT INTO " . TABLE_USERS . " (" . COL_FULLNAME . ", " . COL_EMAIL . ", " . COL_PASSWORD . ", " . COL_PHONE . ", " . COL_USER_TYPE . ") VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([$fullname, $email, $hashed_password, $phone, $user_type])) {
                    // Succès de l'inscription
                    $message = "تم إنشاء الحساب بنجاح!";
                    $messageType = "success";
                } else {
                    // Échec de l'insertion
                    $message = "حدث خطأ أثناء التسجيل.";
                    $messageType = "error";
                }
            }
        } catch (PDOException $e) {
            // Gestion erreur PDO
            $message = "خطأ في النظام: " . $e->getMessage();
            $messageType = "error";
        }
    } else {
        // Affichage des erreurs de validation
        $message = implode('<br>', $errors);
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد</title>

    <!-- Google Fonts: Tajawal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        /* Variables de thème */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --error-color: #e74c3c;
            --bg-color: #f5f6fa;
            --text-color: #2c3e50;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        /* Champs de saisie */
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Tajawal', sans-serif;
            font-size: 16px;
            box-sizing: border-box;
            /* Ensures padding doesn't affect width */
        }

        input:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .note {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-top: 5px;
        }

        button {
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

        button:hover {
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

        #email-error {
            color: var(--error-color);
            font-size: 0.85em;
            margin-top: 5px;
            display: none;
        }

        /* Responsive Images Check */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Ajustements mobile */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>تسجيل حساب جديد</h2>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'inscription -->
        <form id="signupForm" method="post" action="">
            <div class="form-group">
                <label for="fullname">الاسم الكامل</label>
                <input type="text" id="fullname" name="fullname" required placeholder="أدخل اسمك الكامل">
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" required placeholder="example@domain.com">
                <div id="email-error">البريد الإلكتروني يجب أن يحتوي على @ ونقطة (.) بعدها.</div>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required placeholder="اختر كلمة مرور قوية">
            </div>

            <div class="form-group">
                <label for="phone">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" required placeholder="رقم الهاتف">
                <div class="note">نقبل أرقام Bankily و Masrivi للدفع والخدمات.</div>
            </div>

            <div class="form-group">
                <label for="user_type">نوع الحساب</label>
                <select id="user_type" name="user_type" required
                    style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Tajawal', sans-serif; font-size: 16px; background-color: white;">
                    <option value="Client">عميل (أبحث عن خدمات)</option>
                    <option value="Prestataire">مقدم خدمة (أعرض خدماتي)</option>
                </select>
            </div>

            <button type="submit">تسجيل</button>
        </form>
    </div>

    <!-- Validation JavaScript côté client -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emailInput = document.getElementById('email');
            const form = document.getElementById('signupForm');
            const emailError = document.getElementById('email-error');

            // Logique de validation de l'email
            function validateEmail(email) {
                const atIndex = email.indexOf('@');
                const dotIndex = email.indexOf('.', atIndex); // Vérifie la présence d'un point APRÈS le @

                return atIndex > -1 && dotIndex > atIndex;
            }

            // Validation en temps réel lors de la saisie
            emailInput.addEventListener('input', function (e) {
                const email = e.target.value;
                if (email.length > 0) {
                    if (!validateEmail(email)) {
                        emailError.style.display = 'block';
                        emailInput.style.borderColor = '#e74c3c';
                    } else {
                        emailError.style.display = 'none';
                        emailInput.style.borderColor = '#27ae60';
                    }
                } else {
                    emailError.style.display = 'none';
                    emailInput.style.borderColor = '#ddd';
                }
            });

            // Validation finale lors de la soumission du formulaire
            form.addEventListener('submit', function (event) {
                const email = emailInput.value;

                if (!validateEmail(email)) {
                    // Empêche l'envoi du formulaire
                    event.preventDefault();

                    // Affiche l'erreur
                    emailError.style.display = 'block';
                    emailInput.style.borderColor = '#e74c3c';
                    emailInput.focus();

                    // Optionnel : Alerte pour emphase
                    // alert('يرجى إدخال بريد إلكتروني صحيح يحتوي على @ ونقطة بعدها.');
                }
            });
        });
    </script>

</body>

</html>