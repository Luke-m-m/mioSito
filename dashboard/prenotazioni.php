<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

/* utente loggato */
$email = $_SESSION['utente'];
$user = $conn->query("SELECT id FROM users WHERE email='$email'");
$user_id = $user->fetch_assoc()['id'];

/* prenotazioni */
$sql = "SELECT b.*, r.name 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id
        WHERE b.user_id = $user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Le tue prenotazioni</title>
    <link rel="stylesheet" href="../css/dashboard.css">
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

        <h1>Le tue prenotazioni</h1>

        <div class="cards">

            <?php if ($result->num_rows > 0) { ?>

                <?php while($row = $result->fetch_assoc()) { ?>

                    <div class="card">

                        <h3><?php echo $row['name']; ?></h3>

                        <p>📅 Check-in: <?php echo $row['check_in']; ?></p>
                        <p>📅 Check-out: <?php echo $row['check_out']; ?></p>

                        <p>
                            📌 Status:
                            <strong><?php echo $row['status']; ?></strong>
                        </p>

                        <p>
                            💰 Totale: € <?php echo $row['total_price']; ?>
                        </p>

                        <!-- ELIMINA -->
                        <button class="btn danger"
                            onclick="eliminaPrenotazione(<?php echo $row['id']; ?>)">
                            ❌ Elimina
                        </button>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p>Nessuna prenotazione trovata.</p>

            <?php } ?>

        </div>

    </div>

</div>

<!-- JS SOLO CONFIRM -->
<script>
function eliminaPrenotazione(id) {

    if (confirm("Sei sicuro di voler eliminare questa prenotazione?")) {
        window.location.href = "elimina_prenotazione.php?id=" + id;
    }
}
</script>

</body>
</html>