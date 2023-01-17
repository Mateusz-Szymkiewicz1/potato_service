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
 <?php
    $insert_status = $_GET['insert_status'] ?? null;
    $insert_error = $_GET['insert_error'] ?? null;
    if($insert_status != null){
        echo '<div class="insert_response">Zapytanie INSERT zakończone pomyślnie ;)</div>';
    }
    if($insert_error != null){
        switch($insert_error){
            case 1452:
                $error_message = "Wprowadzone klucze obce są niepoprawne!";
                break;
            case 1049:
                $error_message = "Nie udało się połączyć z bazą danych!";
                break;
            case 1045:
                $error_message = "Nie przyznano dostępu użytkownikowi!";
                break;
            default:
                $error_message = "Nieprzewidziany błąd!";
                break;
        }
        echo '<div class="insert_response insert_error">'.$insert_error.' - '.$error_message.'</div>';
    }
    ?>
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
                $login = $_SESSION['login'];
                $sql = "SELECT * FROM mysql.user WHERE USER LIKE '$login';";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $wiersz_user = $stmt->fetch(PDO::FETCH_ASSOC);
                if($wiersz_user['Insert_priv']){
                    echo '<i class="fa fa-plus" id="plus"></i>';
                }
                if($wiersz_user['Select_priv']){
                $sql = "SELECT * FROM $tabela;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<table><tr>';
                $types = [];
                foreach($wiersz as $key => $value){
                    $sql3 = "SHOW COLUMNS FROM $tabela WHERE Field = '$key'";
                    $stmt3 = $db->prepare($sql3);
                    $stmt3->execute();
                    $wiersz3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                    array_push($types, $wiersz3["Type"]);
                   echo '<th>'.$key;
                    if($wiersz3['Key'] == "MUL" or $wiersz3['Key'] == "UNI"){
                        echo '&nbsp&nbsp<i class="fa fa-key foreign_key"></i>';
                    }
                    if($wiersz3['Key'] == "PRI"){
                        echo '&nbsp&nbsp<i class="fa fa-key primary_key"></i>';
                    }
                    echo '</th>';
                }
                echo '</tr>';
                echo "<tr>";
                foreach($wiersz as $key => $value){
                     echo '<td>'.$value.'</td>';
                }
                echo '</tr>';
                while($wiersz = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $wiersz2 = $wiersz;
                    echo "<tr>";
                    foreach($wiersz as $key => $value){
                        echo '<td>'.$value.'</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';
                }else{
                   echo '<script>'.'window.location.replace("home.php");'.'</script>';
                    die; 
                }
            }
            catch(Exception $e){
                echo $e;
            }
            function str_starts_with($haystack, $needle) {
                return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
            }
            function str_contains($haystack, $needle) {
                return $needle !== '' && mb_strpos($haystack, $needle) !== false;
            }
            $licznik = 0;
            echo '<div class="insert_form" style="display: none;"><h2>Dodaj wiersz</h2><form action="insert.php" method="post"><input type="text" value="'.$tabela.'" name="tabela" hidden>';
            foreach($wiersz2 as $key => $value){
                $sql = "SHOW COLUMNS FROM `$tabela` WHERE Field = '$key';";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $wiersz_kolumna = $stmt->fetch(PDO::FETCH_ASSOC);
                $null = $wiersz_kolumna["Null"];
                $ai = false;
                if(str_contains($wiersz_kolumna["Extra"],"auto_increment")){
                    $ai = true;
                }
                if(str_starts_with($types[$licznik], 'int') and $ai == false){
                    echo '<input type="number" placeholder="0" name="'.$key.'num"';
                    if($null == "NO"){
                        echo 'required';
                    }
                    echo '>'.$key.'<br/>';
                }
                if(str_starts_with($types[$licznik], 'varchar') and $ai == false){
                    echo '<input type="text" placeholder="'.$value.'..." name="'.$key.'"';
                    if($null == "NO"){
                        echo 'required';
                    }
                    echo '>'.$key.'<br/>';
                }
                if(str_starts_with($types[$licznik], 'enum') and $ai == false){
                    $arr = explode("'",$types[$licznik]);
                    array_splice($arr,0,1);
                    array_splice($arr,count($arr)-1,1);
                    echo '<select name="'.$key.'"';
                    if($null == "NO"){
                        echo 'required';
                    }    
                    echo '>';
                    for($i = 0; $i<count($arr);$i++){
                        if($arr[$i] != ","){
                            echo '<option value="'.$arr[$i].'">'.$arr[$i].'</option>';
                        }
                    }
                    echo '</select>'.$key.'<br/>';
                }
                $licznik++;
            }
            echo '<input type="submit" value="Dodaj"><button>Anuluj</button></form></div>';
        }
    ?>
    <script>
        document.querySelector("#plus").addEventListener("click", function(){
            if(document.querySelector(".insert_form").style.display == "none"){                
                document.querySelector(".insert_form").style.animation = "slideInDown 0.4s ease";
                document.querySelector(".insert_form").style.display = "block";
            }else{
                document.querySelector(".insert_form").style.animation = "slideOutUp 0.4s ease";
                setTimeout(function(){
                    document.querySelector(".insert_form").style.display = "none";
                }, 350)
            }
        })
        document.querySelector(".insert_form button").addEventListener("click", function(e){
            e.preventDefault();
            document.querySelector(".insert_form").style.animation = "slideOutUp 0.4s ease";
            setTimeout(function(){
                document.querySelector(".insert_form").style.display = "none";
            }, 350)
        })
        document.querySelector("#plus").style.display = "none";
        setTimeout(function(){
            var left = document.querySelector("table").offsetLeft-50;
            document.querySelector("#plus").style.marginLeft = left+"px";  
            document.querySelector("#plus").style.display = "block";
        },100) 
        window.addEventListener("resize", function(){
            var left = document.querySelector("table").offsetLeft-50;
            document.querySelector("#plus").style.marginLeft = left+"px";  
            document.querySelector("#plus").style.display = "block";
        })
        document.querySelector(".insert_response").addEventListener("click", function(){
            if(document.querySelector(".insert_response")){
                document.querySelector(".insert_response").style.display = "none";
            }
        })
    </script>
</body>
</html>