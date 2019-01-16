<?php
if (isset($_GET) && isset($_GET['id'])) {
    session_start();
    if (isset($_SESSION) && $_SESSION['level'] >= 9) {
        require_once("config.php");
        $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_DB);
        $id = mysqli_real_escape_string($db, $_GET['id']);

        mysqli_query($db, "DELETE FROM samochody WHERE id='$id'");
        header('location: oferta.php');
    } else {
        die('Brak uprawnień');
    }
}
?>