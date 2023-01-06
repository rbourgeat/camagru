<?php

class Likes {

    private $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function putLike($db, $pictureID, $userID) {
        $db->query("INSERT INTO likes SET pictureID = ?, userID = ?", [$pictureID, $userID]);
    }

    public function removeLike($db, $pictureID, $userID) {
        $db->query("DELETE FROM likes WHERE pictureID = ? AND userID = ?", [$pictureID, $userID]);
    }

    public function countLikes($db, $pictureID) {
        $nbr = $db->query("SELECT userID FROM likes WHERE pictureID = $pictureID");
        return (count($nbr->fetchAll(PDO::FETCH_COLUMN, 0)));
    }

    public function checkLike($db, $pictureID, $userID) {
        $test = $db->fetcher("SELECT id FROM likes WHERE pictureID = ? AND userID = ?", [$pictureID, $userID]);
        return ($test ? true : false);
    }
}
