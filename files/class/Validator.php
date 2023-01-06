<?php

class Validator {

    private $data;
    private $errors = [];

    public function __construct($data) {
        $this->data = $data;
    }

    private function getField($field) {
        if (!isset($this->data[$field]))
            return null;
        return htmlentities($this->data[$field], ENT_QUOTES);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function isValid() {
        return empty($this->errors);
    }

    public function usernameValidator($db, $table) {
        if(empty($this->getField("username")) || strlen($this->getField("username")) > 255 || strlen($this->getField("username")) < 3 || !preg_match('@[A-Za-z0-9_-]@', $this->getField("username")))
            $this->errors["username"] = "Votre nom d'utilisateur n'est pas valide : il doit faire plus que 3 caractères et n'utilisez que des caractères alphanumériques, underscore et tiret."; 
        else if ($db->query("SELECT id FROM $table WHERE username = ?", [$this->getField("username")])->fetch())
            $this->errors["username"] = "Ce pseudo est déjà pris."; 
    }

    public function emailValidator($db, $table) {
        if (empty($this->getField("email")) || strlen($this->getField("email")) > 255 || !filter_var($this->getField("email"), FILTER_VALIDATE_EMAIL))
            $this->errors["email"] = "Votre email n'est pas valide : vous devez utilisez la bonne synthaxe (exemple : exemple@exemple.fr)";
        else if ($db->query("SELECT id FROM $table WHERE email = ?", [$this->getField("email")])->fetch())
            $this->errors["email"] = "Cet email est déjà utilisé.";
    }

    public function passwordValidator() {
        $uppercase = preg_match('@[A-Z]@', $this->getField("password"));
        $lowercase = preg_match('@[a-z]@', $this->getField("password"));
        $number = preg_match('@[0-9]@', $this->getField("password"));
        if (!$uppercase || !$lowercase || !$number || strlen($this->data["password"]) > 255 || strlen($this->data["password"]) < 8)
            $this->errors["password"] = "Votre mot de passe n'est pas valide : il doit contenir 8 caractères minimum et au moins un nombre, une majuscule et une minuscule.";
        else if ($this->data["password"] !== $this->data["password_confirm"])
            $this->errors["password"] = "Vous devez confirmer correctement votre mot de passe.";
    }
}