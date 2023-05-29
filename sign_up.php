<?php 
    require_once 'checkSession.php';

    if(checkSession()){
        header("Location: user_home.php");
        exit;
    }


    // Verifica esistenza dati POST
    if (!empty($_POST["name"]) && !empty($_POST["surname"]) && !empty($_POST["username"]) && !empty($_POST["password"]) && 
        !empty($_POST["confirm_password"]) && !empty($_POST["email_address"]) && !empty($_POST["date_birth"]))
        {
            $error = array();
            $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

            //Controllo dati POST
            #NOME
            if(strlen($_POST['name'])<=0){
                $error[]="Inserisci il nome";
            }
            #COGNOME
            if(strlen($_POST['surname'])<=0){
                $error[]="Inserisci il cognome";
            }
            #USERNAME
            if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])){
                $error[]= "Username non valido";
            }else{
                $username=mysqli_real_escape_string($conn, $_POST['username']);
                $query="SELECT username FROM users WHERE username= '".$username."'";
                $res=mysqli_query($conn,$query);
                if(mysqli_num_rows($res)>0){
                    $error[]="Username già in uso";
                }
            }

            #PASSWORD
            if(strlen($_POST['password'])<8){
                $error[]="Password troppo corta. Almeno 8 caratteri";
            }

            #COMFERMA_PASSWORD
            if(strcmp($_POST['password'], $_POST['confirm_password'])!==0){
                $error[]="Le password non coincidono";
            }
            #EMAIL
            if(!preg_match('/^[A-z0-9\.\+_-]+@[A-z0-9\._-]+\.[A-z]{2,6}$/', $_POST['email_address'])){
                $error[]= "Username non valido";
            }else{
                $email=mysqli_real_escape_string($conn, $_POST['email_address']);
                $query="SELECT email_address FROM users WHERE email_address= '$email'";
                $res=mysqli_query($conn,$query);
                if(mysqli_num_rows($res)>0){
                    $error[]="Indirizzo email già in uso";
                }
            }
            #DATA
            if(date_create($_POST['date_birth'])> date_create(date('Y-m-d'))){
                $error[]="La data è successiva a quella odierna";
            }else if(date_diff(date_create($_POST['date_birth']), date_create(date('Y-m-d')))->y <12) {
                $error[]= "Devi avere almeno 12 anni";
            }       

            #UPLOAD IMMAGINE PROFILO
            if (count($error) === 0) { 
                $file = $_FILES['photo'];
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
                            } else {
                                $error[] = "L'immagine non deve superare i 5 MB";
                            }
                        } else {
                            $error[] = "Errore nel carimento del file";
                        }
                    } else {
                        $error[] = "I formati consentiti sono .png, .jpeg, .jpg e .gif";
                    }
                }else{
                    echo "Non hai caricato nessuna immagine";
                }
            }
            #REGISTRAZIONE AL DATABASE
            if (count($error) == 0) {
                $name = mysqli_real_escape_string($conn, $_POST['name']);
                $surname = mysqli_real_escape_string($conn, $_POST['surname']);
                $date_birth= mysqli_real_escape_string($conn, $_POST['date_birth']);
                $sign_up_date=mysqli_real_escape_string($conn, $_POST['sign_up_date']);
    
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                $password = password_hash($password, PASSWORD_BCRYPT);
    
                $query = "INSERT INTO users(name, surname, username, password, email_address, date_birth, sign_up_date, photo_path) 
                VALUES('$name', '$surname', '$username', '$password', '$email', '$date_birth', '$sign_up_date' ,'$fileDestination')";
                
                if (mysqli_query($conn, $query)) {
                    $_SESSION["username"] = $_POST["username"];
                    $_SESSION["user_id"] = mysqli_insert_id($conn);
                    mysqli_close($conn);
                    header("Location: user_home.php");
                    exit;
                } else {
                    $error[] = "Errore di connessione al Database";
                }
            }
            mysqli_free_result($res);
            mysqli_close($conn);
        } 
        else if (isset($_POST["username"])) {
           $error = array("Riempi tutti i campi");
        }
