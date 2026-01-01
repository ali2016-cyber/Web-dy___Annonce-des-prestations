<?php
session_start();

?>
<?php




if(isset($_POST["Nom_utilisateur"])) {     // (كنت انكد اندير Registrer فبلدNom_utilis...) هذا   Nom_utilisateurيشتغل الين المستخدم دخل فعلا

    
//يشتغل اللافحالة la variable $_SESSION 
// session_start(); m3ah vnfs lmlf

     $_SESSION["Nom_utilisateur"] = $_POST["Nom_utilisateur"];  
     $_SESSION["email"]= $_POST["email"];
     $_SESSION["Password"] = $_POST["Password"];
     $_SESSION["utilisateur"] = $_POST["utilisateur"];

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // ha4e nt2kdou bih 3n lmoustakhdim madar code khabith ve l'input

filter_input(INPUT_POST, $_SESSION["Nom_utilisateur"], FILTER_SANITIZE_SPECIAL_CHARS);
filter_input(INPUT_POST, $_SESSION["email"], FILTER_SANITIZE_SPECIAL_CHARS);
filter_input(INPUT_POST, $_SESSION["Password"], FILTER_SANITIZE_SPECIAL_CHARS);


$mot_de_pase =  $_SESSION["Password"];   // ceci est pour pouver la comparer apres lors de login pour verifier est ce que c'est l'utilisateur 

$_SESSION["Password"] = password_hash($_SESSION["Password"], PASSWORD_DEFAULT);  // HA4E MN EJL TECHFIRE MOT DE PASSE

}
   
     header("Location: BDD.php");    //هذا ايكيس بينا ملف Base de donne
     

}
if(isset($_POST["logout"])) {        //هذا ينفذ فحال ضغطنا log out
// $_POST["logout"] = Null;


 session_destroy();              //        modifie ceci apres ,apres bien   comprendre ce que session faire


 header("Location: home1.php");    // هذا ايكيس بينا ذا الملف لقدامك

}







?>