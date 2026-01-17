<?php
// Démarrage de la session pour la gestion des utilisateurs connectés
session_start();

// Inclusion de la configuration de la base de données
require_once 'db.php';

// Variables pour gérer les messages de retour (succès ou erreur)
$message = '';
$messageType = '';

// Vérification si la requête est de type POST (formulaire soumis)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage de l'email
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    // Récupération du mot de passe (sans nettoyage car il sera haché/vérifié)
    $password = $_POST['password'];

    // Validation des champs vides
    if (empty($email) || empty($password)) {
        $message = "يرجى ملء جميع الحقول."; // Veuillez remplir tous les champs
        $messageType = "error";
    } else {
        try {
            // Préparation de la requête pour chercher l'utilisateur par email
            $stmt = $pdo->prepare("SELECT " . COL_ID . ", " . COL_FULLNAME . ", " . COL_PASSWORD . ", " . COL_USER_TYPE . " FROM " . TABLE_USERS . " WHERE " . COL_EMAIL . " = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Vérification si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user[COL_PASSWORD])) {
                // Connexion réussie : Initialisation des variables de session
                $_SESSION['user_id'] = $user[COL_ID];
                $_SESSION['user_name'] = $user[COL_FULLNAME];
                $_SESSION['user_role'] = $user[COL_USER_TYPE];

                // Redirection vers la page d'accueil ou le tableau de bord
                header("Location: index.php"); 
                exit;
            } else {
                // Échec de connexion : Identifiants incorrects
                $message = "البريد الإلكتروني أو كلمة المرور غير صحيحة."; // Email ou mot de passe incorrect
                $messageType = "error";
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            $message = "خطأ في النظام: " . $e->getMessage();
            $messageType = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <!-- Importation de la police Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Variables CSS pour le thème */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --bg-color: #f5f6fa;
            --text-color: #2c3e50;
        }

        /* Styles de base de la page */
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
            display: flex;
            justify-content: center; /* Centrage horizontal */
            align-items: center;     /* Centrage vertical */
            min-height: 100vh;       /* Pleine hauteur de l'écran */
        }

        /* Conteneur principal du formulaire */
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
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

        /* Styles des champs de saisie */
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Tajawal', sans-serif;
            font-size: 16px;
            box-sizing: border-box; /* Inclut le padding dans la largeur */
        }

        input:focus {
            border-color: var(--secondary-color);
            outline: none; /* Supprime le contour par défaut */
        }

        /* Style du bouton de soumission */
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
            margin-top: 10px;
        }

        button:hover {
            background-color: #219150;
        }

        /* Styles pour les messages d'erreur/succès */
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Liens de bas de page (inscription) */
        .links {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }

        .links a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>تسجيل الدخول</h2>

        <!-- Affichage du message d'erreur si existant -->
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire de login -->
        <form method="post" action="">
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">دخول</button>
        </form>

        <div class="links">
            ليس لديك حساب؟ <a href="signup.php">أنشئ حساباً جديداً</a>
        </div>
    </div>

</body>

</html>