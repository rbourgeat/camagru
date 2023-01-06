<?php

    session_start();
    require_once "config/class.php";
    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $validator = new Validator($_POST);

    $auth->restrict();

    if ($auth->actualUser()) {
        $auth->connect($db->query("SELECT * FROM users WHERE id = {$auth->actualUser()->id}")->fetch());
    }
    $user = $auth->actualUser();

    if (!empty($_POST["password"])) {
        if (htmlentities($_POST["password"], ENT_QUOTES) !== htmlentities($_POST["password_confirm"], ENT_QUOTES)) {
            $session->setFlash("danger", "Le mot de passe a mal été validé.");
            App::redirect("account.php");
        } 

        else if (password_verify(htmlentities($_POST["password"], ENT_QUOTES), $user->password)) {
            $session->setFlash("danger", "Votre nouveau mot de passe doit être différent de l'ancien.");
        }

        else if (!password_verify(htmlentities($_POST["password"], ENT_QUOTES), $user->password)) {
            $validator->passwordValidator();
            if($validator->isValid()) {
                $password = password_hash(htmlentities($_POST["password"], ENT_QUOTES), PASSWORD_BCRYPT);
                $db->query("UPDATE users SET password = ? WHERE id = $user->id", [$password]);
                $session->setFlash("success", "Votre mot de passe a bien été mis à jour.");
                App::redirect("account.php");
            }
            else {
                $session->setFlash("danger", "Vous devez rentrer un mot de passe valide : il doit contenir 8 caractères minimum, un nombre, une majuscule et une minuscule.");
                App::redirect("account.php");
            }
        }
    }

    if (!empty($_POST["username"])) {
        if (htmlentities($_POST["username"], ENT_QUOTES) !== $user->username) {
            $validator->usernameValidator($db, "users");
            if($validator->getErrors()["username"] === "Ce pseudo est déjà pris.") {
                $session->setFlash("danger", "Ce nom d'utilisateur est déjà utilisé, merci d'en choisir un autre.");
                App::redirect("account.php");
            }
            else if ($validator->getErrors()["username"] === "Votre nom d'utilisateur n'est pas valide : il doit faire plus que 3 caractères et n'utilisez que des caractères alphanumériques, underscore et tiret.") {
                $session->setFlash("danger", "Attention, votre nom d'utilisateur n'est pas valide : il doit faire plus que 3 caractères et n'utilisez que des caractères alphanumériques, underscore et tiret.");
                App::redirect("account.php");
            }
            else {
                $db->query("UPDATE users SET username = ? WHERE id = $user->id", [htmlentities($_POST["username"], ENT_QUOTES)]);
                $session->setFlash("success", "Votre nom d'utilisateur a bien été mis à jour.");
                App::redirect("account.php");
            }
        }
        else {
            $session->setFlash("danger", "Attention, votre nouveau nom d'utilisateur doit être différent de l'ancien.");
            App::redirect("account.php");
        }
    }

    if (!empty($_POST["email"])) {
        if (htmlentities($_POST["email"], ENT_QUOTES) !== $user->email) {
            $validator->emailValidator($db, "users");
            if ($validator->isValid()) {
                $used = $db->query("SELECT id FROM users WHERE email = ?", [htmlentities($_POST["email"], ENT_QUOTES)])->fetch();
                if ($used) {
                    $session->setFlash("danger", "Cette adresse mail est déjà utilisée, veuillez en prendre une autre.");
                    App::redirect("account.php");
                }
                else {
                    $db->query("UPDATE users SET email = ? WHERE id = $user->id", [htmlentities($_POST["email"], ENT_QUOTES)]);
                    $session->setFlash("success", "Votre adresse mail a bien été mise à jour.");
                    App::redirect("account.php");
                }
            }
            else {
                $session->setFlash("danger", "Votre nouvel email n'est pas valide - utilisez une synthaxe correcte.");
                App::redirect("account.php");
            }
        }
        else {
            $session->setFlash("danger", "Votre nouvelle adresse email doit être différente de l'ancienne.");
            App::redirect("account.php");
        }
    }

    if (!empty($_POST["comment_confirm"])) {
        $value = $_POST["comment_confirm"] == "yes" ? 1 : 0;
        $auth->changeCommentToken($db, $auth->actualUser()->username, $value);
    }
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>42gram - rbourgeat</title>
    </head>
    <?php require "elements/header.php" ?>

        <h2 class="page-title">Hello <?= htmlentities($user->username, ENT_QUOTES);?> ✋</h2>

        <div class="content">
            <div class="form-div">
                <form action="" method="post" class="account-form">
                    <div>
                        <input type="text" name="username" placeholder="✏️ Nouveau pseudo" id="pseudo-1">
                    </div>
                    <button type="submit">Changer de pseudo</button>
                </form>
            </div>

            <div class="form-div">
                <form action="" method="post" class="account-form">
                    <div>
                        <input type="text" name="email" placeholder="✏️ mon@email.com" id="email-1">
                    </div>
                    <button type="submit">Changer d'adresse email</button>
                </form>
            </div>
            
            <div class="form-div">
                <form action="" method="post" class="account-form">
                    <div>
                        <input type="password" name="password" placeholder="✏️ Nouveau mot de passe" id="password-1">
                    </div>
                    <div>
                        <input type="password" name="password_confirm" placeholder="✏️ Confirmation du nouveau mot de passe" id="password-2">
                    </div>
                    <button type="submit">Changer de mot de passe</button>
                </form>
            </div>

            <div class="form-div">
                <form action="" method="post" class="account-form">
                    <p>Recevoir un mail à chaque commentaire ? (actuellement 
                        <?php if($auth->checkCommentToken($db, $auth->getUserID($db, $auth->actualUser()->username))) {
                            echo("oui)");
                        }
                        else {
                            echo("non)");
                        };
                        ?>
                    
                    <input type="radio" name="comment_confirm" value="no" id="no"><label for="no">Non</label><br />
                    <input type="radio" name="comment_confirm" value="yes" id="yes" ><label for="yes">Oui</label><br />
                    </p>
                    <button type="submit">Confirmer</button>
                </form>
            </div>
        </div>

    <?php require "elements/footer.php" ?>
</html>
