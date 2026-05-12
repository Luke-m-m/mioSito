<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-box">
    <h2>Registrati</h2>

    <form action="save.php" method="POST">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="password" name="confirm_password" placeholder="Conferma Password" required>

        <button type="submit">Crea account</button>
    </form>

    <p>Hai già un account? <a href="index.php">Login</a></p>
</div>

</body>
</html>