<?php 
    /*******************************************************
        Controlla che l'username sia unico
    ********************************************************/
    require_once 'checkSession.php';

    if (!isset($_GET["q"])) {
        if(!checkSession()){
            header("Location: index_home.php");        
            exit;
        }
    }

    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $username = mysqli_real_escape_string($conn, $_GET["q"]);

    $query = "SELECT username FROM users WHERE username = '$username'";

    $res = mysqli_query($conn, $query) or die("Errore: ".mysqli_error($conn));

    echo json_encode(array('exists' => mysqli_num_rows($res) > 0 ? true : false));

    mysqli_free_result($res);
    mysqli_close($conn);
?>