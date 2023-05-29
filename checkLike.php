<?php
    /*******************************************************
        Controlla se esiste il like 
    ********************************************************/
   
    require_once 'checkSession.php';

    if(!checkSession()){
        header('Location: index_home.php');
        exit;
    }
    $userid=$_SESSION['user_id'];
    

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    $gameid=mysqli_real_escape_string($conn,$_GET["game_id"]);

    $query = "SELECT * FROM likes WHERE idUser = '$userid' AND idGame = '$gameid'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    if(mysqli_num_rows($res) > 0) {
        echo json_encode(array('exists' => true, 'idGame' => $gameid));
        exit;
    }
    mysqli_close($conn);
    echo json_encode(array('exists' => false, 'idGame' => $gameid));
?>