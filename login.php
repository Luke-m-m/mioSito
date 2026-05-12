<?php
session_start();

include "includes/db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$_SESSION['utente'] = $email;

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['utente'] = $email;

        // QUESTO È CORRETTO SOLO SE ESISTE LA CARTELLA
        header("Location: dashboard/");
        exit();

    } else {
        echo "Password errata";
    }

} else {
    echo "Utente non trovato";
}
?>