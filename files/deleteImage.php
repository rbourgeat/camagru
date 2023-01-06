<?php

    session_start();
    require_once "config/class.php";
    $session = Session::getInstance();
    $db = App::getDatabase();
    $picture = new Pictures($session);
    $auth = App::getAuth();

    $auth->restrict();

    $id =  htmlentities($_GET["id"], ENT_QUOTES);

    $picture->deleteImage($db, $id);
?>