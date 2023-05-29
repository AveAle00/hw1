<?php
    require_once 'checkSession.php';

    if($_SESSION['user_id']!==checkSession()){
        header('Location: index_home.php');
        exit;
    }
?>

<html>
    <head>
        <title> Home - <?php echo $_SESSION['username'] ?> </title>
        <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display&display=swap" rel="stylesheet">       
        <link rel="stylesheet" href="hw1.css"/>
        <link rel="stylesheet" href="user_home.css"/>
        <meta charset="utf-8">
        <script src="user_home.js" defer="true"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="image/favicon.png">
    </head>
    <body>
        <div id=overlay></div>
        <nav>
        <img src="image/logo_white.png" id="logo">
        <div>
            <a href='profile.php'>Profilo</a>
            <a href='logout.php'> Logout</a>
        </div>
        </nav>
        <article>
            <span id='welcome'> Benvenuto <em><?php echo $_SESSION['username']?></em></span>      
            <h1 id='title'>Cerca i giochi che ti interessano.</h1>
            
            <form autocomplete="off">
                <div id="search">
                    <input type='text' name="search" id="searchBar">
                    <input type="submit" value="Cerca" id="submit">
                </div>
            </form>
            
            <h1 id='searchLine'>Qui sotto compariranno i giochi che cercherai.</h1>

            <section>

            </section>

        </article>
        <footer>
            <span>Creato da Alessandro Aveni - Matricola: O46002043</span>
            <span>Corso di Web Programming 2023</span>
        </footer>
    </body>
</html>