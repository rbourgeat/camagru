<?php

    session_start();
    require_once "config/class.php";

    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $pictures = new Pictures($session);
    $likes = new Likes($session);

    $imageName = $_FILES["upimage"]["tmp_name"];

    $image_tmp = (file_get_contents($_FILES["upimage"]["tmp_name"]));

    $pictures->uploadPicture($db, $auth->getUserID($db, $auth->actualUser()->username), $image_tmp);

    $likes->putLike($db, $db->lastInsertedId(), $auth->getUserID($db, $auth->actualUser()->username));
