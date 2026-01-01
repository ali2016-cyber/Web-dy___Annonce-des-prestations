<?php

if(isset($_SESSION["Nom_utilisateur"])) {
include("header_logout.html");

}
else{
    include("header_login.html");
}
 echo $_SESSION["Nom_utilisateur"];
 
?>