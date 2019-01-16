<?php
session_start();
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
            <a class="navitem selected" href="index.php">Główna</a>
            <a class="navitem" href="oferta.php">Oferta</a>
            <?php
            if (isset($_SESSION['user'])) {
                echo "<a class='navitem' href='add.php'>Dodaj</a>";
                echo "<a class='navitem' href='logout.php'>Wyloguj ( ".$_SESSION['user']." )</a>";
            } else {
                echo "<a class='navitem' href='login.php'>Logowanie</a>";
            }
            ?>
        </div>
    </header>
</body>
</html>