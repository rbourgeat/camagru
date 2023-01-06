<?php

    session_start();
    require_once "config/class.php";
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>42gram - rbourgeat</title>
    </head>
    <body>
        <?php require_once 'config/setup.php'; ?>
        <?php require_once 'elements/header.php'; ?>
            <div class="content" id="index-page">
                <img src="/42gram.png" alt="" id="logo">
            </div>
        <?php require_once 'elements/footer.php' ?>
    </body>
</html>
