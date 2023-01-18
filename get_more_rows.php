<?php
session_start();
$db = new PDO("mysql:host=localhost;dbname=baza_testowa", $_SESSION['login'], $_SESSION['haslo'],array(
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
));
$tabela = $_GET['tabela'] ?? null;
$last_id = $_GET['last_id'] ?? null;
$login = $_SESSION['login'];
if($last_id != null && $tabela != null){
    $sql = "SELECT * FROM $tabela WHERE id > $last_id LIMIT 50;";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $array = [];
    while($wiersz = $stmt->fetch(PDO::FETCH_ASSOC)){
        array_push($array, $wiersz);
    }
    echo json_encode($array, true);
}
?>