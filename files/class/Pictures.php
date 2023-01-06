<?php

class Pictures {

    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function uploadPicture($db, $username, $picture) {
        $date = date('Y-m-d');
        $name = $username . strval(time());
        $db->query("INSERT INTO pictures SET name = ?, mime = ?, picture = ?, author = ?, date = ?", [
            $name,
            "image/png",
            $picture, 
            $username,
            $date]);
    }

    public function getPic($db, $id) {
        $pic = $db->fetcher("SELECT picture FROM pictures WHERE id = ?", [$id]);
        return $pic;
    }

    public function getUserPicsID($db, $username) {
        $ids = $db->query("SELECT id FROM pictures WHERE author = ?", [
            $username
        ]);
        return ($ids->fetchAll(PDO::FETCH_COLUMN, 0));
    }

    public function getPicsIDs($db) {
        $ids = $db->query("SELECT id FROM pictures");
        return ($ids->fetchAll(PDO::FETCH_COLUMN, 0));
    }

    public function deleteImage($db, $id) {
        $db->query("DELETE FROM pictures WHERE pictures . id = ?", [$id]);
    }

    public function getAuthor($db, $id) {
        $auth = $db->query("SELECT author FROM pictures WHERE id = ?", [
            $id
        ]);
        $auth2 = $db->query("SELECT username FROM users WHERE id = ?", [
            $auth->fetch(PDO::FETCH_COLUMN, 0)
        ]);
        return ($auth2->fetch(PDO::FETCH_COLUMN, 0));
    }
}
