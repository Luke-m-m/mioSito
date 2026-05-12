<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

/* ===== UTENTE ===== */
$email = $_SESSION['utente'];
$user = $conn->query("SELECT id FROM users WHERE email='$email'");
$user_id = $user->fetch_assoc()['id'];

/* ===== DATI PRENOTAZIONE ===== */
$room_id = $_POST['room_id'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$total_price = $_POST['total_price'];

/* ===== DATI CARTA (SIMULATI) ===== */
$card_name = $_POST['card_name'];
$card_number = $_POST['card_number'];
$card_exp = $_POST['card_exp'];
$card_cvv = $_POST['card_cvv'];

/* checkbox salvataggio */
$save_payment = isset($_POST['save_payment']) ? 1 : 0;

$room_id = $_POST['room_id'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];

/* controllo sovrapposizione */
$check = $conn->query("
    SELECT * FROM bookings 
    WHERE room_id = $room_id
    AND status != 'cancelled'
    AND (
        ('$check_in' < check_out)
        AND
        ('$check_out' > check_in)
    )
");

if ($check->num_rows > 0) {
    echo "<script>
        alert('Camera già prenotata in queste date!');
        window.history.back();
    </script>";
    exit();
}

/* ===== INSERISCI PRENOTAZIONE ===== */
$sql = "INSERT INTO bookings 
(user_id, room_id, check_in, check_out, total_price, status)
VALUES 
('$user_id', '$room_id', '$check_in', '$check_out', '$total_price', 'paid')";

if ($conn->query($sql)) {

    /* ===== METODO DI PAGAMENTO ===== */
$save_payment = isset($_POST['save_payment']) ? 1 : 0;

if (!empty($_POST['saved_card'])) {

    /* ===== USA CARTA SALVATA ===== */
    $card_id = $_POST['saved_card'];

    $card = $conn->query("SELECT * FROM payment_methods WHERE id=$card_id")->fetch_assoc();

    $card_name = $card['card_name'];
    $card_number = $card['card_number'];
    $card_exp = $card['card_exp'];

} else {

    /* ===== NUOVA CARTA ===== */
    $card_name = $_POST['card_name'];
    $card_number = $_POST['card_number'];
    $card_exp = $_POST['card_exp'];
    $card_cvv = $_POST['card_cvv'];

    /* salva solo se checkbox attiva */
    if ($save_payment == 1) {

        $sqlPay = "INSERT INTO payment_methods 
        (user_id, card_name, card_number, card_exp)
        VALUES 
        ('$user_id', '$card_name', '$card_number', '$card_exp')";

        $conn->query($sqlPay);
    }
}

    /* redirect */
    header("Location: prenotazioni.php");
    exit();

} else {
    echo "Errore nella prenotazione: " . $conn->error;
}
?>