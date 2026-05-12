<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

/* ===== PRENDO TUTTE LE CAMERE ===== */
$sql = "SELECT * FROM rooms ORDER BY id ASC";
$result = $conn->query($sql);

/* ===== DISTRIBUZIONE SU 18 PIANI ===== */
$rooms_by_floor = [];

if ($result && $result->num_rows > 0) {
    $floor = 1;

    while ($row = $result->fetch_assoc()) {
        $rooms_by_floor[$floor][] = $row;

        $floor++;
        if ($floor > 18) {
            $floor = 1;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camere</title>
    <link rel="stylesheet" href="../css/dashboard.css">

    <style>
        .floor-title {
            margin-top: 30px;
            color: #2f5d3a;
            font-size: 20px;
            border-left: 4px solid #2f5d3a;
            padding-left: 10px;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            width: 260px;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-top h3 {
            margin: 0;
            color: #2f5d3a;
        }

        .card-body {
            font-size: 14px;
            color: #555;
            margin: 10px 0;
        }

        .price {
            font-weight: bold;
            color: #2f5d3a;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin-top: 8px;
            background: #2f5d3a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #3f7a4e;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>EcoVerdeTower</h2>

        <a href="index.php">🏠 Dashboard</a>
        <a href="camere.php">🛏️ Camere</a>
        <a href="prenotazioni.php">📅 Prenotazioni</a>
        <a href="profilo.php">👤 Profilo</a>
        <a href="../logout.php">🚪 Logout</a>
    </div>

    <!-- CONTENUTO -->
    <div class="content">

        <h1>Camere disponibili per piano </h1>

        <?php for ($i = 1; $i <= 18; $i++) { ?>

            <div class="floor-title">
                Piano <?php echo $i; ?>
            </div>

            <div class="cards">

                <?php if (!empty($rooms_by_floor[$i])) { ?>

                    <?php foreach ($rooms_by_floor[$i] as $row) { ?>

                        <div class="card">

                            <div class="card-top">
                                <h3><?php echo $row['name']; ?></h3>
                            </div>

                            <div class="card-body">
                                <p><?php echo $row['description']; ?></p>
                            </div>

                            <div class="card-bottom">
                                <p class="price">
                                    € <?php echo $row['price_per_night']; ?> / notte
                                </p>

                                <a class="btn" href="infocamera.php?id=<?php echo $row['id']; ?>">
                                    Info
                                </a>

                                <a class="btn" href="prenota.php?id=<?php echo $row['id']; ?>">
                                    Prenota
                                </a>
                            </div>

                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <p style="color:#888;">Nessuna camera su questo piano</p>

                <?php } ?>

            </div>

        <?php } ?>

    </div>

</div>

</body>
</html>