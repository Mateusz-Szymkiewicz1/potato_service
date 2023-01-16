<?php
session_start();
$tabela = $_POST['tabela'];
$kolumny = "";
$dane = "";
foreach ($_POST as $key => $value){
    if($key != "tabela" and $value != "Dodaj"){
        if($value == ""){
            $kolumny = $kolumny.$key.",";
            $dane = $dane.'""'.',';
        }else{
            if(is_numeric($value)){
            $kolumny = $kolumny.$key.",";
            $dane = $dane.$value.',';
            }else{
                $kolumny = $kolumny.$key.",";
                $dane = $dane.'"'.$value.'",';
            }
        }
    }
}
$kolumny = rtrim($kolumny, ",");
$dane = rtrim($dane, ",");
try{
    $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $_SESSION['login'], $_SESSION['haslo'],array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ));
    $login = $_SESSION['login'];
    $sql = "SELECT * FROM mysql.user WHERE USER LIKE '$login';";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $wiersz_user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$wiersz_user['Insert_priv']){
       echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_error=1045");'.'</script>';
       die;
    }else{
        $sql = "INSERT INTO $tabela ($kolumny) VALUES ($dane);";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_status=OK");'.'</script>';
    }
}catch(PDOException $e){
     $errorInfo = $e->errorInfo;
    if($errorInfo[1]){
        echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_error='.$errorInfo[1].'");'.'</script>';
    }else{
        echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_error='.$e->getCode().'");'.'</script>';
    }
    die;
}
?>