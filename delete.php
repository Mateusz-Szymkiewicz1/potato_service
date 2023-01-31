<?php
session_start();
$login = $_SESSION['login'];
$tabela = $_POST['tabela'] ?? null;
$row_names = $_POST['rows'] ?? null;
$id_name = $_POST["id_name"] ?? null;
if($login and $row_names){
    try{
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        $sql = "DELETE FROM $tabela WHERE $id_name IN ($row_names);";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_status=3");'.'</script>';
    }catch(PDOException $e){
        $errorInfo = $e->errorInfo;
        if($errorInfo[1]){
            echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_error='.$errorInfo[1].'");'.'</script>';
        }else{
            echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_error='.$e->getCode().'");'.'</script>';
        }
    }
}else{
    echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'");'.'</script>';
}
?>