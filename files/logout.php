<?php

    session_start();
    require_once "config/class.php";
    App::getAuth()->logout();
    Session::getInstance()->setFlash("success", "Vous êtes bien déconnecté.");
    App::redirect("index.php");
