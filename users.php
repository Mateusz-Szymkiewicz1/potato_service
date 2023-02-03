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
    <script src="js/users.js" defer></script>
    <style>
        td{
            max-width: 400px;
            word-wrap: break-word;
        }
        .insert_form{
            left: 20%;
            right: 20%;
        }
    </style>
</head>
<body>
    <a href="home.php" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>Użytkownicy<br/><span class="error"></span></h1>
    <?php
        $error = $_GET['error'] ?? null;
        $operation_info = $_GET['operation_info'] ?? null;
        if($error){
            switch($error){
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
            echo '<div class="insert_response insert_error">'.$error." - ".$err_desc.'</div>';
        }
        if($operation_info == 1){
            echo '<div class="insert_response">Pomyślnie dodano użytkownika!</div>';
        }
        if($operation_info == 3){
            echo '<div class="insert_response">Pomyślnie usunięto użytkownika!</div>';
        }
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
            echo '<i class="fa fa-plus" id="plus"></i>';
            echo '<i class="fa fa-ban" id="block"></i>';
            echo '<br/><br/><br/><table><tr><th>Nazwa</th><th>Host</th><th>Uprawnienia</th><th>Nadawanie</th></tr>';
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
    <div class="insert_form new_user" style="display: none;">
        <h2>Dodaj użytkownika<br/><span class="new_user_error"></span></h2>
        <form action="add_user.php" id="new_user" method="post">
            <label>Nazwa* </label><input type="text" name="new_user_name" id="new_user_name" required><br/>
            <label>Host* </label><input type="text" name="new_user_host" id="new_user_host" value="%" required><br/>
            <label>Hasło </label><input type="password" name="new_user_pass" id="new_user_pass"><br/>
            <label>Powtórz hasło </label><input type="password" name="new_user_pass2" id="new_user_pass2"><br/>
            <span class="span_wygeneruj">Wygeneruj hasło</span><br/>
            <div class="section">
                <p>Dane <input type="checkbox" class="section_checkbox"></p>
                <label>Select</label><input type="checkbox" name="new_user_select">
                <label>Insert</label><input type="checkbox" name="new_user_insert">
                <label>Update</label><input type="checkbox" name="new_user_update">
                <label>Delete</label><input type="checkbox" name="new_user_delete">
                <label>File</label><input type="checkbox" name="new_user_file">
            </div>
            <div class="section">
                <p>Struktura <input type="checkbox" class="section_checkbox"></p>
                <label>Create</label><input type="checkbox" name="new_user_create">
                <label>Alter</label><input type="checkbox" name="new_user_alter">
                <label>Index</label><input type="checkbox" name="new_user_index">
                <label>Drop</label><input type="checkbox" name="new_user_drop">
                <label>Create temporary tables</label><input type="checkbox" name="new_user_create_temporary">
                <label>Show view</label><input type="checkbox" name="new_user_show_view">
                <label>Create routine</label><input type="checkbox" name="new_user_create_routine">
                <label>Alter routine</label><input type="checkbox" name="new_user_alter_routine">
                <label>Execute</label><input type="checkbox" name="new_user_execute">
                <label>Create view</label><input type="checkbox" name="new_user_create_view">
                <label>Event</label><input type="checkbox" name="new_user_event">
                <label>Trigger</label><input type="checkbox" name="new_user_trigger">
            </div>
            <div class="section">
                <p>Administracja <input type="checkbox" class="section_checkbox"></p>
                <label>Grant</label><input type="checkbox" name="new_user_grant">
                <label>Super</label><input type="checkbox" name="new_user_super">
                <label>Process</label><input type="checkbox" name="new_user_process">
                <label>Reload</label><input type="checkbox" name="new_user_reload">
                <label>Shutdown</label><input type="checkbox" name="new_user_shutdown">
                <label>Show databases</label><input type="checkbox" name="new_user_show_databases">
                <label>Lock tables</label><input type="checkbox" name="new_user_lock_tables">
                <label>References</label><input type="checkbox" name="new_user_references">
                <label>Replication client</label><input type="checkbox" name="new_user_replication_client">
                <label>Replication slave</label><input type="checkbox" name="new_user_replication_slave">
                <label>Create user</label><input type="checkbox" name="new_user_create_user">
            </div>
            <div class="section">
                <p>Ograniczenia zasobów<br/><br/>Uwaga: Ustawienie tych opcji na 0 (zero) usuwa ograniczenie.</p>
                <label>Max queries per hour</label><input type="number" name="new_user_max_queries" value="0" min="0"><br/>
                <label>Max updates per hour</label><input type="number" name="new_user_max_updates" value="0" min="0"><br/>
                <label>Max connections per hour</label><input type="number" name="new_user_max_conns" value="0" min="0"><br/>
                <label>Max user_connections per hour</label><input type="number" name="new_user_max_user_conns" value="0" min="0">
            </div><br/>
            <input type="submit" value="Dodaj"><button>Anuluj</button>
        </form>
        <form action="delete_user.php" method="post" id="delete_form" hidden>
            <input type="text" name="users" value="" class="delete_input">
            <input type="submit">
        </form>
    </div>
</body>
</html>