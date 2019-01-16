<?php
session_start();
require_once("config.php");
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DB);
$sql = "SELECT * FROM samochody INNER JOIN typy ON typy.id = samochody.typ_id WHERE 1=1"; // 'WHERE <cokolwiek>' żeby w sortowaniu nie dodawać WHERE tylko od razu AND

$przebieg_od = "";
$przebieg_do = "";
$rok_produkcji_od = "";
$rok_produkcji_do = "";
$cena_od = "";
$cena_do = "";
$typ = "";

// Sortowanie
if (isset($_POST) && isset($_POST['submit'])) {
    $przebieg_od = mysqli_real_escape_string($db, $_POST['przebieg_od']);
    $przebieg_do = mysqli_real_escape_string($db, $_POST['przebieg_do']);
    $rok_produkcji_od = mysqli_real_escape_string($db, $_POST['rok_produkcji_od']);
    $rok_produkcji_do = mysqli_real_escape_string($db, $_POST['rok_produkcji_do']);
    $cena_od = mysqli_real_escape_string($db, $_POST['cena_od']);
    $cena_do = mysqli_real_escape_string($db, $_POST['cena_do']);
    $typ = mysqli_real_escape_string($db, $_POST['typ']);
    if ($typ == -1)
        $typ = "";

    if ($przebieg_od != "")
        $sql = $sql." AND samochody.przebieg >= ".$przebieg_od;
    if ($przebieg_do != "")
        $sql = $sql." AND samochody.przebieg <= ".$przebieg_do;
    if ($rok_produkcji_od != "")
        $sql = $sql." AND samochody.rok_produkcji >= ".$rok_produkcji_od;
    if ($rok_produkcji_do != "")
        $sql = $sql." AND samochody.rok_produkcji <= ".$rok_produkcji_do;
    if ($cena_od != "")
        $sql = $sql." AND samochody.cena >= ".$cena_od;
    if ($cena_do != "")
        $sql = $sql." AND samochody.cena <= ".$cena_do;
    if ($typ != "")
        $sql = $sql." AND samochody.typ_id = ".$typ;
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
            <a class="navitem selected" href="oferta.php">Oferta</a>
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

    <div class="content cars" style="flex-direction: row; flex-wrap: wrap; padding-left: 10%; padding-right: 10%; align-content: flex-start;">
        <div class="panel panel-filter" style="align-items: center; width: 100%; flex-direction: row; justify-content: space-between;">
            <b>Filtrowanie</b>
            <form method="POST" style="color: #646464">
                <span style="margin-right: 12px;">
                    Przebieg
                    <input type="text" name="przebieg_od" placeholder="Od" style="width: 5em" value="<?php echo $przebieg_od; ?>">
                    <input type="text" name="przebieg_do" placeholder="Do" style="width: 5em" value="<?php echo $przebieg_do; ?>">
                </span>
                <span style="margin-right: 12px;">
                    Rok produkcji
                    <input type="text" name="rok_produkcji_od" placeholder="Od" style="width: 5em" value="<?php echo $rok_produkcji_od; ?>">
                    <input type="text" name="rok_produkcji_do" placeholder="Do" style="width: 5em" value="<?php echo $rok_produkcji_do; ?>">
                </span>
                <span style="margin-right: 12px;">
                    Cena
                    <input type="text" name="cena_od" placeholder="Od" style="width: 5em" value="<?php echo $cena_od; ?>">
                    <input type="text" name="cena_do" placeholder="Do" style="width: 5em" value="<?php echo $cena_do; ?>">
                </span>
                <span style="margin-right: 12px;">
                    <label for="typ">Typ</label>
                    <select id="typ" name="typ">
                        <option value="-1">Każdy</option>
                        <?php
                            $query = mysqli_query($db, "SELECT * FROM typy;");
                            while ($row = mysqli_fetch_array($query)) {
								if ($typ == $row[0]) {
									echo "<option selected value='".$row[0]."'>".$row[1]."</option>";
								} else {
									echo "<option value='".$row[0]."'>".$row[1]."</option>";
								}
                            }
                        ?>
                    </select>
                </span>
                <input type="submit" name="submit" class="btn btn-small" value="Filtruj">
            </form>
        </div>
        
        <?php
            $query = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_array($query)) {
                $id = $row[0];
                $marka = $row['marka'];
                $model = $row['model'];
                $typ = $row['typ'];
                $rok_prod = $row['rok_produkcji'];
                $przebieg = number_format($row['przebieg'], 0, ",", " ");
                $cena = number_format($row['cena'], 0, ",", " ");
                $zdjecie = $row['zdjecie'];

                echo "<div class='panel'>";
                echo "<h2 style='margin: 0 0 16px 0;'>$marka $model ";
                if (isset($_SESSION['user']) && $_SESSION['level'] >= 9) {
                    echo "<span style='position: absolute; right: 0;'>";
                    echo "<a class='fas fa-edit' href='edit.php?id=$id' style='margin-right: 8px;'></a>";
                    echo "<a class='fas fa-trash' href='delete.php?id=$id'></a>";
                    echo "</span>";
                }
                echo "</h2>";
                echo "<div class='row' style='align-items: flex-start; position: relative;'>";
                echo "<div class='item-box'>";
                echo "<img class='item-img' src='img/samochody/$zdjecie.jpg'>";
                echo "<p class='item-price'><b>$cena zł</b></p>";
                echo "</div>";
                echo "<div class='item-info'>";
                echo "<p>Typ: <b>$typ</b></p>";
                echo "<p>Rok produkcji: <b>$rok_prod</b></p>";
                echo "<p>Przebieg: <b>$przebieg km</b></p>";
                echo "<a class='btn grow' style='position: absolute; bottom: 0; right: 0;' href='auto.php?id=$id'>Szczegóły</a>";
                echo "</div></div></div>";
            }
        ?>
    </div>
</body>
</html>
