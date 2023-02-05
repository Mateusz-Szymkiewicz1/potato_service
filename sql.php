<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PS - SQL</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="css/tabela.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @font-face {
          font-family: terminal;
          src: url(terminal.ttf);
        }
        body{
            overflow-x: hidden;
            padding: 50px;
        }
        textarea{
            display: block;
            margin: auto;
            width: 70%;
            height: 40vh;
            outline: none;
            font-size: 28px;
            font-family: terminal;
            background: #ccb774;
            color: #fff;
            border: 0;
            resize: vertical;
            padding: 20px;
        }
        h1{
            margin-bottom: 30px;
        }
        textarea::placeholder{
          color: #eee;
          opacity: 1; 
        }
        .wykonaj{
            display: block;
            margin: auto;
            margin-top: 30px;
            height: 40px;
            width: 100px;
            cursor: pointer;
            background: transparent;
            border: 1px solid #000;
            outline: none;
            font-family: terminal;
            transition: background 0.4s;
        }
        .wykonaj:hover{
            background: #ccb774;
            color: #fff;
            border: 0;
        }
        .response{
            width: 70%;
            margin: auto;
            margin-top: 30px;
            height: 40vh;
            background: #ccc;
            overflow: auto;
            font-size: 26px;
            padding: 10px;
        }
        .szablon{
            float: left;
            margin-right: 20px;
            height: 30px;
            cursor: pointer;
            background: transparent;
            border: 1px solid #000;
            outline: none;
            font-family: terminal;
            transition: 0.4s;
        }
        .szablon:hover{
            background: #ccb774;
            color: #fff;
        }
    </style>
</head>
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
            case 1064:
                $err_desc = "Błąd składniowy!";
                break;
            case 1142:
                $err_desc = "Brak uprawnień!";
                break;
            case 1146:
                $err_desc = "Podana tabela nie istnieje!";
                break;
            default:
                $err_desc = "Nieprzewidziany błąd!";
                break;
        }
        echo '<div class="insert_response insert_error">'.$error." - ".$err_desc.'</div>';
    }
    if($operation_info == 1){
        echo '<div class="insert_response">Pomyślnie wykonano zapytanie!</div>';
    }
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
    }
?>
<body>
   <a href="home.php" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>SQL</h1>
    <button id="btn_select" class="szablon">SELECT</button>
    <button id="btn_insert" class="szablon">INSERT</button>
    <button id="btn_update" class="szablon">UPDATE</button>
    <br/><br/>
    <form action="sql.php" method="post">
        <textarea spellcheck="false" name="query" placeholder="Zapytanie..."></textarea>
    </form>
    <button class="wykonaj">Wykonaj</button>
    <script>
        async function decision() {
            return new Promise(function (resolve, reject) {
            let decision = document.createElement("div");
            decision.classList.add("decision");
            decision.innerHTML = `<span>Na pewno?</span><br /><button id="button_tak">TAK</button><button id="button_nie">NIE</button>`;
            document.body.appendChild(decision);
            decision.style.animation = "slideInDown 0.5s ease";
            document.querySelector("#button_tak").addEventListener("click", function () {
                resolve();
            })
            document.querySelector("#button_nie").addEventListener("click", function () {
                reject();
            })
            })
        }
        document.querySelector(".wykonaj").addEventListener("click", function(){
            if(document.querySelector("textarea").value){
                decision().then(function () {
                    document.querySelector("form").submit();
                }, function () {
                    document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
                    setTimeout(function () {
                        document.querySelector(".decision").remove();
                    }, 500)
                });
            }
        })
        if (document.querySelector(".insert_response")) {
            document.querySelector(".insert_response").addEventListener("click", function () {
                if (document.querySelector(".insert_response")) {
                    document.querySelector(".insert_response").style.display = "none";
                }
            })
        }
        document.querySelector("#btn_select").style.display = "none";
        setTimeout(function () {
            var left = document.querySelector("textarea").offsetLeft - 50;
            document.querySelector("#btn_select").style.marginLeft = left + "px";
            document.querySelector("#btn_select").style.display = "block";
        }, 100)
        window.addEventListener("resize", function () {
            var left = document.querySelector("textarea").offsetLeft - 50;
            document.querySelector("#btn_select").style.marginLeft = left + "px";
            document.querySelector("#btn_select").style.display = "block";
        })
        document.querySelector("#btn_select").addEventListener("click", function(){
            document.querySelector("textarea").value = "SELECT * FROM table_name WHERE condition;"
        })
        document.querySelector("#btn_insert").addEventListener("click", function(){
            document.querySelector("textarea").value = "INSERT INTO table_name (column1, column2, column3) VALUES (value1, value2, value3);"
        })
        document.querySelector("#btn_update").addEventListener("click", function(){
            document.querySelector("textarea").value = "UPDATE table_name SET column1 = value1, column2 = value2 WHERE condition;"
        })
    </script>
<?php
    $query = $_POST['query'] ?? null;
    if($query){
        try{
            $sql = "$query";
            $stmt = $db->prepare($sql);
            $msc = microtime(true);
            $stmt->execute();
            $msc = microtime(true)-$msc;
            echo '<div class="response">';
            echo 'Wykonanie zajęło ('.$msc.'s)<br/>';
            try{
                while($wiersz = $stmt->fetch(PDO::FETCH_ASSOC)){
                  foreach($wiersz as $key => $value){
                      echo $key." -> ".$value.'<br/>';
                  }
                }
            }catch(PDOException $e){
                $affected = $stmt->rowCount();
                if($affected == 1){
                    echo 'Pomyślnie wykonano operację na '.$affected.' wierszu';
                }else{
                    echo 'Pomyślnie wykonano operację na '.$affected.' wierszach';
                }
            }
            echo '</div>';
        }catch(PDOException $e){
            $errorInfo = $e->errorInfo;
            if($errorInfo[1]){
                echo '<script>'.'window.location.replace("sql.php?error='.$errorInfo[1].'");'.'</script>';
            }else{
                echo '<script>'.'window.location.replace("sql.php?error='.$e->getCode().'");'.'</script>';
            }
        }
    }
?>
</body>
</html>
