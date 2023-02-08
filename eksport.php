<?php
session_start();
$export_tables = $_POST['export_tables'] ?? null;
$login = $_SESSION['login'];
if($login and $export_tables){
    try{
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        $mysqlUserName = $login;
        $mysqlPassword = $_SESSION['haslo'];
        $mysqlHostName = "localhost";
        $DbName = "baza_testowa";
        $backup_name = "baza_testowa.sql";
        $tables = explode(",",$export_tables);
        function Export_Database($host,$user,$pass,$name,  $tables, $backup_name){
            $mysqli = new mysqli($host,$user,$pass,$name); 
            $mysqli->select_db($name); 
            $mysqli->query("SET NAMES 'utf8'");
            foreach($tables as $table){
                $result         =   $mysqli->query('SELECT * FROM '.$table);  
                $fields_amount  =   $result->field_count;  
                $rows_num=$mysqli->affected_rows;     
                $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
                $TableMLine     =   $res->fetch_row();
                $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";
                for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
                    while($row = $result->fetch_row())  {
                        if ($st_counter%100 == 0 || $st_counter == 0 )  {
                            $content .= "\nINSERT INTO ".$table." VALUES";
                        }
                        $content .= "\n(";
                        for($j=0; $j<$fields_amount; $j++)  { 
                            $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                            if (isset($row[$j])){
                                $content .= '"'.$row[$j].'"' ; }
                            else {   
                                $content .= '""';}     
                            if ($j<($fields_amount-1)){
                                $content.= ',';}      
                            }
                            $content .=")";
                            if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {   
                                $content .= ";";
                            } 
                            else{
                                $content .= ",";
                            } 
                            $st_counter=$st_counter+1;
                    }
                } $content .="\n\n\n";
            }
            $backup_name = $backup_name ? $backup_name : $name.".sql";
            header('Content-Type: application/octet-stream');   
            header("Content-Transfer-Encoding: Binary"); 
            header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
            echo $content;exit;
        }
        Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables, $backup_name);
        echo '<script>'.'window.location.replace("import_eksport.php?operation_info=1");'.'</script>';
    }catch(PDOException $e){
        $errorInfo = $e->errorInfo;
        if($errorInfo[1]){
            echo '<script>'.'window.location.replace("import_eksport.php?error='.$errorInfo[1].'");'.'</script>';
        }else{
            echo '<script>'.'window.location.replace("import_eksport?error='.$e->getCode().'");'.'</script>';
        }
    }
}else{
    echo '<script>'.'window.location.replace("import_eksport.php");'.'</script>';
}
?>
