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

/* camera */
$room = $conn->query("SELECT * FROM rooms WHERE id=$room_id")->fetch_assoc();

/* notti */
$date1 = new DateTime($check_in);
$date2 = new DateTime($check_out);
$notti = $date1->diff($date2)->days;

$total = $notti * $room['price_per_night'];

/* carte salvate */
$cards = $conn->query("SELECT * FROM payment_methods WHERE user_id=$user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pagamento</title>
   
    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f4f8f4;
        color: #2f3e2f;
    }

    .container {
        display: flex;
        min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
        width: 220px;
        background: #2f5d3a;
        color: white;
        padding: 20px;
    }

    .sidebar h2 {
        margin-bottom: 20px;
        font-size: 20px;
    }

    .sidebar a {
        display: block;
        color: white;
        text-decoration: none;
        padding: 10px;
        margin-bottom: 8px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: #3f7a4e;
    }

    /* CONTENT */
    .content {
        flex: 1;
        padding: 30px;
    }

    /* PAYMENT BOX */
    .payment-box {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }

    .payment-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #2f5d3a;
    }

    .payment-subtitle {
        font-size: 14px;
        margin-bottom: 20px;
        color: #6b7c6b;
    }

    .price-box {
        background: #eaf5ea;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        text-align: center;
        font-size: 18px;
        color: #2f5d3a;
    }

    h3 {
        margin-top: 20px;
        color: #2f5d3a;
    }

    /* INPUT */
    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 10px;
        border: 1px solid #cfe3cf;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
        background: #fbfdfb;
    }

    input[type="text"]:focus {
        border-color: #2f5d3a;
        background: white;
    }

    /* RADIO */
    label {
        font-size: 14px;
    }

    hr {
        border: none;
        border-top: 1px solid #e0e8e0;
        margin: 20px 0;
    }

    /* CHECKBOX */
    input[type="checkbox"] {
        transform: scale(1.1);
    }

    /* BUTTON */
    .payment-btn {
        width: 100%;
        margin-top: 20px;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: #2f5d3a;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }

    .payment-btn:hover {
        background: #3f7a4e;
    }
</style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>🏨 Hotel</h2>
        <a href="index.php">🏠 Dashboard</a>
        <a href="camere.php">🛏️ Camere</a>
        <a href="prenotazioni.php">📅 Prenotazioni</a>
        <a href="profilo.php">👤 Profilo</a>
        <a href="../logout.php">🚪 Logout</a>
    </div>

    <!-- CONTENUTO -->
    <div class="content">

        <div class="payment-box">

            <div class="payment-title">💳 Pagamento sicuro</div>

            <div class="payment-subtitle">
                Completa il pagamento per confermare la prenotazione
            </div>

            <div class="price-box">
                <h2>Totale: € <?php echo $total; ?></h2>
            </div>

            <form method="POST" action="salva_prenotazione.php">

                <!-- dati prenotazione -->
                <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                <input type="hidden" name="check_in" value="<?php echo $check_in; ?>">
                <input type="hidden" name="check_out" value="<?php echo $check_out; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total; ?>">

                <!-- ===== CARTE SALVATE ===== -->
                <?php if ($cards->num_rows > 0) { ?>

                    <h3>💾 Usa carta salvata</h3>

                    <?php while($card = $cards->fetch_assoc()) { ?>

                        <label style="display:block; margin-bottom:10px;">
                            <input type="radio" name="saved_card"
                                value="<?php echo $card['id']; ?>">

                            💳 <?php echo $card['card_name']; ?> —
                            **** <?php echo substr($card['card_number'], -4); ?>
                        </label>

                    <?php } ?>

                    <hr>

                <?php } ?>

                <!-- ===== NUOVA CARTA ===== -->
                <h3>💳 Oppure inserisci nuova carta</h3>

                <input type="text" name="card_name" placeholder="Nome titolare">
                <input type="text" name="card_number" placeholder="Numero carta">
                <input type="text" name="card_exp" placeholder="MM/AA">
                <input type="text" name="card_cvv" placeholder="CVV">

                <!-- salva metodo -->
                <label style="display:flex; gap:8px; margin-top:10px;">
                    <input type="checkbox" name="save_payment">
                    💾 Salva metodo di pagamento
                </label>

                <button class="payment-btn" type="submit">
                    💳 Paga ora
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>