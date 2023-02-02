<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PS - Strona główna</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="css/home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php // połączenie użytkownika z bazą
    session_start();
    if(!$_SESSION['login']){
        session_destroy();
        echo '<script>'.'window.location.replace("index.php");'.'</script>';
    }else{
        $login = $_SESSION['login'];
        $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        $sql = "SELECT * FROM mysql.user WHERE USER LIKE '$login';";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $wiersz_user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>
<body>
    <div class="top">
        <a href="#" draggable="false">
            <img src="favicon.ico" alt="Logo" height="70px" width="70px" class="logo" draggable="false">
            <h1>Strona główna</h1>
        </a>
    </div>
    <span class="greet">Witaj, <?=$_SESSION['login']?></span><br />
    <span class="greet2">Co będziemy dzisiaj robić?</span>
    <div class="grid">
        <?php
            if($wiersz_user['Select_priv'] == "Y"){
               echo '<div class="card" id="tabele"><i class="fa fa-table"></i><span>Tabele</span></div>';
            }
            if($wiersz_user['Grant_priv'] == "Y" or $wiersz_user['Create_user_priv'] == "Y"){
               echo '<a href="users.php" draggable="false"><div class="card"><i class="fa fa-user"></i><span>Użytkownicy</span></div></a>';
            }
       ?>
       <a href="sql.php" draggable="false">
            <div class="card">
                <i class="fa fa-terminal"></i>
                <span>SQL</span>
            </div>
        </a>
        <a href="index.php" draggable="false">
            <div class="card">
                <i class="fa fa-sign-out"></i>
                <span>Wyloguj</span>
            </div>
        </a>
    </div>
    <div class="select_table" style="display: none;">
        <h2>Wybierz tabelę:</h2>
        <select name="select_table" id="select_table">
            <?php
                $sql = "SHOW TABLES;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                while($wiersz = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo '<option>'.$wiersz["Tables_in_baza_testowa"].'</option>';
                }
            ?>
        </select><br />
        <button class="przejdz">Przejdź</button>
        <button class="anuluj">Anuluj</button>
    </div>
    <script>
        document.querySelector("#tabele").addEventListener("click", function() {
            if (document.querySelector(".select_table").style.display == "none") {
                document.querySelector(".select_table").style.animation = "slideInDown 0.5s ease";
                document.querySelector(".select_table").style.display = "block";
            }
        })
        document.querySelector(".anuluj").addEventListener("click", function() {
            document.querySelector(".select_table").style.animation = "slideOutUp 0.5s ease";
            setTimeout(function() {
                document.querySelector(".select_table").style.display = "none";
            }, 500)
        })
        document.querySelector(".przejdz").addEventListener("click", function() {
            let tabela = document.querySelector("select").value;
            window.location.replace(`tabela.php?name=${tabela}`);
        })
    </script>
</body>

</html>
