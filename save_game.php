<?php
    /*******************************************************
        Inserisce nel database il post da pubblicare 
    ********************************************************/
   
    require_once 'checkSession.php';

    if($_SESSION['user_id']!==checkSession()){
        header('Location: index_home.php');
        exit;
    }

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
        
    # Costruisco la query per il gioco
    $userid=$_SESSION['user_id'];
    $gameid = mysqli_real_escape_string($conn, $_POST['id']);
    $cover = mysqli_real_escape_string($conn, $_POST['cover']);
    $releaseDate = mysqli_real_escape_string($conn, $_POST['releaseDate']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $genres = mysqli_real_escape_string($conn, $_POST['genres']);
    $platforms = mysqli_real_escape_string($conn, $_POST['platforms']);

    #query games
    $query = "SELECT * FROM games WHERE id = '$gameid'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    #controllo se c'è il gioco
    if(mysqli_num_rows($res)>0){
        # se c'è il gioco vedo se c'è il like
        $query= "SELECT * FROM likes WHERE idUser = '$userid' AND idGame = '$gameid'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if(mysqli_num_rows($res)>0){
            echo json_encode(array('ans' => "Hai già messo like a questo gioco"));
            exit;
        } else{
            // inserisco il like nel database
            $query="INSERT INTO likes VALUES('$userid','$gameid')";
            if(mysqli_query($conn, $query) or die(mysqli_error($conn))){
                echo json_encode(array('ans' => "Mi piace registrato"));
            }
            exit;
        }
    }else{
        // siamo nel caso in cui il gioco non c'è inserisco il gioco e poi il like
        $query="INSERT INTO games VALUES('$gameid', '$cover','$releaseDate','$name','$genres','$platforms')";
        if(mysqli_query($conn, $query) or die(mysqli_error($conn))){
            $query="INSERT INTO likes VALUES('$userid','$gameid')";
            if(mysqli_query($conn, $query) or die(mysqli_error($conn))){
            }
            echo json_encode(array('ans' => "Mi piace e gioco registrati"));
            exit;
        }
    }

    
    echo json_encode(array('ans' => "Qualcosa è andato storto"));