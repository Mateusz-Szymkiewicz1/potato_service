<?php
session_start();
$tabela = $_POST['tabela'];
$kolumny = "";
$dane = "";
$id_name = $_POST['id_name'];
$row_id = $_POST['row_id'];
foreach ($_POST as $key => $value){
    if($key != "tabela" and $key != "id_name" and $key != "row_id"){
        if(substr($key, -3) == "num"){
          if($value == ""){
              $key2 = substr($key, 0, -3);
              $kolumny = $kolumny.$key2.",";
              $dane = $dane."null".',';
              continue;
          }else{
              $key2 = substr($key, 0, -3);
              $kolumny = $kolumny.$key2.",";
              $number = intval($value);
              $dane = $dane.$number.',';
              continue;  
          }
        }
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
$kolumny_tab = explode(",",$kolumny);
$dane_tab = explode(",",$dane);
$final_string = "";
for($i = 0; $i < count($kolumny_tab);$i++){
    $final_string = $final_string.$kolumny_tab[$i]." = ".$dane_tab[$i].",";
}
$final_string = rtrim($final_string, ",");
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
    if($wiersz_user['Update_priv'] != "Y"){
       echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_error=1045");'.'</script>';
       die;
    }else{
        $sql = "UPDATE $tabela SET $final_string WHERE $id_name = $row_id;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        echo '<script>'.'window.location.replace("tabela.php?name='.$tabela.'&insert_status=2");'.'</script>';
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
