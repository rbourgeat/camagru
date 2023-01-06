<?php

    session_start();
    require_once "config/class.php";

    if (isset($_GET["id"]) && isset($_GET["token"])) {
        $db = App::getDatabase();
        $auth = App::getAuth();
        $session = Session::getInstance();
        $user = $auth->checkResetToken($db, htmlentities($_GET["id"], ENT_QUOTES), htmlentities($_GET["token"], ENT_QUOTES));
        if ($user) {
            if (isset($_POST["password"])) {
                $validator = new Validator($_POST);
                $validator->passwordValidator(htmlentities($_POST["password"], ENT_QUOTES));
                if ($validator->isValid() && !password_verify(htmlentities($_POST["password"], ENT_QUOTES), $user->password)) {
                    $password = password_hash(htmlentities($_POST["password"], ENT_QUOTES), PASSWORD_BCRYPT);
                    $db->query("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?", [$password, $user->id]);
                    $session->setFlash("success", "Votre mot de passe a bien été modifié.");
                    $auth->connect($user);
                    App::redirect("account.php");
                }
                else if (password_verify(htmlentities($_POST["password"], ENT_QUOTES), $user->password)) {
                    $session->setFlash("danger", "Le mot de passe doit être différent de l'ancien");
                }
                else {
                    $session->setFlash("danger", implode("/r", $validator->getErrors()));
                }
            }
        }
        else {
            $session->setFlash("danger", "Désolé, vous n'êtes pas autorisé à visiter cette page.");
            App::redirect("login.php");
        }
    }
    else {
        App::redirect("index.php");
    }
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
    </head>
    <body>
        <?php require_once 'elements/header.php'; ?>
            <div class="content">
                <h1 class="page-title">Réinitialisation du mot de passe</h1>
                <div class="form-div">
                    <form action="" method="POST" class="account-form">
                        <div class="form-group">
                            <label for="">Nouveau mot de passe</label>
                            <input type="password" name="password" />
                        </div>
                        <div class="form-group">
                            <label for="">Confirmation du nouveau mot de passe</label>
                            <input type="password" name="password_confirm" />
                        </div>

                        <button type="submit">Confirmer</button>
                    </form>
                </div>
            </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>