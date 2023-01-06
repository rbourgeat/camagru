<?php

    session_start();
    require_once "config/class.php";
    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $picture = new Pictures($session);

    $actualUserPseudo = $auth->actualUser()->username;

    $id = (isset($_GET['id']) && is_numeric(htmlentities($_GET['id'], ENT_QUOTES))) ? intval(htmlentities($_GET['id'], ENT_QUOTES)) : 0;

    $pic = $picture->getPic($db, $id);

    foreach (getallheaders() as $name => $value) {
        header_remove($name);
    };
    ob_end_clean();

    header('Content-Type: image/png');

    echo $pic;

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
?>