<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Użytkownicy</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="css/tabela.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        td{
            max-width: 400px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <a href="home.php" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>Użytkownicy<br/><span class="error"></span></h1>
    <?php
        session_start();
        if(!$_SESSION['login']){
            echo '<script>'.'window.location.replace("home.php");'.'</script>';
            die;
        }else{
            $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $_SESSION['login'], $_SESSION['haslo'],array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $sql = "Select * from mysql.user;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            echo '<table><tr><th>Nazwa</th><th>Host</th><th>Uprawnienia</th><th>Nadawanie</th></tr>';
            while($wiersz_users = $stmt->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                echo '<td>'.$wiersz_users['User'].'</td>';
                echo '<td>'.$wiersz_users['Host'].'</td>';
                $uprawnienia = "";
                $uprawnienia_counter = 0;
                foreach($wiersz_users as $key => $value){
                    if(substr($key, -4) == "priv"){
                        if($value == "Y"){
                            $uprawnienia = $uprawnienia.substr($key,0,-5).",";
                        }else{
                            $uprawnienia_counter++;
                        }
                    }
                }
                $uprawnienia = rtrim($uprawnienia,",");
                if($uprawnienia_counter == 0){
                    $uprawnienia = "Wszystkie";
                }
                echo '<td>'.$uprawnienia.'</td>';
                if($wiersz_users['Grant_priv'] == "Y"){
                    $nadawanie = "Tak";
                }else{
                    $nadawanie = "Nie";
                }
                echo '<td>'.$nadawanie.'</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    ?>
</body>
</html>