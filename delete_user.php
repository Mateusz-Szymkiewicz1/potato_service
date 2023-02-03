<?php
session_start();
$login = $_SESSION['login'];
$users = $_POST['users'] ?? null;
$final_string = "";
$users_tab = explode(",",$users);
if($login and $users){
    try{
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        foreach($users_tab as $value){
            $sql = "SELECT Host FROM mysql.user WHERE User = '$value'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
            $final_string = $final_string.'\''.$value.'\'@\''.$wiersz['Host'].'\',';
        }
        $final_string = rtrim($final_string, ",");
        $sql = "DROP USER $final_string;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        echo '<script>'.'window.location.replace("users.php?operation_info=3");'.'</script>';
    }catch(PDOException $e){
        $errorInfo = $e->errorInfo;
        if($errorInfo[1]){
            echo '<script>'.'window.location.replace("users.php?error='.$errorInfo[1].'");'.'</script>';
        }else{
            echo '<script>'.'window.location.replace("users.php?error='.$e->getCode().'");'.'</script>';
        }
    }
}else{
    echo '<script>'.'window.location.replace("users.php");'.'</script>';
}
?>