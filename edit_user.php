<?php
session_start();
$login = $_SESSION['login'];

$user_name = $_POST['user_name'] ?? null;

$privs = "";
$grant = "";
$use_with = "";
$use_with_limits = "";
$limits = "";
isset($_POST['edit_user_select']) ? $privs=$privs."SELECT," : null;
isset($_POST['edit_user_insert']) ? $privs=$privs."INSERT," : null;
isset($_POST['edit_user_update']) ? $privs=$privs."UPDATE," : null;
isset($_POST['edit_user_delete']) ? $privs=$privs."DELETE," : null;
isset($_POST['edit_user_file']) ? $privs=$privs."FILE," : null;
isset($_POST['edit_user_create']) ? $privs=$privs."CREATE," : null;
isset($_POST['edit_user_alter']) ? $privs=$privs."ALTER," : null;
isset($_POST['edit_user_index']) ? $privs=$privs."INDEX," : null;
isset($_POST['edit_user_drop']) ? $privs=$privs."DROP," : null;
isset($_POST['edit_user_create_tmp_tables']) ? $privs=$privs."CREATE TEMPORARY TABLES," : null;
isset($_POST['edit_user_show_view']) ? $privs=$privs."SHOW VIEW," : null;
isset($_POST['edit_user_create_routine']) ? $privs=$privs."CREATE ROUTINE," : null;
isset($_POST['edit_user_alter_routine']) ? $privs=$privs."ALTER ROUTINE," : null;
isset($_POST['edit_user_execute']) ? $privs=$privs."EXECUTE," : null;
isset($_POST['edit_user_create_view']) ? $privs=$privs."CREATE VIEW," : null;
isset($_POST['edit_user_event']) ? $privs=$privs."TRIGGER," : null;
isset($_POST['edit_user_trigger']) ? $privs=$privs."EVENT," : null;
isset($_POST['edit_user_grant']) ? $grant="GRANT OPTION" : null;
isset($_POST['edit_user_super']) ? $privs=$privs."SUPER," : null;
isset($_POST['edit_user_process']) ? $privs=$privs."PROCESS," : null;
isset($_POST['edit_user_reload']) ? $privs=$privs."RELOAD," : null;
isset($_POST['edit_user_shutdown']) ? $privs=$privs."SHUTDOWN," : null;
isset($_POST['edit_user_show_db']) ? $privs=$privs."SHOW DATABASES," : null;
isset($_POST['edit_user_lock_tables']) ? $privs=$privs."LOCK TABLES," : null;
isset($_POST['edit_user_references']) ? $privs=$privs."REFERENCES," : null;
isset($_POST['edit_user_repl_client']) ? $privs=$privs."REPLICATION CLIENT," : null;
isset($_POST['edit_user_repl_slave']) ? $privs=$privs."REPLICATION SLAVE," : null;
isset($_POST['edit_user_create_user']) ? $privs=$privs."CREATE USER," : null;
$privs = rtrim($privs, ",");

$_POST['edit_user_max_queries'] != "0" ? $limits=$limits."MAX_QUERIES_PER_HOUR ".$_POST['edit_user_max_queries']." " : null;
$_POST['edit_user_max_updates'] != "0" ? $limits=$limits."MAX_UPDATES_PER_HOUR ".$_POST['edit_user_max_updates']." " : null;
$_POST['edit_user_max_conns'] != "0" ? $limits=$limits."MAX_CONNECTIONS_PER_HOUR ".$_POST['edit_user_max_conns']." " : null;
$_POST['edit_user_max_user_conns'] != "0" ? $limits=$limits."MAX_USER_CONNECTIONS ".$_POST['edit_user_max_user_conns']." " : null;

if($grant != "" or $limits != ""){
    $use_with = "WITH";
}
if($limits != ""){
    $use_with_limits = "WITH";
}

if($login and $user_name){
    try{
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        $sql = "SELECT Host FROM mysql.user WHERE User = '$user_name'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = '\''.$user_name.'\'@\''.$wiersz['Host'].'\'';
        $sql = "REVOKE ALL PRIVILEGES ON *.* FROM $user;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if($privs != ""){
            $sql = "GRANT $privs ON *.* TO $user $use_with $limits $grant;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }else if($grant != ""){
            $sql = "GRANT GRANT OPTION ON *.* TO $user $use_with_limits $limits;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        echo '<script>'.'window.location.replace("users.php?operation_info=2");'.'</script>';
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