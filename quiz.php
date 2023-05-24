<?php
session_start();
$quizData = $_SESSION['quizData'];
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
    $_SESSION['correctAnswer'] = $correctAnswer;
    $isCorrect = ($selectedAnswer === $correctAnswer);

    // Mettre à jour le score si la réponse est correcte
    if ($isCorrect) {
        $_SESSION['score'] = isset($_SESSION['score']) ? $_SESSION['score'] + 1 : 1;
    }else {
        if (isset($_SESSION['wrongAnswers']) && is_array($_SESSION['wrongAnswers'])) {
            $wrongAnswers = $_SESSION['wrongAnswers'];
        } else {
            $wrongAnswers = array();
        }
        $wrongAnswer = array(
            'question' => $quizData['quiz']['questions'][$questionIndex]['question'],
            'userAnswer' => $selectedAnswer,
            'correctAnswer' => $correctAnswer
        );
        $_SESSION['wrongAnswers'][] = $wrongAnswer;
    }
    // Rediriger vers la question suivante (ou afficher le score final)
    if ($questionIndex < 10) {
        $nextQuestion = $questionIndex + 1;
        header("Location: quiz.php?question=$nextQuestion");
        exit();
    } else {
        echo "Fin du quiz ! Redirection vers la page de score...";
        echo "<script>setTimeout(function(){ window.location.href = 'http://localhost/QuizzHarryPotter/fin.php'; }, 3000);</script>";
        exit();
    }
}
// Afficher la question en cours
$currentQuestion = $quizData['quiz']['questions'][$questionIndex]['question'];
$options = $quizData['quiz']['questions'][$questionIndex]['options'];
$correctAnswer = $quizData['quiz']['questions'][$questionIndex]['answer'];
shuffle($options);

$correctAnswerIndex = array_search($correctAnswer, $options);
if ($correctAnswerIndex !== false) {
    // Déplacer la réponse correcte à la nouvelle position
    $correctAnswer = $options[$correctAnswerIndex];
    unset($options[$correctAnswerIndex]);
    array_unshift($options, $correctAnswer);
}

$answers = $options;
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
                clearInterval(countdownInterval); // Arrêter le minuteur
                goToNextQuestion();
            } else {
                timerElement.textContent = totalSeconds;
            }
        }, 1000);
    }

    function goToNextQuestion() {
        var currentQuestionIndex = <?php echo $questionIndex; ?>;
        var totalQuestions = <?php echo 11; ?>;

        if (currentQuestionIndex < totalQuestions - 1) {
            var nextQuestionIndex = currentQuestionIndex + 1;
            window.location.href = 'quiz.php?question=' + nextQuestionIndex;
        } else {
            window.location.href = 'http://localhost/QuizzHarryPotter/fin.php';
        }
    }

    countdown();
</script>


</body>
</html>
