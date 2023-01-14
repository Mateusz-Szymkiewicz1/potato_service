<?php
// odebranie danych z formularza logowania
$username = $_POST["username"];
$password = $_POST["password"];
// połączenie z bazą danych
try {
    $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $username, $password,array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
  ));
 echo '<script>'.'window.location.replace("home.php");'.'</script>';
}
catch(PDOException $e) {
    echo '<script>'.'window.location.replace("index.php?error_code='.$e->getCode().'");'.'</script>';
}
?>
