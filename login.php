<?php 
    require_once 'checkSession.php';

    if(checkSession()){
        header("Location: user_home.php");
        exit;
    }
    if (!empty($_POST["username"]) && !empty($_POST["password"]) )
    {
        // Se username e password sono stati inviati
        // Connessione al DB
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        // ID e Username per sessione, password per controllo
        $query = "SELECT * FROM users WHERE username = '$username'";
        // Esecuzione
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));;
        
        if (mysqli_num_rows($res) > 0) {
            // Ritorna una sola riga, il che ci basta perché l'utente autenticato è solo uno
            $entry = mysqli_fetch_assoc($res);
            if (password_verify($_POST['password'], $entry['password'])) {

                // Imposto una sessione dell'utente
                $_SESSION["username"] = $entry['username'];
                $_SESSION["user_id"] = $entry['id'];
                header("Location: user_home.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        // Se l'utente non è stato trovato o la password non ha passato la verifica
        $error = "Username e/o password errati.";
    }
    else if (isset($_POST["username"]) || isset($_POST["password"])) {
        // Se solo uno dei due è impostato
        $error = "Inserisci username e password.";
    }
    
?>

<html>
    <head>
        <title> Login </title>
        <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display&display=swap" rel="stylesheet">   
        <link rel="stylesheet" href="hw1.css"/>     
        <link rel="stylesheet" href="login.css"/>
        <meta charset="utf-8">
        <script src="login.js" defer="true"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="image/favicon.png">
    </head>
    <body>
        <div id="overlay"></div>
        <nav>
            <img src="image/logo_white.png" id="logo">
            <a href='index_home.php'> Indietro</a>   
        </nav>
        <section>        
            <h1>Per continuare, accedi a MyGameVault.</h1>
            <form name="login_form" method="POST">
                <?php
                    // Verifica la presenza di errori
                    if (isset($error)) {
                        echo "<p class='error'>$error</p>";
                    }                
                ?>
                <div class="form_elem" data-elem="username">
                    <label for ="username">Username</label> 
                    <input type='text' name='username' class='input' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>>
                    <div> <img src=image/close.svg> <span>Inserisci il nome utente</span></div>
                </div>

                <div class="form_elem" data-elem="password">
                    <label for="password">Password</label>
                    <input type='password' name='password' class='input' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                    <div><img src=image/close.svg> <span> Inserisci la password</span></div>
                </div>
                <input type='submit' value="Accedi" id="login_button">
            </form>
            <div id=sign_up>
                <span>Non hai un account?</span>
                <a href="sign_up.php"> ISCRIVITI A MYGAMEVAULT</a>
            </div>
        </section>
        <footer>
            <span>Creato da Alessandro Aveni - Matricola: O46002043</span>
            <span>Corso di Web Programming 2023</span>
        </footer>
    </body>
</html>