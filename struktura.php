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
    <style>
        #plus{
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .insert_form{
            min-width: 370px;
        }
    </style>
</head>
<body>
    <a href="tabela.php?name=<?=$_GET['name']?>" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>Struktura - <?=$_GET['name']?></h1>
    <?php
    $add_column_error = $_GET['add_column_error'] ?? null;
    $add_column_info = $_GET['add_column_info'] ?? null;
    if($add_column_error){
        switch($add_column_error){
            case 1062:
                $err_desc = "Klucz unikalny nie może się powtarzać!";
                break;
            case 1068:
                $err_desc = "Może istnieć tylko jeden klucz główny!";
                break;
            case 1075:
                $err_desc = "Może istnieć tylko jedna kolumna z inkrementacją (i musi być ona kluczem)!";
                break;
            default:
                $err_desc = "Nieprzewidziany błąd!";
                break;
        }
        echo '<div class="insert_response insert_error">'.$add_column_error." - ".$err_desc.'</div>';
    }
    if($add_column_info){
        echo '<div class="insert_response">Pomyślnie dodano kolumnę!</div>';
    }
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
                echo '<i class="fa fa-plus" id="plus"></i>';
                echo '<i class="fa fa-pencil" id="pencil"></i>';
                $sql = "SELECT * FROM $tabela LIMIT 1;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<br/><br/><br/><table><tr><th>Nazwa</th><th>Typ</th><th>Null</th><th>Domyślnie</th><th>Extra</th></tr>';
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
                        echo '<td>'.$wiersz_column['Extra'].'</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            catch(Exception $e){
                echo $e;
            }
        }
    ?>
    <div class="insert_form" style="display: none;">
        <h2>Dodaj pole</h2>
        <form action="add_column.php" method="post">
            <input type="text" value="<?=$tabela?>" name="tabela" hidden>
            <label>Nazwa</label><input type="text" name="new_nazwa" required><br/>
            <label>Typ</label><select name="new_typ">
                <option value="int">INT</option>
                <option value="varchar">VARCHAR</option>
                <option value="text">TEXT</option>
                <option value="date">DATE</option>
            </select><br/>
            <label>Długość/Wartości</label><input type="text" name="new_dlugosc"><br/>
            <label>Domyślnie</label><select name="new_default" id="new_default">
                <option value="none">...</option>
                <option value="null">NULL</option>
                <option value="timestamp">Current_timestamp</option>
                <option value="defined">Zdefiniuj</option>
            </select>
            <input type="text" name="new_defined_default" id="new_defined_default" hidden><br/>
            <label>NULL</label><input type="checkbox" name="new_null"><br/>
            <label>Indeks</label><select name="new_index">
                <option value="">...</option>
                <option value="PRI">PRIMARY</option>
                <option value="UNI">UNIQUE</option>
            </select><br/>
            <label>AI</label><input type="checkbox" name="new_ai"><br/>
            <label>Po</label><select name="new_after">
               <option value="">...</option>
                <?php
                    $sql = "SELECT * FROM $tabela LIMIT 1;";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
                    foreach($wiersz as $key => $value){
                        echo '<option value="'.$key.'">'.$key.'</option>';
                    }
                ?>
            </select><br />
            <input type="submit"><button>Anuluj</button>
        </form>
    </div>
    <script>
        document.querySelector("#plus").style.display = "none";
        setTimeout(function() {
            var left = document.querySelector("table").offsetLeft - 50;
            document.querySelector("#plus").style.marginLeft = left + "px";
            document.querySelector("#plus").style.display = "block";
        }, 100)
        window.addEventListener("resize", function() {
            var left = document.querySelector("table").offsetLeft - 50;
            document.querySelector("#plus").style.marginLeft = left + "px";
            document.querySelector("#plus").style.display = "block";
        })
        document.querySelector("#plus").addEventListener("click", function() {
            if (document.querySelector(".insert_form").style.display == "none") {
                document.querySelector(".insert_form").style.animation = "slideInDown 0.4s ease";
                document.querySelector(".insert_form").style.display = "block";
            } else {
                document.querySelector(".insert_form").style.animation = "slideOutUp 0.4s ease";
                setTimeout(function() {
                    document.querySelector(".insert_form").style.display = "none";
                }, 350)
            }
        })
        document.querySelector(".insert_form button").addEventListener("click", function(e) {
            e.preventDefault();
            document.querySelector(".insert_form").style.animation = "slideOutUp 0.4s ease";
            setTimeout(function() {
                document.querySelector(".insert_form").style.display = "none";
            }, 350)
        })
        document.querySelector("#new_default").addEventListener("change", function(e){
            if(e.target.value == "defined"){
                document.querySelector("#new_defined_default").removeAttribute("hidden");
            }else{
                document.querySelector("#new_defined_default").hidden = "true";
            }
        })
         if (document.querySelector(".insert_response")) {
            document.querySelector(".insert_response").addEventListener("click", function() {
                if (document.querySelector(".insert_response")) {
                    document.querySelector(".insert_response").style.display = "none";
                }
            })
        }
    </script>
</body>
</html>