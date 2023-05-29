<?php
    require_once 'checkSession.php';
    
    if($_SESSION['user_id']!==checkSession()){
        header('Location: index_home.php');
        exit;
    }

    $userid=$_SESSION['user_id'];
        
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $query = "SELECT photo_path FROM users WHERE id = '$userid'";
    $res= mysqli_query($conn, $query) or die(mysqli_error($conn));
    $file=$_FILES['photo'];
    
    if(isset($file)){
        $res= mysqli_query($conn, $query) or die(mysqli_error($conn));
        while($row = mysqli_fetch_assoc($res)){
            $photo_path[] = $row;
        }
        unlink($photo_path[0]['photo_path']);
        if ($file['size'] !== 0) {
            $type = exif_imagetype($file['tmp_name']);
            $allowedExt = array(IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg', IMAGETYPE_GIF => 'gif');
            if (isset($allowedExt[$type])) {
                if ($file['error'] === 0) {
                    if ($file['size'] < 5242880) {
                        $fileNameNew = uniqid('photo-').".".$allowedExt[$type];
                        $fileDestination = 'image/'.$fileNameNew;
                        //verifica se il file che si invia dalla 
                        //prima alla seconda destinazione è un file valido
                        move_uploaded_file($file['tmp_name'], $fileDestination);
                        $query = "UPDATE users SET photo_path = '$fileDestination' WHERE id=$userid";
                        if (mysqli_query($conn, $query)) {
                            mysqli_close($conn);
                            echo json_encode(array('ans' => "Foto modificata"));
                            exit;
                        } else {
                            echo json_encode(array('ans'=> "Errore di connessione al Database"));
                        } 
                    } else {
                        echo json_encode(array('ans'=> "L'immagine non deve superare i 5 MB"));
                    }
                } else {
                    echo json_encode(array('ans'=> "Errore nel carimento del file"));
                }
            } else {
                echo json_encode(array('ans'=> "I formati consentiti sono .png, .jpeg, .jpg e .gif"));
            }
        }else{
            echo json_encode(array('ans'=> "Non hai caricato nessuna immagine"));
        }
    }
    mysqli_free_result($res);
    mysqli_close($conn);
?>