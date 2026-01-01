<?php
include("header.html");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="css/bootstrap.css">
     
</head>
<body>
    <!-- Some content here -->

    <form action="login_logout.php" method="post">
    <!-- Les information de l'utilisateur -->

    <!-- Le Nom -->
        <h3>Nom</h3>
        <input type="text" name="Nom_utilisateur" class="user_info" required>

    <!-- L'Email -->
        <h3 class="label_info_user">Email</h3>
        <input type="email" name="email" required>

    <!-- Le Mot de Passe -->
        <h3 class="label_info_user">Mot de Passe</h3>
        <input type="password" name="Password" required><br>

     <!-- enregistrer les informations ci-dessus -->
       Prestataire:<input type="radio" name="utilisateur" value="Prestataire"><br>
       Client:     <input type="radio" name="utilisateur" value="Client">
       <input type='submit' name='Registrer' value='Registrer'>

    </form>

    <!-- And some content here -->

</body>
</html>

<?php
include("footer.html");

?>