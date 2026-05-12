<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

$username = $_SESSION['utente'];

/* USER */
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Utente non trovato");
}

$user_id = $user['id'];

/* CARDS */
$cards = $conn->query("
    SELECT * FROM payment_methods
    WHERE user_id = $user_id
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profilo</title>

    <!--FIX IMPORTANTE PER RESPONSIVE -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h2>EcoVerde Tower</h2>

        <a href="index.php">🏠 Dashboard</a>
        <a href="camere.php">🛏️ Camere</a>
        <a href="prenotazioni.php">📅 Prenotazioni</a>
        <a href="profilo.php">👤 Profilo</a>
        <a href="../logout.php">🚪 Logout</a>

    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- HERO PROFILO -->
        <div class="profile-hero">

            <div class="avatar">
                <?php echo strtoupper(substr($user['first_name'] ?? $user['email'], 0, 1)); ?>
            </div>

            <div>

                <h2>
                    <?php echo ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''); ?>
                </h2>

                <p>Eco Member • Viaggi sostenibili</p>

            </div>

        </div>

        <!-- LAYOUT PROFILO -->
        <div class="profile-grid">

            <!-- FORM -->
            <div class="card big">

                <h2>Informazioni personali</h2>

                <form method="POST" class="form">

                    <div class="grid-2">

                        <div>
                            <label>Nome</label>
                            <input type="text" name="first_name"
                                value="<?php echo $user['first_name'] ?? ''; ?>">
                        </div>

                        <div>
                            <label>Cognome</label>
                            <input type="text" name="last_name"
                                value="<?php echo $user['last_name'] ?? ''; ?>">
                        </div>

                    </div>

                    <div>
                        <label>Email</label>
                        <input type="text"
                            value="<?php echo $user['email']; ?>"
                            disabled>
                    </div>

                    <div>
                        <label>Telefono</label>
                        <input type="text" name="phone"
                            value="<?php echo $user['phone'] ?? ''; ?>">
                    </div>

                    <button class="btn" name="update_profile">
                        💾 Salva modifiche
                    </button>

                </form>

            </div>

            <!-- SIDE INFO -->
            <div class="side">

                <div class="card mini">
                    <span>🌱 Stato account</span>
                    <strong>Eco Premium</strong>
                </div>

                <div class="card mini">
                    <span>📧 Email</span>
                    <strong><?php echo $user['email']; ?></strong>
                </div>

                <div class="card mini eco">
                    <span>🌍 Impatto ambientale</span>
                    <strong>-32% CO₂</strong>
                </div>

            </div>

        </div>

        <!-- PAGAMENTI -->
        <div class="section-title">
            💳 Metodi di pagamento
        </div>

        <div class="cards">

            <?php while($card = $cards->fetch_assoc()) { ?>

            <div class="card payment">

                <div class="chip">VISA</div>

                <h3><?php echo $card['card_name']; ?></h3>

                <p>**** **** **** <?php echo substr($card['card_number'], -4); ?></p>

                <small>Scadenza: <?php echo $card['card_exp']; ?></small>

                <form method="POST"
                    action="elimina_carta.php"
                    onsubmit="return confirm('Eliminare questa carta?')">

                    <input type="hidden"
                        name="delete_card_id"
                        value="<?php echo $card['id']; ?>">

                    <button type="submit" class="btn danger">
                        🗑️ Elimina
                    </button>

                </form>

            </div>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>