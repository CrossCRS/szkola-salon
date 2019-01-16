<?php
session_start();

require_once("config.php");
if (isset($_POST) && isset($_POST['login']) && isset($_POST['pass'])) {
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DB);
    $login = mysqli_real_escape_string($db, $_POST['login']);
    $pass = hash('sha256', mysqli_real_escape_string($db, $_POST['pass']));
    
    $q = mysqli_query($db, "SELECT level FROM users WHERE username = '$login' AND password = '$pass'");
    if (mysqli_num_rows($q) == 1) {
        $level = mysqli_fetch_array($q)[0];
        $_SESSION['user'] = $login;
        $_SESSION['level'] = $level;
        header('location: index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/salon.css">
    <title>Salon Samochodowy</title>
</head>
<body>
    <header>
        <h2>Salon</h2>
        <div class="menu">
            <a class="navitem" href="index.php">Główna</a>
            <a class="navitem" href="oferta.php">Oferta</a>
            <?php
            if (isset($_SESSION['user'])) {
                echo "<a class='navitem' href='add.php'>Dodaj</a>";
                echo "<a class='navitem' href='logout.php'>Wyloguj ( ".$_SESSION['user']." )</a>";
            } else {
                echo "<a class='navitem selected' href='login.php'>Logowanie</a>";
            }
            ?>
        </div>
    </header>

    <div class="content">
        <div class="panel">
            <form action="#" method="POST">
                <h5 style="margin: 0 0 16px 0;">LOGOWANIE</h5>
                <div class="row"><label for="login" style="margin-right: 4px;">Login: </label><input type="text" class="grow" id="login" name="login"></div>
                <div class="row"><label for="pass" style="margin-right: 4px;">Hasło: </label><input type="password" class="grow" id="pass" name="pass"></div>
                <div class="row"><input type="submit" class="btn grow" value="Zaloguj"></div>
                <p style="color: #727272; margin: 12px 0 0 0; text-align: center;">Domyślne - admin:admin</p>
            </form>
        </div>
    </div>
</body>
</html>