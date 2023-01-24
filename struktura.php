<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Struktura - <?=$_GET['name']?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="css/tabela.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <a href="tabela.php?name=<?=$_GET['name']?>" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>Struktura - <?=$_GET['name']?></h1>
    <?php
    function str_contains($haystack, $needle) {
                return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
    session_start();
        $tabela = $_GET['name'] ?? null;
        if(!$tabela or !$_SESSION['login']){
            echo '<script>'.'window.location.replace("home.php");'.'</script>';
            die;
        }else{
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
                if(!$wiersz_user['Alter_priv']){
                    echo '<script>'.'window.location.replace("home.php");'.'</script>';
                }
                $sql = "SELECT * FROM $tabela LIMIT 1;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<table><tr><th>Nazwa</th><th>Typ</th><th>Null</th><th>Domyślnie</th><th>Inkrementacja</th></tr>';
                foreach($wiersz as $key => $value){
                    $sql_column = "SHOW COLUMNS FROM $tabela WHERE Field = '$key'";
                    $stmt_column = $db->prepare($sql_column);
                    $stmt_column->execute();
                    $wiersz_column = $stmt_column->fetch(PDO::FETCH_ASSOC);
                    echo '<tr>';
                        echo '<td>'.$wiersz_column["Field"];
                        if($wiersz_column["Key"] == "PRI"){
                            echo '&nbsp&nbsp&nbsp<i class="fa fa-key primary_key"></i>';
                        }
                        if($wiersz_column["Key"] == "MUL" or $wiersz_column["Key"] == "UNI"){
                            echo '&nbsp&nbsp&nbsp<i class="fa fa-key foreign_key"></i>';
                        }
                        echo '</td>';
                        echo '<td>'.$wiersz_column["Type"].'</td>';
                        echo '<td>'.$wiersz_column["Null"].'</td>';
                        $default = $wiersz_column['Default'];
                        if(!$default){
                            $default = "null";
                        }
                        echo '<td>'.$default.'</td>';
                        $increment = "false";
                        if(str_contains($wiersz_column['Extra'], "auto_increment")){
                            $increment = "true";
                        }
                        echo '<td>'.$increment.'</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            catch(Exception $e){
                echo $e;
            }
        }
    ?>
</body>
</html>