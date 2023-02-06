<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PS - Import/Eksport</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="css/tabela.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Extra+Condensed:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body{
            padding-bottom: 80px;
        }
        .box{
            background: #eed996;
            height: 300px;
            width: 550px;
            color: #fff;
            text-align: center;
            padding-top: 50px;
        }
        .wrapper{
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }
        .box i{
            font-size: 50px;
        }
        input{
            cursor: pointer;
        }
        form label, input[type=submit]{
            display: block;
            font-size: 26px;
            border: 2px solid #fff;
            padding: 10px;
            width: 100px;
            margin: auto;
            margin-top: 40px;
            cursor: pointer;
            transition: 0.4s;
            font-weight: 600;
        }
        form label:hover{
            background: #fff;
            color: #eed996;
        }
        input[type=submit]{
            margin-top: 0px;
            font-family: inherit;
            background: #fff;
            color: #eed996;
        }
        span{
            display: block;
            font-size: 26px;
            font-weight: 600;
            padding-top: 15px;
        }
        #export_box{
            height: auto;
            padding-bottom: 50px;
        }
        .tabele{
            background: #ccb774;
            width: 285px;
            margin: auto;
            text-align: left;
            padding: 15px;
        }
        p{
            font-size: 24px;
            margin: 5px;
            padding-left: 5px;
            cursor: pointer;
        }
        p:hover{
            background: #bba663;
        }
        .p_focused{
            background: #bba663;
        }
        .zaznacz{
            margin: 0;
            padding: 0;
            margin-top: 30px;
            margin-bottom: 5px;
        }
        #submit_eksport{
            margin-top: 15px;
        }
        .export_all{
            width: 200px !important;
            margin-top: 20px !important;
        }
    </style>
</head>
<body>
    <a href="home.php" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>Import/Eksport<br/><span class="error"></span></h1>
    <?php
        session_start();
        if(!$_SESSION['login']){
            echo '<script>'.'window.location.replace("home.php");'.'</script>';
            die;
        }else{
            $login = $_SESSION['login'];
            $db = new PDO("mysql:host=localhost;dbname=baza_testowa", $login, $_SESSION['haslo'],array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $sql = "SELECT * FROM mysql.user WHERE USER LIKE '$login';";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $wiersz_user = $stmt->fetch(PDO::FETCH_ASSOC);
            if($wiersz_user['File_priv'] != "Y"){
               echo '<script>'.'window.location.replace("home.php");'.'</script>';
               die;
            }
        }
    ?>
    <div class="wrapper">
        <div class="box" id="import_box">
            <i class="fa fa-download"></i>
            <form action="import_eksport.php" method="post" enctype="multipart/form-data">
                <label for="import_file">Wybierz plik</label>
                <input type="file" id="import_file" name="import_file" hidden><br>
                <input type="submit" value="Import">
            </form>
        </div>
        <div class="box" id="export_box">
            <i class="fa fa-upload"></i>
            <span>Wybierz tabele do eksportu: &nbsp;&nbsp;</span>
            <span class="zaznacz">Zaznacz wszystkie</span>
            <div class="tabele">
                <?php
                    $sql = "SHOW TABLES;";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    while($wiersz = $stmt->fetch(PDO::FETCH_ASSOC)){
                        echo '<p>'.$wiersz["Tables_in_baza_testowa"]."</p>";
                    }
                ?>
            </div><br/>
            <form action="import_eksport.php" method="post">
                <input type="submit" value="Eksport" id="submit_eksport">
            </form>
            <span>lub</span>
            <form action="import_eksport.php" method="post">
                <input type="submit" value="Eksportuj całą bazę" class="export_all">
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll(".tabele p").forEach(p => {
            p.addEventListener("click", function () {
                if (p.className == "p_focused") {
                    p.removeAttribute("class");
                } else {
                    p.classList.add("p_focused");
                }
            })
        })
        let zaznacz_counter = 1;
        document.querySelector(".zaznacz").addEventListener("click", function (e) {
            if (zaznacz_counter % 2) {
                document.querySelectorAll(".tabele p").forEach(p => {
                    p.className = "p_focused";
                    e.target.innerText = "Odznacz wszystko";
                })
            } else {
                if (document.querySelector(".p_focused")) {
                    document.querySelectorAll(".tabele p").forEach(p => {
                       p.removeAttribute("class");
                    })
                }
                e.target.innerText = "Zaznacz wszystko";
            }
            zaznacz_counter++;
        })
    </script>
</body>
</html>