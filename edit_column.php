<?php
session_start();
$login = $_SESSION['login'];
$tabela = $_POST['tabela'] ?? null;
$column_name = $_POST['updated_nazwa'] ?? null;
$old_column_name = $_POST['old_name'] ?? null;
$column_type = $_POST['updated_typ'] ?? null;
$column_length = $_POST['updated_dlugosc'] ?? null;
$column_null = $_POST['updated_null'] ?? null;
$column_default = $_POST['updated_default'] ?? null;
$column_defined_default = $_POST['updated_defined_default'] ?? null;
$column_index = $_POST['updated_index'] ?? null;
$column_ai = $_POST['updated_ai'] ?? null;
if($login and $column_name and $column_type){
    try{
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        if($column_length){
        $typ = $column_type."(".$column_length.")";
        }else{
            $typ = $column_type;
        }
        if($column_null){
            $null = "NULL";
        }else{
            $null = "NOT NULL";
        }
        if($column_ai){
            $increment = "auto_increment";
        }else{
            $increment = "";
        }
        if($column_index == "PRI"){
            $key = "primary key";
        }else if($column_index == "UNI"){
            $key = "unique key";
        }else{
            $key = "";
        }
        if($column_default == "null"){
            $default = "DEFAULT(NULL)";
        }else if($column_default == "timestamp"){
            $default = "DEFAULT(CURRENT_TIMESTAMP)";
        }else if($column_defined_default){
            $default = "DEFAULT(".$column_defined_default.")";
        }else{
            $default = "";
        }
        $sql = "ALTER TABLE $tabela MODIFY $old_column_name $typ $null $default $increment $key";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if($old_column_name != $column_name){
            $sql = "ALTER TABLE $tabela CHANGE $old_column_name $column_name $typ;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        echo '<script>'.'window.location.replace("struktura.php?name='.$tabela.'&add_column_info=2");'.'</script>';
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