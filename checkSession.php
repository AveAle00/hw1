<?php
require_once 'dbconfig.php';

session_start();

function checkSession(){
    if(isset($_SESSION['user_id'])){
        return $_SESSION['user_id'];
    } else return false;
}
?>