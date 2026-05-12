<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['delete_card_id'])) {

        $id = intval($_POST['delete_card_id']);

        $conn->query("DELETE FROM payment_methods WHERE id=$id");
    }
}

header("Location: profilo.php");
exit();
?>