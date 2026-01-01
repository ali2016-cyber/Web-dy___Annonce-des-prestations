<?php
session_start();

include("header_logout.html");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="headers.css">
</head>
<body>
<?php  echo $_SESSION["email"];   ?>
</body>
</html>

<?php
include("footer.html")

?>