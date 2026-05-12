<?php
include "includes/db.php";

$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

if ($password !== $confirm) {
    echo "Password non uguali";
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

/* inserimento nel database */
$sql = "INSERT INTO users (email, password)
        VALUES ('$email', '$hash')";

if ($conn->query($sql)) {
    echo "Registrazione completata! <a href='index.html'>Login</a>";
} else {
    echo "Errore: " . $conn->error;
}
?>
