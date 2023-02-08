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
        body {
            padding-bottom: 80px;
        }

        .box {
            background: #eed996;
            height: 300px;
            width: 550px;
            color: #fff;
            text-align: center;
            padding-top: 50px;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .box i {
            font-size: 50px;
        }

        input {
            cursor: pointer;
        }

        form label,
        input[type=submit] {
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

        form label:hover {
            background: #fff;
            color: #eed996;
        }

        form label {
            word-break: break-all;
        }

        input[type=submit] {
            margin-top: 0px;
            font-family: inherit;
            background: #fff;
            color: #eed996;
        }

        span {
            display: block;
            font-size: 26px;
            font-weight: 600;
            padding-top: 15px;
        }

        #export_box {
            height: auto;
            padding-bottom: 50px;
        }

        .tabele {
            background: #ccb774;
            width: 285px;
            margin: auto;
            text-align: left;
            padding: 15px;
        }

        p {
            font-size: 24px;
            margin: 5px;
            padding-left: 5px;
            cursor: pointer;
        }

        p:hover {
            background: #bba663;
        }

        .p_focused {
            background: #bba663;
        }

        .zaznacz {
            margin: 0;
            padding: 0;
            margin-top: 30px;
            margin-bottom: 5px;
        }

        #submit_eksport {
            margin-top: 15px;
        }

        .export_all {
            width: 200px !important;
            margin-top: 20px !important;
        }

    </style>
</head>

<body>
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
        if(isset($_FILES["import_file"])){
            $file = $_FILES["import_file"];
            if(substr($file["name"] , -4) != ".sql"){
                echo '<div class="insert_response insert_error">Zły format pliku (wymagany .sql)</div>';
            }else{

            }
        }
    ?>
    <a href="home.php" draggable="false"><img draggable="false" src="images/back.png" height="60px" width="50px" class="arrow"></a>
    <h1>Import/Eksport<br /><span class="error"></span></h1>
    <div class="wrapper">
        <div class="box" id="import_box">
            <i class="fa fa-download"></i>
            <form action="import_eksport.php" id="import_form" method="post" enctype="multipart/form-data">
                <label for="import_file">Wybierz plik</label>
                <input type="file" id="import_file" name="import_file" hidden><br>
                <input type="submit" value="Import" id="import_submit">
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
            </div><br />
            <form action="eksport.php" method="post">
                <input type="text" name="export_tables" hidden>
                <input type="submit" value="Eksport" id="eksport_submit">
            </form>
        </div>
    </div>
    <script>
        if (document.querySelector(".insert_response")) {
            document.querySelector(".insert_response").addEventListener("click", function() {
                if (document.querySelector(".insert_response")) {
                    document.querySelector(".insert_response").style.display = "none";
                }
            })
        }
        document.querySelectorAll(".tabele p").forEach(p => {
            p.addEventListener("click", function() {
                if (p.className == "p_focused") {
                    p.removeAttribute("class");
                } else {
                    p.classList.add("p_focused");
                }
            })
        })
        let zaznacz_counter = 1;
        document.querySelector(".zaznacz").addEventListener("click", function(e) {
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
        async function decision() {
            return new Promise(function(resolve, reject) {
                let decision = document.createElement("div");
                decision.classList.add("decision");
                decision.innerHTML = `<span>Na pewno?</span><button id="button_tak">TAK</button><button id="button_nie">NIE</button>`;
                document.body.appendChild(decision);
                decision.style.animation = "slideInDown 0.5s ease";
                document.querySelector("#button_tak").addEventListener("click", function() {
                    resolve();
                })
                document.querySelector("#button_nie").addEventListener("click", function() {
                    reject();
                })
            })
        }
        document.querySelector("#import_submit").addEventListener("click", function(e) {
            e.preventDefault();
            if (!document.querySelector(".decision") && document.querySelector("input[type=file]").value) {
                decision().then(function() {
                    document.querySelector("#import_submit").parentElement.submit();
                }, function() {
                    document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
                    setTimeout(function() {
                        document.querySelector(".decision").remove();
                    }, 500)
                });
            }
        })
        document.querySelector("#import_file").addEventListener("input", function(e) {
            document.querySelector("#import_form label").innerText = e.target.value.split('\\').pop();
        })
        document.querySelector("#eksport_submit").addEventListener("click", function(e) {
            e.preventDefault();
            let focused = document.querySelectorAll(".p_focused").length;
            if (!document.querySelector(".decision") && focused > 0) {
                document.querySelectorAll(".p_focused").forEach(p => {
                    document.querySelector("input[name=export_tables]").value = document.querySelector("input[name=export_tables]").value+p.innerText+",";
                })
                document.querySelector("input[name=export_tables]").value = document.querySelector("input[name=export_tables]").value.slice(0, -1);
                decision().then(function() {
                    document.querySelector("#eksport_submit").parentElement.submit();
                    document.querySelector(".decision").remove();
                }, function() {
                    document.querySelector(".decision").style.animation = "slideOutUp 0.5s ease";
                    setTimeout(function() {
                        document.querySelector(".decision").remove();
                    }, 500)
                });
            }
        })

    </script>
</body>
</html>