<?php 

require_once 'checkSession.php';

if(checkSession()){
    header("Location: user_home.php");
    exit;
}

?>
<html>
    <head>
        <title> MyGameVault </title>
        <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="hw1.css"/>
        <link rel="stylesheet" href="index_home.css"/>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="image/favicon.png">
    </head>
    <body>
        <div id="overlay"></div>
        <nav>
            <img src="image/logo_white.png" id="logo">
            <div>
            <a href='login.php'> Login</a>
            <a href='sign_up.php'> Iscriviti</a>
            </div>
        </nav>
        <section>
        <span id="title">MyGameVault</span>
        <br>
        <span id="slogan">Tutto quello che ti serve sul mondo dei videogiochi</span>
        </section>
        <footer>
            <span>Creato da Alessandro Aveni - Matricola: O46002043</span>
            <span>Corso di Web Programming 2023</span>
        </footer>
    </body>
</html>