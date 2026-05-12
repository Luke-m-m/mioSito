<?php
session_start();

if (!isset($_SESSION['utente'])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/db.php";

if (!isset($_GET['id'])) {
    die("Camera non valida");
}

$id = (int) $_GET['id'];

/* camera */
$room = $conn->query("SELECT * FROM rooms WHERE id = $id")->fetch_assoc();

if (!$room) {
    die("Camera non trovata");
}

/* prenotazioni occupate */
$bookings = $conn->query("
    SELECT check_in, check_out 
    FROM bookings 
    WHERE room_id = $id AND status != 'cancelled'
");

$busy = [];

while ($b = $bookings->fetch_assoc()) {

    $start = strtotime($b['check_in']);
    $end = strtotime($b['check_out']);

    while ($start <= $end) {
        $busy[] = date("Y-m-d", $start);
        $start = strtotime("+1 day", $start);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prenota Camera</title>
    <link rel="stylesheet" href="../css/dashboard.css">

    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            max-width: 650px;
            margin: 20px auto;
            user-select: none;
        }

        .day {
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            background: #e5e7eb;
            cursor: pointer;
        }

        .busy {
            background: #ef4444;
            color: white;
            cursor: not-allowed;
        }

        .selected {
            background: #38bdf8;
            color: white;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="sidebar">
        <h2>🏨 Hotel</h2>
        <a href="index.php">🏠 Dashboard</a>
        <a href="camere.php">🛏️ Camere</a>
        <a href="prenotazioni.php">📅 Prenotazioni</a>
        <a href="profilo.php">👤 Profilo</a>
        <a href="../logout.php">🚪 Logout</a>
    </div>

    <div class="content">

        <h1>Prenota: <?php echo $room['name']; ?></h1>

        <p class="price">
            € <?php echo $room['price_per_night']; ?> / notte
        </p>

        <h3>📅 Seleziona le date</h3>

        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:10px;">
            <button type="button" onclick="prevMonth()">⬅</button>
            <span id="monthLabel"></span>
            <button type="button" onclick="nextMonth()">➡</button>
        </div>

        <div class="calendar" id="calendar"></div>

        <form method="POST" action="pagamento.php" onsubmit="return validateDates()">

            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
            <input type="hidden" name="check_in" id="check_in">
            <input type="hidden" name="check_out" id="check_out">

            <button class="btn" type="submit">
                Continua al pagamento
            </button>

        </form>

    </div>

</div>

<script>
    let busy = <?php echo json_encode($busy); ?>;

    let current = new Date();

    const calendar = document.getElementById("calendar");
    const label = document.getElementById("monthLabel");

    let startDate = null;
    let endDate = null;
    let selectedDays = [];

    function toISO(date) {
        return date.toISOString().split("T")[0];
    }

    /* CREA RANGE DATE */
    function getRange(start, end) {

        let a = new Date(start);
        let b = new Date(end);

        if (a > b) [a, b] = [b, a];

        let arr = [];

        while (a <= b) {
            arr.push(toISO(new Date(a)));
            a.setDate(a.getDate() + 1);
        }

        return arr;
    }

    /* CONTROLLA DATE OCCUPATE */
    function rangeHasBusyDays(range) {
        return range.some(day => busy.includes(day));
    }

    /* UPDATE FORM */
    function updateForm() {

        if (!startDate || !endDate) return;

        document.getElementById("check_in").value = startDate;
        document.getElementById("check_out").value = endDate;
    }

    /* VALIDAZIONE */
    function validateDates() {

        if (!startDate || !endDate) {
            alert("Seleziona le date");
            return false;
        }

        if (startDate === endDate) {
            alert("Seleziona almeno 1 notte");
            return false;
        }

        return true;
    }

    /* RENDER CALENDARIO */
    function render() {

        calendar.innerHTML = "";

        let year = current.getFullYear();
        let month = current.getMonth();

        label.innerText = current.toLocaleString('it-IT', {
            month: 'long',
            year: 'numeric'
        });

        let lastDay = new Date(year, month + 1, 0);

        let today = new Date();
        today.setHours(0,0,0,0);

        for (let i = 1; i <= lastDay.getDate(); i++) {

            let date = new Date(year, month, i);
            let iso = toISO(date);

            let div = document.createElement("div");

            div.classList.add("day");
            div.innerText = i;

            if (date < today || busy.includes(iso)) {
                div.classList.add("busy");
            }

            if (selectedDays.includes(iso)) {
                div.classList.add("selected");
            }

            div.addEventListener("click", () => {

                if (div.classList.contains("busy")) return;

                /* PRIMO CLICK */
                if (!startDate || (startDate && endDate)) {

                    startDate = iso;
                    endDate = null;
                    selectedDays = [iso];

                } else {

                    /* SECONDO CLICK */
                    let range = getRange(startDate, iso);

                    if (rangeHasBusyDays(range)) {
                        alert("Nel range selezionato ci sono date occupate");
                        return;
                    }

                    endDate = iso;
                    selectedDays = range;
                }

                updateForm();
                render();
            });

            calendar.appendChild(div);
        }
    }

    /* CAMBIO MESE */
    function nextMonth() {
        current.setMonth(current.getMonth() + 1);
        render();
    }

    function prevMonth() {
        current.setMonth(current.getMonth() - 1);
        render();
    }

    render();
</script>
</body>
</html>