<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <!-- importer le fichier de style -->
    <link rel="stylesheet" href="style.css" media="screen" type="text/css" />
</head>
<?php
session_start();
if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
    echo "Bonjour " . $_SESSION['username'] . " !";
    if (isset($_SESSION['score']) && $_SESSION['score'] != "") {
        echo "<br>Score : " . $_SESSION['score'];
    }else{
        echo "<br>Score : 0";
    }
    echo "<a href='logout.php'>Déconnexion</a>";
    include "homeCo.html"; // If the user is logged in, display the homeCo.php page
} else {
    include "homeNco.html"; // If the user is not logged in, display the homeNco.php page
}
?>

</html>