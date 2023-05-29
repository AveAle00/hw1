<?php
    require_once 'checkSession.php';
    
    if($_SESSION['user_id']!==checkSession()){
        header('Location: index_home.php');
        exit;
    }

    $userid=$_SESSION['user_id'];
        
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $query = "SELECT * FROM users WHERE id = '$userid'";
    $res= mysqli_query($conn, $query) or die(mysqli_error($conn));

    while($row = mysqli_fetch_assoc($res))
    {
            $userInfo[] = $row;
    }

    $data=$userInfo[0]['sign_up_date'];
    $timestamp=strtotime($data);
    $sign_up_date=date("d/m/Y", $timestamp);

?>

<html>
    <head>
        <title> <?php echo $userInfo[0]['name']. " "  .$userInfo[0]['surname'] ?> - MyGameVault </title>
        <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display&display=swap" rel="stylesheet">       
        <link rel="stylesheet" href="hw1.css"/>
        <link rel="stylesheet" href="profile.css"/>
        <meta charset="utf-8">
        <script src="profile.js" defer="true"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="image/favicon.png">
    </head>
    <body>
        <div id=overlay></div>
        <nav>
        <img src="image/logo_white.png" id="logo">
        <div>
            <a href=user_home.php>Home</a>
            <a href='logout.php'> Logout</a>
        </div>
        </nav>
        <header>
            <div id='profileSide'>
                <div id='profile'>
                    <img src= <?php echo  $userInfo[0]['photo_path'] !== "" ? $userInfo[0]['photo_path'] : "image/default_avatar.png"?> id='photo'> 
                    <div id='camera'><img src=image/camera.svg></div>
                    <input type='file' class='hidden'>
                </div>
                <span id='error' class='hidden'></span>
            </div>               
            <div id=userInfo>
                <span id='name_surname'> <?php echo $userInfo[0]['name']. " "  .$userInfo[0]['surname']?></span>
                <span id='date'>Data di iscrizione: <?php echo $sign_up_date ?> </span>
            </div>
        </header>
        <article>
            <span id="title">I tuoi videogiochi preferiti</span>
        </article>
        <footer>
            <span>Creato da Alessandro Aveni - Matricola: O46002043</span>
            <span>Corso di Web Programming 2023</span>
        </footer>
    </body>
</html>