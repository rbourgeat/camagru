<?php

class Session {

    static $instance;

    public function __construct(){
        session_start();
    }

    static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function setFlash($key, $message) {
        $_SESSION["flash"][$key] = $message;
    }

    public function hasFlashes() {
        return isset($_SESSION["flash"]);
    }

    public function getFlashes() {
        $flash = $_SESSION["flash"];
        unset($_SESSION["flash"]);
        return $flash;
    }

    public function write($key, $data) {
        $_SESSION[$key] = $data;
    }
    
    public function delete($key) {
        unset($_SESSION[$key]);
    }

    public function read($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function resetFlashes() {
        if ($this->hasFlashes()) {
            $_SESSION["flash"] = null;
        }
    }
}
