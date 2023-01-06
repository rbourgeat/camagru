<?php

    require_once 'database.php';

    try {
        $db_construct = new PDO("mysql:host=$DB_HOST;port=3306", $DB_USER, $DB_PASSWORD);
    }
    catch (PDOException $e) {
        echo "Erreur construct : " . $e->getMessage();
        die();
    }

    $db_construct->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_construct->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    $db_construct->prepare("CREATE DATABASE IF NOT EXISTS $DB_NAME DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci")->execute();

    try {
        $db_tables = new PDO("$DB_DSN;port=3306", $DB_USER, $DB_PASSWORD);
    }
    catch (PDOException $e) {
        echo "Erreur tables : " . $e->getMessage();
        die();
    }

    $db_tables->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db_tables->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    $db_tables->prepare("CREATE TABLE IF NOT EXISTS `users` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `confirmation_token` VARCHAR (60) NULL,
        `confirmed_at` DATETIME NULL,
        `reset_token` VARCHAR (60) NULL,
        `reseted_at` DATETIME NULL,
        `remember_token` VARCHAR (255) NULL,
        `send_mail_comment` TINYINT(1) NOT NULL,
        PRIMARY KEY (`id`)
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();

    $db_tables->prepare("CREATE TABLE IF NOT EXISTS `pictures` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(255) NOT NULL,
        `mime`VARCHAR (255) NOT NULL,
        `picture` MEDIUMBLOB NOT NULL,
        `author` VARCHAR(255) NOT NULL,
        `date` DATE NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();

    $db_tables->prepare("CREATE TABLE IF NOT EXISTS `comments` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pictureID` int(11) NOT NULL,
        `author` VARCHAR(255) NOT NULL,
        `comment` VARCHAR(255) NOT NULL,
        `date` DATE NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();

    $db_tables->prepare("CREATE TABLE IF NOT EXISTS `likes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `pictureID` int(11) NOT NULL,
        `userID` int(11) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8")->execute();
?>