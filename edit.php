<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['level'] < 9)
    die('Brak uprawnień');

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
}
if (isset($_POST) && isset($_POST['id'])) {
    $marka = mysqli_real_escape_string($db, $_POST['marka']);
    $model = mysqli_real_escape_string($db, $_POST['model']);
    $rok_produkcji = mysqli_real_escape_string($db, $_POST['rok_produkcji']);
    $przebieg = mysqli_real_escape_string($db, $_POST['przebieg']);
    $cena = mysqli_real_escape_string($db, $_POST['cena']);
    $zdjecie = mysqli_real_escape_string($db, $_POST['zdjecie']);
    $typ = mysqli_real_escape_string($db, $_POST['typ']);

    mysqli_query($db, "UPDATE samochody SET marka='$marka', model='$model', rok_produkcji='$rok_produkcji', przebieg='$przebieg', cena='$cena', zdjecie='$zdjecie' WHERE id='$id'");
    header('location: oferta.php');
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
                <h5 style="margin: 0 0 16px 0;">EDYCJA</h5>
                <div class="row"><input type="number" class="grow" id="id" name="id" readonly value="<?php echo $id; ?>"></div>
                <div class="row"><label for="marka" style="margin-right: 4px;">Marka: </label><input type="text" class="grow" id="marka" name="marka" value="<?php echo $marka; ?>"></div>
                <div class="row"><label for="model" style="margin-right: 4px;">Model: </label><input type="text" class="grow" id="model" name="model" value="<?php echo $model; ?>"></div>
                <div class="row"><label for="rok_produkcji" style="margin-right: 4px;">Rok produkcji: </label><input type="number" class="grow" id="rok_produkcji" name="rok_produkcji" value="<?php echo $rok_produkcji; ?>"></div>
                <div class="row"><label for="przebieg" style="margin-right: 4px;">Przebieg: </label><input type="number" class="grow" id="przebieg" name="przebieg" value="<?php echo $przebieg; ?>"></div>
                <div class="row"><label for="cena" style="margin-right: 4px;">Cena: </label><input type="number" class="grow" id="cena" name="cena" value="<?php echo $cena; ?>"></div>
                <div class="row"><label for="zdjecie" style="margin-right: 4px;">Zdjęcie: </label><input type="text" class="grow" id="zdjecie" name="zdjecie" value="<?php echo $zdjecie; ?>"></div>
                <div class="row"><label for="typ" style="margin-right: 4px;">Typ: </label>
                <select class="grow" id="typ" name="typ">
                    <?php
                        $query = mysqli_query($db, "SELECT * FROM typy;");
                        while ($row = mysqli_fetch_array($query)) {
                            if ($row[0] == $typ_id) {
                                echo "<option value='".$row[0]."' selected>".$row[1]."</option>";
                            } else {
                                echo "<option value='".$row[0]."'>".$row[1]."</option>";
                            };
                        }
                    ?>
                </select>
                </div>
                <div class="row"><input type="submit" class="btn grow" style="margin-top: 10px" value="Zapisz"></div>
            </form>
        </div>
    </div>
</body>
</html>