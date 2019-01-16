<?php
session_start();
require_once("config.php");
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DB);

if (isset($_GET) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    $q = mysqli_query($db, "SELECT * FROM samochody INNER JOIN typy ON samochody.typ_id = typy.id WHERE samochody.id = '$id'");
    $r = mysqli_fetch_array($q);
    $marka = $r['marka'];
    $model = $r['model'];
    $typ_id = $r['typ_id'];
    $typ = $r['typ'];
    $rok_produkcji = $r['rok_produkcji'];
    $przebieg = $r['przebieg'];
    $cena = $r['cena'];
    $zdjecie = $r['zdjecie'];

    $przebieg = number_format($przebieg, 0, ",", " ");
    $cena = number_format($cena, 0, ",", " ");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/salon.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
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
                echo "<a class='navitem' href='login.php'>Logowanie</a>";
            }
            ?>
        </div>
    </header>

    <div class="content cars" style="flex-direction: row; flex-wrap: wrap; padding-left: 30%; padding-right: 30%; align-content: flex-start;">
        <div class="panel">
            <?php
                echo "<h2 style='margin: 0 0 16px 0; position: relative;'>$marka $model ";
                if (isset($_SESSION['user']) && $_SESSION['level'] >= 9) {
                    echo "<span style='position: absolute; right: 0;'>";
                    echo "<a class='fas fa-edit' href='edit.php?id=$id' style='margin-right: 8px;'></a>";
                    echo "<a class='fas fa-trash' href='delete.php?id=$id'></a>";
                    echo "</span>";
                }
                echo "</h2>";
                echo "<div class='row' style='align-items: flex-start; position: relative;'>";
                echo "<img class='item-img img-big' src='img/samochody/$zdjecie.jpg'>";
                echo "<p class='item-price'><b>$cena zł</b></p>";
                echo "<div class='item-info'>";
                echo "<p>Typ: <b>$typ</b></p>";
                echo "<p>Rok produkcji: <b>$rok_produkcji</b></p>";
                echo "<p>Przebieg: <b>$przebieg km</b></p>";
                echo "</div></div>";
            ?>
        </div>
    </div>
</body>
</html>