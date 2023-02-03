<?php
session_start();
$login = $_SESSION['login'];

$new_user_name = $_POST['new_user_name'] ?? null;
$new_user_host = $_POST['new_user_host'] ?? null;
$new_user_pass = $_POST['new_user_pass'] ?? null;

$privs = "";
$grant = "";
$use_with = "";
$use_with_limits = "";
$limits = "";
isset($_POST['new_user_select']) ? $privs=$privs."SELECT," : null;
isset($_POST['new_user_insert']) ? $privs=$privs."INSERT," : null;
isset($_POST['new_user_update']) ? $privs=$privs."UPDATE," : null;
isset($_POST['new_user_delete']) ? $privs=$privs."DELETE," : null;
isset($_POST['new_user_file']) ? $privs=$privs."FILE," : null;
isset($_POST['new_user_create']) ? $privs=$privs."CREATE," : null;
isset($_POST['new_user_alter']) ? $privs=$privs."ALTER," : null;
isset($_POST['new_user_index']) ? $privs=$privs."INDEX," : null;
isset($_POST['new_user_drop']) ? $privs=$privs."DROP," : null;
isset($_POST['new_user_create_temporary']) ? $privs=$privs."CREATE TEMPORARY TABLES," : null;
isset($_POST['new_user_show_view']) ? $privs=$privs."SHOW VIEW," : null;
isset($_POST['new_user_create_routine']) ? $privs=$privs."CREATE ROUTINE," : null;
isset($_POST['new_user_alter_routine']) ? $privs=$privs."ALTER ROUTINE," : null;
isset($_POST['new_user_execute']) ? $privs=$privs."EXECUTE," : null;
isset($_POST['new_user_create_view']) ? $privs=$privs."CREATE VIEW," : null;
isset($_POST['new_user_event']) ? $privs=$privs."TRIGGER," : null;
isset($_POST['new_user_trigger']) ? $privs=$privs."EVENT," : null;
isset($_POST['new_user_grant']) ? $grant="GRANT OPTION" : null;
isset($_POST['new_user_super']) ? $privs=$privs."SUPER," : null;
isset($_POST['new_user_process']) ? $privs=$privs."PROCESS," : null;
isset($_POST['new_user_reload']) ? $privs=$privs."RELOAD," : null;
isset($_POST['new_user_shutdown']) ? $privs=$privs."SHUTDOWN," : null;
isset($_POST['new_user_show_databases']) ? $privs=$privs."SHOW DATABASES," : null;
isset($_POST['new_user_lock_tables']) ? $privs=$privs."LOCK TABLES," : null;
isset($_POST['new_user_references']) ? $privs=$privs."REFERENCES," : null;
isset($_POST['new_user_replication_client']) ? $privs=$privs."REPLICATION CLIENT," : null;
isset($_POST['new_user_replication_slave']) ? $privs=$privs."REPLICATION SLAVE," : null;
isset($_POST['new_user_create_user']) ? $privs=$privs."CREATE USER," : null;
$privs = rtrim($privs, ",");

$_POST['new_user_max_queries'] != "0" ? $limits=$limits."MAX_QUERIES_PER_HOUR ".$_POST['new_user_max_queries']." " : null;
$_POST['new_user_max_updates'] != "0" ? $limits=$limits."MAX_UPDATES_PER_HOUR ".$_POST['new_user_max_updates']." " : null;
$_POST['new_user_max_conns'] != "0" ? $limits=$limits."MAX_CONNECTIONS_PER_HOUR ".$_POST['new_user_max_conns']." " : null;
$_POST['new_user_max_user_conns'] != "0" ? $limits=$limits."MAX_USER_CONNECTIONS ".$_POST['new_user_max_user_conns']." " : null;

if($grant != "" or $limits != ""){
    $use_with = "WITH";
}
if($limits != ""){
    $use_with_limits = "WITH";
}

if($login and $new_user_name and $new_user_host){
    try{
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        $sql = "CREATE USER '$new_user_name'@'$new_user_host' IDENTIFIED BY '$new_user_pass';";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if($privs != ""){
            $sql = "GRANT $privs ON *.* TO '$new_user_name'@'$new_user_host' $use_with $limits $grant;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }else if($grant != ""){
            $sql = "GRANT GRANT OPTION ON *.* TO '$new_user_name'@'$new_user_host' $use_with_limits $limits;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        echo '<script>'.'window.location.replace("users.php?operation_info=1");'.'</script>';
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