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
<?php
    session_start();
    if(!$_SESSION['login']){
        session_destroy();
        echo '<script>'.'window.location.replace("index.php");'.'</script>';
    }
?>
<body>
   <div class="top">
       <a href="#">
        <img src="favicon.ico" alt="Logo" height="70px" width="70px" class="logo">
        <h1>Strona główna</h1>
       </a>
    </div>
    <span class="greet">Witaj, <?=$_SESSION['login']?></span><br />
    <span class="greet2">Co będziemy dzisiaj robić?</span>
    <div class="grid">
        <div class="card">
            <i class="fa fa-table"></i>
            <span>Tabele</span>
        </div>
        <div class="card">
            <i class="fa fa-user"></i>
            <span>Użytkownicy</span>
        </div>
        <a href="index.php">
            <div class="card">
                <i class="fa fa-sign-out"></i>
                <span>Wyloguj</span>
            </div>
        </a>
    </div>
</body>
</html>