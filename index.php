<!--http://localhost/sito/ (link per il sito web)-->
<!-- http://localhost/phpmyadmin/ (database)-->

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Accedi</button>
    </form>

    <p>Accedi al tuo account</p>
    <p>
        Non hai un account?
        <a href="register.php">Registrati</a>
    </p>
</div>

</body>
</html>