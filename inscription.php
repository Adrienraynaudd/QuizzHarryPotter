<?php
session_start();
// Connexion à la base de données
$servername = "[2a01:e0a:46a:2780:545c:98ff:fe16:72cc]";
$port = "3310";
$username = "root";
$password = "";
$dbname = "quiz";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Traitement du formulaire d'inscription lorsque celui-ci est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Vérifier si l'utilisateur existe déjà dans la base de données
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Ce nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        // Insérer les données de l'utilisateur dans la base de données
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $username;
            echo "Inscription réussie ! Redirection vers la page d'accueil...";
            echo "<script>setTimeout(function(){ window.location.href = 'home.php'; }, 3000);</script>";
            exit();
        } else {
            echo "Erreur lors de l'inscription : " . $conn->error;
            echo "<script>setTimeout(function(){ window.location.href = 'inscription.html'; }, 3000);</script>";
        }
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
