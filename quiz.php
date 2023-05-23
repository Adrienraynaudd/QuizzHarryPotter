<?php
// Charger les données du quiz depuis le fichier JSON
$jsonData = file_get_contents('question.json');
$quizData = json_decode($jsonData, true);

// Vérifier si une question est en cours
if (isset($_GET['question'])) {
    $questionIndex = $_GET['question'];
} else {
    $questionIndex = 0; // Commencer par la première question
}


// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupérer la réponse sélectionnée
    $selectedAnswer = $_POST['answer'];

    // Vérifier si la réponse est correcte
    $correctAnswer = $quizData['quiz']['questions'][$questionIndex]['answer'];
    $isCorrect = ($selectedAnswer === $correctAnswer);

    // Mettre à jour le score si la réponse est correcte
    if ($isCorrect) {
        session_start();
        if (!isset($_SESSION['score'])) {
            $_SESSION['score'] = 0;
        }
        $_SESSION['score']++;
    }else {
        session_start();
        if (!isset($_SESSION['wrongAnswers'])) {
            $_SESSION['wrongAnswers'] = array();
        }
        $wrongAnswer = array(
            'question' => $quizData['quiz']['questions'][$questionIndex]['question'],
            'userAnswer' => $selectedAnswer,
            'correctAnswer' => $correctAnswer
        );
        array_push($_SESSION['wrongAnswers'], $wrongAnswer);
    }
    if ($questionIndex >= 10) {
        // Afficher le score final
        if (!isset($_SESSION['score'])) {
            $_SESSION['score'] = 0;
        }
        $finalScore = $_SESSION['score'];
        $wrongAnswers = $_SESSION['wrongAnswers'];
        session_destroy();
    
        echo '<h2>Score final :</h2>';
        echo '<p>' . $finalScore . ' / 10</p>';

        if (!empty($wrongAnswers)) {
            echo '<h2>Questions mal répondues :</h2>';
            foreach ($wrongAnswers as $question) {
                echo '<p>Question : ' . $question['question'] . '</p>';
                echo '<p>Votre réponse : ' . $question['userAnswer'] . '</p>';
                echo '<p>Réponse correcte : ' . $question['correctAnswer'] . '</p>';
                echo '<hr>';
            }
        }
        exit();
    }

    // Rediriger vers la question suivante (ou afficher le score final)
    if ($questionIndex < count($quizData['quiz']['questions']) - 1) {
        $nextQuestion = $questionIndex + 1;
        header("Location: quiz.php?question=$nextQuestion");
        exit();
    } else {
        // Toutes les questions ont été répondues, afficher le score final
        session_start();
        $finalScore = $_SESSION['score'];
        session_destroy();

        echo '<h2>Score final :</h2>';
        echo '<p>' . $finalScore . ' / ' . count($quizData['quiz']['questions']) . '</p>';
        exit();
    }
}

// Afficher la question en cours
$currentQuestion = $quizData['quiz']['questions'][$questionIndex]['question'];
$answers = $quizData['quiz']['questions'][$questionIndex]['options'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz</title>
    <style>
        /* Votre style CSS ici */
    </style>
</head>
<body>
    <h2>Question :</h2>
    <p><?php echo $currentQuestion; ?></p>

    <form action="quiz.php?question=<?php echo $questionIndex; ?>" method="post">
        <?php foreach ($answers as $answer) : ?>
            <input type="radio" name="answer" value="<?php echo $answer; ?>" required><?php echo $answer; ?><br>
        <?php endforeach; ?>

        <br>
        <p>Temps restant : <span id="countdown">10</span> secondes</p>
        <input type="submit" name="submit" value="Soumettre">
    </form>

    <script>
        var totalSeconds = 10;
        var timerElement = document.getElementById("countdown");

        function countdown() {
            timerElement.textContent = totalSeconds;

            var countdownInterval = setInterval(function() {
                totalSeconds--;

                if (totalSeconds <= 0) {
                    <?php
                    // Rediriger vers la question suivante (ou afficher le score final)
                    if ($questionIndex < count($quizData['quiz']['questions']) - 1) {
                        $nextQuestion = $questionIndex + 1;
                        echo "window.location.href = 'quiz.php?question=$nextQuestion';";
                    } else {
                        // Toutes les questions ont été répondues, afficher le score final
                        session_start();
                        $finalScore = $_SESSION['score'];
                        session_destroy();

                        echo "alert('Score final : $finalScore / " . count($quizData['quiz']['questions']) . "');";
                        echo "window.location.href = 'quiz.php';";
                    }
                    ?>
                } else {
                    timerElement.textContent = totalSeconds;
                }
            }, 1000);
        }

        countdown();
    </script>
</body>
</html>
