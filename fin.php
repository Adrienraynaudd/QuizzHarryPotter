<?php
session_start();

if (isset($_SESSION['score'])) {
    $finalScore = $_SESSION['score'];
} else {
    $finalScore = 0;
}

if (isset($_SESSION['wrongAnswers'])) {
    $wrongAnswers = $_SESSION['wrongAnswers'];
} else {
    $wrongAnswers = array();
}

echo '<h2>Score final :</h2>';
echo '<p>' . $_SESSION['score'] . ' / 11</p>';
if ($_SESSION['score'] > $_SESSION['scoreFin']){
    $_SESSION['scoreFin'] = $_SESSION['score'];
}

if (!empty($wrongAnswers)) {
    echo '<h2>Questions mal répondues :</h2>';
    foreach ($wrongAnswers as $wrongAnswer) {
        if (is_array($wrongAnswer) && isset($wrongAnswer['question']) && isset($wrongAnswer['userAnswer']) && isset($wrongAnswer['correctAnswer'])) {
            echo '<p>Question : ' . $wrongAnswer['question'] . '</p>';
            echo '<p>Votre réponse : ' . $wrongAnswer['userAnswer'] . '</p>';
            echo '<p>Réponse correcte : ' . $wrongAnswer['correctAnswer'] . '</p>';
            echo '<hr>';
        }
    }
}
?>
