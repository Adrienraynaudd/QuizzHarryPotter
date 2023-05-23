<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Traitement du formulaire de connexion lorsque celui-ci est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Vérifier les informations de connexion dans la base de données
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Connexion réussie
        echo "Connexion réussie ! Redirection vers la page d'accueil...";
        echo "<script>setTimeout(function(){ window.location.href = 'home.html'; }, 3000);</script>";
        exit();
    } else {
        // Identifiants de connexion invalides
        echo "Identifiants de connexion invalides. Veuillez réessayer.";
        echo"<script>setTimeout(function(){ window.location.href = 'connexion.html'; }, 3000);</script>";
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
