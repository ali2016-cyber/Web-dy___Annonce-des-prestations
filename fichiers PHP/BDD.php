<?php
session_start();           // pour qu'on puisse utliser les variable globale $_SESSION[""]

$BD_server      = "localhost";    // nom de serveur
$BD_utilisateur = "root";         // nom de base de l'utilisateur
$BD_pass        = "";             // le mot de pass
$BD_nom         = "annonce_prestations";  // le nom de base de donne
$conn           = "";                     //  un variable qui decrit l'etat du connection

try{                                 //  try chatch pour la gestion d'eurrer, comme try except en python


$conn = mysqli_connect($BD_server,
                       $BD_utilisateur,
                       $BD_pass,
                       $BD_nom);     // هذا اياك تتصل بقاعدة البيانات



$Requete_sql = "INSERT INTO utilisateurs (Nom_complet, Email, Mot_de_passe, Conte) VALUES ('{$_SESSION["Nom_utilisateur"]}', '{$_SESSION["email"]}', '{$_SESSION["Password"]}', '{$_SESSION["utilisateur"]}')";

// Une requete sql qui va inserer l'utilisateur dans la base de donne annonce_prestations dans la table utilisateur


mysqli_query($conn, $Requete_sql);         // applique la requete




header('Location: home2.php');  // aller au page home2.php


mysqli_close($conn);            // arreter la connection avec mysql
}

catch(mysqli_sql_exception) {
    echo "on a pas pus registrer l'utilisateur";
}



?>