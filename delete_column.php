<?php
session_start();
$login = $_SESSION['login'];
$tabela = $_POST['tabela'] ?? null;
$column_names = $_POST['columns'] ?? null;
if($login and $column_names){
    try{
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        $sql = "ALTER TABLE $tabela $column_names";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        echo '<script>'.'window.location.replace("struktura.php?name='.$tabela.'&add_column_info=3");'.'</script>';
    }catch(PDOException $e){
        $errorInfo = $e->errorInfo;
        if($errorInfo[1]){
            echo '<script>'.'window.location.replace("struktura.php?name='.$tabela.'&add_column_error='.$errorInfo[1].'");'.'</script>';
        }else{
            echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&add_column_error='.$e->getCode().'");'.'</script>';
        }
    }
}else{
    echo '<script>'.'window.location.replace("struktura.php?name='.$tabela.'");'.'</script>';
}
?>