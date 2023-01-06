<?php

class Auth {

    private $session;
    private $options = [ "restriction_msg" => "test" ];

    public function __construct($session, $options = []) {
        $this->options = array_merge($this->options, $options);
        $this->session = $session;
    }

    public function register($db, $username, $email, $password) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $token = Str::random_str(60);
        $db->query("INSERT INTO users SET username = ?, email = ?, password = ?, confirmation_token = ?, send_mail_comment = ?", [
            $username, 
            $email, 
            $password,
            $token, 
            1]);
        $user_id = $db->lastInsertedId();
        mail($email, "Confirmation de votre inscription", "Bonjour et merci de vous être inscrit !\r\nPour confirmer votre inscription, veuillez cliquer sur le lien :\r\nhttp://localhost/confirm.php?id=$user_id&token=$token", "From: 42gram");
    }

    public function confirm($db, $user_id, $token) {

        $user = $db->query("SELECT * FROM users WHERE id = ?", [$user_id])->fetch();
        if ($user && $user->confirmation_token === $token) {
            $db->query("UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?", [$user_id]);
            $this->session->write("auth", $user);
            return true;
        } 
        return false;
    }

    public function restrict($option = false) {

        if(!$this->session->read("auth")) {
            $option ? $this->session->setFlash("danger" , $this->options[$option]) : $this->session->setFlash("danger" , $this->options["restriction_msg"]);
            App::redirect("index.php");
        }

    }

    public function isSomeoneHere() {
        return ($this->session->read("auth") ? TRUE : FALSE);
    }

    public function getUserID($db, $username) {
        $id = $db->query("SELECT id FROM users WHERE username = ?", [
            $username
        ]);
        return ($id->fetch(PDO::FETCH_COLUMN, 0));
    }

    public function getUserEmail($db, $username) {
        $id = $db->query("SELECT email FROM users WHERE username = ?", [
            $username
        ]);
        return ($id->fetch(PDO::FETCH_COLUMN, 0));
    }

    public function actualUser() {
        return !$this->session->read("auth") ? false : $this->session->read("auth");
    }

    public function connect($user) {
        $this->session->write("auth", $user);
    }

    public function disconnect($user) {
        $this->session->delete($user);
    }

    public function connectFromCookie($db) {
        if(isset($_COOKIE["remember"]) && !$this->actualUser()) {
            $parts = explode("//", $_COOKIE["remember"]);
            $user_id = $parts[0];
            $user = $db->query("SELECT * FROM users WHERE id = ?", [$user_id])->fetch();
            if ($user) {
                if ($_COOKIE["remember"] === $user_id . "//" . $user->remember_token) {
                    $this->connect($user);
                    setcookie("remember", $_COOKIE["remember"], time() + 60 * 60 * 24 * 7);
                    $this->session->resetFlashes();
                    App::redirect("account.php");
                }
                setcookie("remember", null, time() - 1);
            }
        }
        setcookie("remember", null, time() - 1);
    }

    public function login ($db, $username, $password, $remember = false) {
        $user = $db->query("SELECT * FROM users WHERE username = :username AND confirmed_at IS NOT NULL OR email = :username AND confirmed_at IS NOT NULL", ["username" => $username])->fetch();
        if($user && password_verify($password, $user->password)) {
            $this->connect($user);
            if ($remember) {
                $remember_token = Str::random_str(200);
                $db->query("UPDATE users SET remember_token = ? WHERE id = ?", [$remember_token, $user->id]);
                setcookie("remember", $user->id . "//" . $remember_token, time() + 60 * 60 * 24 * 7);
            }
            return $user;
        }
        return false;
    }

    public function logout() {
        setcookie("remember", NULL, time() - 1);
        $this->disconnect("auth");
    }

    public function rebootPassword($db, $email) {

        $user = $db->query("SELECT * FROM users WHERE email = :email AND confirmed_at IS NOT NULL", ["email" => $email])->fetch();
        if($user) {
            $reset_token = Str::random_str(60);
            $db->query("UPDATE users SET reset_token = ?, reseted_at = NOW() WHERE id = ?", [$reset_token, $user->id]);
            mail($email, "42gram - redéfinition de votre mot de passe", "Bonjour, vous avez demandé à changer votre mot de passe.\r\nPour confirmer votre nouveau mot de passe, veuillez cliquer sur le lien :\r\nhttp://localhost/reset.php?id={$user->id}&token=$reset_token", "From: 42gram");
            return $user;
        }
        return false;
    }

    public function checkResetToken($db, $user_id, $token) {
        return $db->query("SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reseted_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)", [$user_id, $token])->fetch();
    }

    public function checkCommentToken($db, $user_id) {
        return (($db->query("SELECT send_mail_comment FROM users WHERE id = ?", [$user_id])->fetch(PDO::FETCH_COLUMN, 0)) == 1 ? true : false);
    }

    public function changeCommentToken($db, $username, $value) {
        $db->query("UPDATE `users` SET `send_mail_comment` = ? WHERE `username` = ?", [$value, $username]);
    }

}
