<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";
include "../includes/room_data.php";

if (!isset($_GET['id'])) {
    echo "Camera non trovata";
    exit();
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM rooms WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Camera non trovata";
    exit();
}

$room = $result->fetch_assoc();

/* dati dinamici */
$data = $roomsData[$id] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $room['name']; ?></title>

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

        .sidebar {
            width: 220px;
            background: #2f5d3a;
            color: white;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: #3f7a4e;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .room-box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            max-width: 700px;
        }

        h1 {
            color: #2f5d3a;
        }

        .subtitle {
            color: #6b7c6b;
            margin-bottom: 20px;
        }

        ul {
            padding-left: 18px;
        }

        .price {
            font-size: 20px;
            color: #2f5d3a;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            margin-top: 15px;
            background: #2f5d3a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn.secondary {
            background: #777;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="sidebar">
        <h2>EcoVerde Tower</h2>
        <a href="index.php">🏠 Dashboard</a>
        <a href="camere.php">🛏️ Camere</a>
        <a href="prenotazioni.php">📅 Prenotazioni</a>
        <a href="profilo.php">👤 Profilo</a>
        <a href="../logout.php">🚪 Logout</a>
    </div>

    <div class="content">

        <div class="room-box">

            <h1><?php echo $room['name']; ?></h1>

            <p class="subtitle">
                🌿 Eco stay experience nella nostra EcoVerde Tower
            </p>

            <h3>🌱 Descrizione</h3>

            <p>
                <?php echo $data['desc'] ?? 'Descrizione non disponibile per questa camera.'; ?>
            </p>

            <h3>✨ Servizi inclusi</h3>

            <ul>
                <?php if (!empty($data['services'])) { ?>
                    <?php foreach ($data['services'] as $service) { ?>
                        <li><?php echo $service; ?></li>
                    <?php } ?>
                <?php } else { ?>
                    <li>Servizi non disponibili</li>
                <?php } ?>
            </ul>

            <h3>💰 Prezzo</h3>

            <p class="price">
                € <?php echo $room['price_per_night']; ?> / notte
            </p>

            <a class="btn" href="prenota.php?id=<?php echo $room['id']; ?>">
                Prenota ora
            </a>

            <a class="btn secondary" href="camere.php">
                Torna indietro
            </a>

        </div>

    </div>

</div>

</body>
</html>