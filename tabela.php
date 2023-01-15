<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tabela - <?=$_GET['name']?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="css/tabela.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <a href="home.php"><img src="images/back.png" height="60px" width="50px" class="arrow"></a>
   <h1>Tabela - <?=$_GET['name']?></h1>
    <?php
    session_start();
        $tabela = $_GET['name'] ?? null;
        if($tabela == null or !$_SESSION['login']){
            echo '<script>'.'window.location.replace("home.php");'.'</script>';
            die;
        }else{
            try{
                $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $_SESSION['login'], $_SESSION['haslo'],array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ));
                $sql = "SELECT * FROM $tabela;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<table><tr>';
                foreach($wiersz as $key => $value){
                   echo '<th>'.$key.'</th>';
                }
                echo '</tr>';
                while($wiersz2 = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    foreach($wiersz2 as $key => $value){
                        echo '<td>'.$value.'</td>';
                    }
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