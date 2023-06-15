<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
</head>
<?php
session_start();
if (!isset($_SESSION['scoreFin'])){
    $_SESSION['scoreFin'] = 0;
}
$_SESSION['score'] = 0;
$_SESSION['wrongAnswers'] = array();
// Charger les données du quiz depuis le fichier JSON
$jsonData = file_get_contents('question.json');
$quizData = json_decode($jsonData, true);
shuffle($quizData['quiz']['questions']);
$_SESSION['quizData'] = $quizData;

if (isset($_SESSION['username']) && $_SESSION['username'] != "") {
    echo "Bonjour " . $_SESSION['username'] . " !";
    if (isset($_SESSION['score']) && $_SESSION['score'] != "") {
        echo "<br>Score : " . $_SESSION['scoreFin'];
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