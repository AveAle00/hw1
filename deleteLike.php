<?php
    /*******************************************************
        Elimina il like dal database
    ********************************************************/
   
    require_once 'checkSession.php';

    if(!checkSession()){
        header('Location: index_home.php');
        exit;
    }
    $userid=$_SESSION['user_id'];
    
    
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    $gameid=mysqli_real_escape_string($conn,$_GET["game_id"]);
    #query likes
    $query = "DELETE FROM likes WHERE idUser = '$userid' AND idGame = '$gameid'";
    if(mysqli_query($conn, $query) or die(mysqli_error($conn))){
        echo json_encode(array('ans' => "Non ti piace più"));
        exit;
    }
    mysqli_close($conn);
    echo json_encode(array('ans' => "Qualcosa è andato storto"));

?>