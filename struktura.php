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
    <script src="js/struktura.js" defer></script>
    <script src="js/shared.js" defer></script>
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
    <h1>Struktura - <?=$_GET['name']?><br/><span class="error"></span></h1>
    <?php
    $add_column_error = $_GET['add_column_error'] ?? null;
    $operation_info = $_GET['add_column_info'] ?? null;
    if($add_column_error){
        require_once "get_err_desc.php";
        $message = get_err_desc($error);
        echo '<div class="insert_response insert_error">'.$add_column_error." - ".$message.'</div>';
    }
    if($operation_info == 1){
        echo '<div class="insert_response">Pomyślnie dodano kolumnę!</div>';
    }
    if($operation_info == 2){
        echo '<div class="insert_response">Pomyślnie edytowano kolumnę!</div>';
    }
    if($operation_info == 3){
        echo '<div class="insert_response">Pomyślnie usunięto kolumny!</div>';
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
                if($wiersz_user['Alter_priv'] != "Y"){
                    echo '<script>'.'window.location.replace("home.php");'.'</script>';
                }
                echo '<i class="fa fa-plus" id="plus"></i>';
                echo '<i class="fa fa-pencil" id="pencil"></i>';
                echo '<i class="fa fa-ban" id="block"></i>';
                echo '<i class="zaznacz">Zaznacz wszystko</i>';
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
        <form action="add_column.php" method="post" id="insert_form">
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
    <div class="update_form insert_form" style="display: none;">
        <h2>Edytuj pole</h2>
        <form action="edit_column.php" method="post" id="update_form">
            <input type="text" value="<?=$tabela?>" name="tabela" hidden>
            <input type="text" value="" name="old_name" id="old_name" hidden>
            <label>Nazwa</label><input type="text" name="updated_nazwa" id="updated_nazwa" required><br/>
            <label>Typ</label><select name="updated_typ" id="updated_typ">
                <option value="int">INT</option>
                <option value="varchar">VARCHAR</option>
                <option value="text">TEXT</option>
                <option value="date">DATE</option>
            </select><br/>
            <label>Długość/Wartości</label><input type="text" name="updated_dlugosc" id="updated_dlugosc"><br/>
            <label>Domyślnie</label><select name="new_default" id="updated_default">
                <option value="none">...</option>
                <option value="null">NULL</option>
                <option value="timestamp">Current_timestamp</option>
                <option value="defined">Zdefiniuj</option>
            </select>
            <input type="text" name="updated_defined_default" id="updated_defined_default" hidden><br/>
            <label>NULL</label><input type="checkbox" name="updated_null" id="updated_null"><br/>
            <label>Indeks</label><select name="updated_index" id="updated_index">
                <option value="">...</option>
                <option value="PRI">PRIMARY</option>
                <option value="UNI">UNIQUE</option>
            </select><br/>
            <label>AI</label><input type="checkbox" name="updated_ai" id="updated_ai"><br/>
            <input type="submit"><button>Anuluj</button>
        </form>
    </div>
    <form action="delete_column.php" method="post" id="delete_form" hidden>
            <input type="text" value="<?=$tabela?>" name="tabela" hidden>
            <input type="text" name="columns" value="" class="delete_input">
            <input type="submit">
    </form>
</body>
</html>