<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

$id = $_GET['id'];

/* prendo prenotazione */
$sql = "SELECT check_in FROM bookings WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Prenotazione non trovata");
}

$booking = $result->fetch_assoc();

/* controllo 7 giorni */
$check_in = new DateTime($booking['check_in']);
$limit = clone $check_in;
$limit->modify('+7 days');

$now = new DateTime();

if ($now > $limit) {
    die("Non puoi eliminare la prenotazione dopo 7 giorni dall'inizio del soggiorno.");
}

/* elimina */
$conn->query("DELETE FROM bookings WHERE id=$id");

header("Location: prenotazioni.php");
exit();
?>