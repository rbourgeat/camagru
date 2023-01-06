<?php

    spl_autoload_register("app_autoloader");

    function app_autoloader($class) {
        require "class/$class.php";
    }
