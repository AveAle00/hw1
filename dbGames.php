<?php
    require_once 'checkSession.php';

    if(!checkSession()){
        header('Location: index_home.php');
        exit;
    }

    $userid=$_SESSION['user_id'];
    $gameInfo=array();
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $query = "SELECT games.* FROM games JOIN likes ON games.id = likes.idGame where likes.idUser='$userid';";
    $res= mysqli_query($conn, $query) or die(mysqli_error($conn));
    
        while($row = mysqli_fetch_assoc($res))
        {
            $gameInfo[] = $row;
        }
        mysqli_free_result($res);
        mysqli_close($conn);
        echo json_encode($gameInfo);
?>