<?php
// Démarrage de la session pour pouvoir la manipuler
session_start();

// Suppression de toutes les variables de session
session_unset();

// Destruction de la session (déconnexion effective)
session_destroy();

// Redirection vers la page d'accueil
header("Location: index.php");
exit;
?>