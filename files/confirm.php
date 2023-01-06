<?php

    session_start();
    require_once "config/class.php";

    $db = App::getDatabase();

    if (App::getAuth()->confirm($db, htmlentities($_GET["id"], ENT_QUOTES), htmlentities($_GET["token"], ENT_QUOTES), Session::getInstance())) {
        Session::getInstance()->setFlash("success", "Votre compte a bien été validé.");
        App::redirect("account.php");
    } 
    
    else {
        Session::getInstance()->setFlash("danger", "Attention, cette clé n'est plus valide.");
        App::redirect("login.php");
    }
?>