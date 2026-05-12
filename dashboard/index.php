<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

/* utente */
$email = $_SESSION['utente'];
$user = $conn->query("SELECT * FROM users WHERE email='$email'")->fetch_assoc();

/* dati base */
$rooms = $conn->query("SELECT COUNT(*) as total FROM rooms")->fetch_assoc()['total'];
$bookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>EcoVerde Tower</title>
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

    <!-- CONTENUTO -->
    <div class="content">

        <!-- HERO -->
        <div class="hero">

            <h1> Benvenuto nell’ EcoVerde Tower</h1>

            <p>
                EcoVerve Tower nasce dall'idea di fondere l'architettura verticale contemporanea con la natura rigogliosa dei giardini pensili.
                Ogni piano porta con sè terrazze verdi, orti condominiali e spazi di biodiversità urbana, riducendo l'isola di calore e migliorando il benessere degli abitanti.
            </p>

            <div class="hero-user">
                👤 Utente: <strong><?php echo $user['email']; ?></strong>
            </div>

        </div>

        <!-- STATS -->
        <div class="cards">

            <div class="card">
                <h3>🏨 Piani totali</h3>
                <p class="big"> 18 </p>
                <p>+ 2 interrati.</p>
                <p>Tutti i piani sono immersi nel verde.</p>
            </div>

            <div class="card">
                <h3>🌲 Giardini</h3>
                <p class="big"> 3200m² </p>
                <p>Il nostro hotel fornice ampi spazi verdi.</p>
            </div>

            <div class="card">
                <h3>✔️ Classe enrgetica</h3>
                <p class="big"> A+ </p>
                <p>L'hotel è totalmente ecologico.</p>
            </div>

            <div class="card">
                <h3>🛏️ Camere disponibili</h3>
                <p class="big"><?php echo $rooms; ?></p>
                <p>Suite eco, loft verdi e camere bamboo immerse nella natura.</p>
            </div>

            <div class="card">
                <h3>📅 Prenotazioni totali</h3>
                <p class="big"><?php echo $bookings; ?></p>
                <p>Ospiti che hanno scelto un soggiorno sostenibile.</p>
            </div>

            <div class="card">
                <h3>🌱 Filosofia eco</h3>
                <p class="big">100%</p>
                <p>Struttura alimentata da energie rinnovabili e materiali naturali.</p>
            </div>

        </div>

        <!-- INFO HOTEL -->
        <div class="info-section">

            <h2> La nostra filosofia</h2>

            <p>
                L'obbiettivo è ottenere la certificazione LEED Platinum e BREEAM Outstanding, diventando un modello replicabile a livello europeo.
            </p>
            <p>
                LEED Platinum è la più alta certificazione internazionale di sostenibilità
                Valuta energia, acqua, materiali e qualità dell'aria interna.
            </p>
            <p>
                BREEAM Outstanding é lo standard europeo con punteggio superiore a 85.
                E' un analisi del ciclo di vita completo dell'edificio.
            </p>

            <p>
                Il nostro hotel è progettato per offrire un’ esperienza di lusso sostenibile.
                Ogni camera è circondata da vegetazione, con pareti verdi, luce naturale e
                materiali eco-compatibili.
            </p>

            <p>
                Gli ospiti possono rilassarsi tra giardini verticali, spazi zen e ambienti
                progettati per il massimo comfort e minimo impatto ambientale.
            </p>

        </div>

        <!-- FOOTER / CONTATTI -->
        <div class="eco-footer">

            <div class="eco-footer-grid">

                <div class="eco-footer-box">
                    <h3>📩 Contattaci</h3>
                    <p>Email: info@ecoverdetower.com</p>
                    <p>Telefono: +39 333 123 4567</p>
                </div>

                <div class="eco-footer-box">
                    <h3>EcoVerde Tower</h3>
                    <p>Hotel sostenibile immerso nella natura verticale.</p>
                </div>

                <div class="eco-footer-box">
                    <h3>🔗 Link utili</h3>
                    <a href="camere.php">Camere</a><br>
                    <a href="prenotazioni.php">Prenotazioni</a>
                </div>

                <div class="eco-footer-box">
                    <h3>🌐 Social</h3>
                    <p>Instagram: @ecoverdetower</p>
                    <p>TikTok: @ecoverde</p>
                </div>

            </div>

            <div class="eco-footer-bottom">
                © 2026 EcoVerde Tower
            </div>

        </div>

    </div>

</body>
</html>