?>

<html>
    <head>
        <title> Iscriviti </title>
        <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="hw1.css"/>       
        <link rel="stylesheet" href="sign_up.css"/>
        <meta charset="utf-8">
        <script src="sign_up.js" defer="true"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="image/favicon.png">
    </head>
    <body>
        <div id="overlay"></div>
        <nav>
            <img src="image/logo_white.png" id="logo">
            <div>
                <a href='index_home.php'> Indietro</a>
            </div>
        </nav>
        <section> 
            <h1>Iscriviti per rimanere aggiornato sulle nuove uscite</h1>
            <form name="sign_in_form" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="form_elem" data-elem="name">
                    <label for ="name">Nome</label> 
                    <input type='text' name='name' class='input' <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];} ?>>
                    <div><img src=image/close.svg> <span> Inserire il nome</span></div>
                </div>

                <div class="form_elem" data-elem="surname">
                    <label for ="surname">Cognome</label> 
                    <input type='text' name='surname' class='input' <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];} ?>>
                    <div><img src=image/close.svg> <span> Inserire il cognome</span></div>
                </div>

                <div class="form_elem" data-elem="username" >
                    <label for ="username">Username</label> 
                    <input type='text' name='username' class='input' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>>
                    <div> <img src=image/close.svg> <span></span></div> <!-- Lo span si riempie in base all'errore-->
                </div>

                <div class="form_elem" data-elem="password" >
                    <label for="password">Password</label>
                    <input type='password' name='password' class='input' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                    <div><img src=image/close.svg> <span> Deve avere almeno 8 caratteri</span></div>
                </div>

                <div class="form_elem" data-elem="confpass">
                    <label for="confirm_password">Conferma Password</label>
                    <input type='password' name='confirm_password' class='input' <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?>>
                    <div><img src=image/close.svg> <span> Le password non coincidono</span></div>
                </div>

                <div class="form_elem" data-elem="email">
                    <label for="email_address">E-mail</label>
                    <input type='text' name='email_address' class='input' <?php if(isset($_POST["email_address"])){echo "value=".$_POST["email_address"];} ?>>
                    <div><img src=image/close.svg> <span></span></div> <!-- Lo span si riempie in base all'errore-->
                </div>

                <div class="form_elem" data-elem="date">
                    <label for="date_birth">Data di Nascita</label>
                    <input type='date' name='date_birth' class='input' <?php if(isset($_POST["date_birth"])){echo "value=".$_POST["date_birth"];} ?>>
                    <div><img src=image/close.svg> <span> </span></div> <!-- Lo span si riempie in base all'errore-->
                </div>

                <div class=form_elem data-elem="photo">
                    <label for='photo'>Immagine del profilo</label>
                    <input type='file' name='photo' accept='.jpg, .jpeg, image/gif, image/png'>
                    <div id="chooseContainer"><button data-elem="chooseButton">Scegli foto</button><em id=file_name>Seleziona un file...</em></div>
                    <div><img src=image/close.svg> <span></span></div> <!-- Lo span si riempie in base all'errore-->
                </div>
                <input type="hidden" name="sign_up_date" value=<?php echo date("Y-m-d")?>>
                <?php if(isset($error)) {
                foreach($error as $err) {
                    echo "<div class='error'><img src='image/close.svg'/><span>".$err."</span></div>";
                    }
                } 
                ?>
                <input type='submit' value="Registrati" id="sign_in_button">
                <label>Hai un account? <a href="login.php">Accedi</a></label>
        
            </form>
        </section>
        <footer>
            <span>Creato da Alessandro Aveni - Matricola: O46002043</span>
            <span>Corso di Web Programming 2023</span>
        </footer>
    </body>
</html>