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
    <script src="js/tabela.js" defer></script>
    <script src="js/shared.js" defer></script>
</head>
<body>
    <?php
    $operation_status = $_GET['insert_status'] ?? null;
    $insert_error = $_GET['insert_error'] ?? null;
    if($operation_status == 1){
        echo '<div class="insert_response">Zapytanie INSERT zakończone pomyślnie ;)</div>';
    }
    if($operation_status == 2){
        echo '<div class="insert_response">Pomyślnie edytowano dane</div>';
    }
    if($operation_status == 3){
        echo '<div class="insert_response">Pomyślnie usunięto dane</div>';
    }
    if($insert_error){
        require_once "get_err_desc.php";
        $message = get_err_desc($error);
        echo '<div class="insert_response insert_error">'.$insert_error.' - '.$message.'</div>';
    }
    ?>
    <a href="home.php" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>Tabela - <?=$_GET['name']?><br/><span class="error"></span></h1>
    <?php
    session_start();
        $tabela = $_GET['name'] ?? null;
        echo '<script>window.tabela = "'.$tabela.'"</script>';
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
                if($wiersz_user['Insert_priv'] == "Y"){
                    echo '<i class="fa fa-plus" id="plus"></i>';
                }
                if($wiersz_user['Update_priv'] == "Y"){
                    echo '<i class="fa fa-pencil" id="pencil"></i>';
                }
                if($wiersz_user['Delete_priv'] == "Y"){
                    echo '<i class="fa fa-ban" id="block"></i>';
                }
                if($wiersz_user['Alter_priv'] == "Y"){
                    echo '<a href="struktura.php?name='.$tabela.'"><i class="fa fa-table" id="struktura"></i></a>';
                }
                echo '<i class="zaznacz">Zaznacz wszystko</i>';
                if($wiersz_user['Select_priv'] == "Y"){
                $sql_count = "SELECT COUNT(*) FROM $tabela;";
                $stmt_count = $db->prepare($sql_count);
                $stmt_count->execute();
                $wiersz_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
                $rows_num = $wiersz_count["COUNT(*)"];
                echo '<script>window.rows_num = '.$rows_num.'</script>';
                $sql = "SELECT * FROM $tabela LIMIT 50;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $wiersz = $stmt->fetch(PDO::FETCH_ASSOC);
                echo '<br/><br/><br/><table><tr>';
                $types = [];
                $licznik = 0;
                $first_column = "";
                foreach($wiersz as $key => $value){
                    if($licznik == 0){
                        $first_column = $key;
                    }
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
                    $licznik++;
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
                if($rows_num > 50){
                    echo '<div class="button_wrapper"><button class="more_btn" id="more_btn">Pokaż więcej</button><button class="more_btn" id="all_btn">Pokaż wszystko</button></div>';
                }
                }else{
                   echo '<script>'.'window.location.replace("home.php");'.'</script>';
                    die; 
                }
            }
            catch(Exception $e){
                echo $e;
            }
            $licznik = 0;
            echo '<div class="insert_form" style="display: none;"><h2>Dodaj wiersz</h2><form action="insert.php" method="post" id="insert_form"><input type="text" value="'.$tabela.'" name="tabela" hidden>';
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
                if((str_starts_with($types[$licznik], 'int') or str_starts_with($types[$licznik], 'decimal')) and $ai == false){
                    echo '<input type="number" placeholder="0" name="'.$key.'num"';
                    if($null == "NO"){
                        echo 'required';
                    }
                    echo '>'.$key.'<br/>';
                }
                if(str_starts_with($types[$licznik], 'date')){
                    echo '<input type="date" name="'.$key.'"';
                    if($null == "NO"){
                        echo 'required';
                    }
                    echo '>'.$key.'<br/>';
                }
                if(str_starts_with($types[$licznik], 'varchar') or str_starts_with($types[$licznik], 'text') or str_starts_with($types[$licznik], 'longtext')){
                    echo '<input type="text" placeholder="'.$value.'..." name="'.$key.'"';
                    if($null == "NO"){
                        echo 'required';
                    }
                    echo '>'.$key.'<br/>';
                }
                if(str_starts_with($types[$licznik], 'enum')){
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
    <form action="delete.php" method="post" id="delete_form" hidden>
        <input type="text" value="<?=$tabela?>" name="tabela">
        <input type="text" value="<?=$first_column?>" name="id_name">
        <input type="text" value="" name="rows" class="delete_input">
        <input type="submit">
    </form>
    <?php echo '<script>window.first_column = "'.$first_column.'";</script>'; ?>
    <script>
        let update_form = document.querySelector(".insert_form").cloneNode(true);
        update_form.querySelector("h2").innerText = "Edytuj wiersz";
        update_form.querySelector("form").action = "update.php";
        update_form.querySelector("form").id = "update_form";
        update_form.querySelector("form").querySelectorAll("*").forEach(el => {
            if(el.tagName == "INPUT" || el.tagName == "SELECT"){
                el.className = "update_input";
            }
        })
        update_form.querySelectorAll("input").forEach(input => input.placeholder = "")
        update_form.querySelector("input[type=submit]").value = "Edytuj";
        update_form.className = "update_form insert_form";
        update_form.querySelector("form").innerHTML = update_form.querySelector("form").innerHTML+`<input type="text" name="id_name" value="${window.first_column}" hidden>`;
        update_form.querySelector("form").innerHTML = update_form.querySelector("form").innerHTML+`<input type="text" name="row_id" class="update_id" hidden>`;
        document.body.appendChild(update_form);
    </script>
</body>
</html>