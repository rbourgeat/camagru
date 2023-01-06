<?php

    session_start();
    require_once "config/class.php";


    if(!empty($_POST)){
        $errors = array();

        $db = App::getDatabase();

        $validator = new Validator($_POST);
        $validator->usernameValidator($db, "users");
        $validator->emailValidator($db, "users");
        $validator->passwordValidator();

        if ($validator->isValid()) {
            App::getAuth()->register($db, htmlentities($_POST["username"], ENT_QUOTES), htmlentities($_POST["email"], ENT_QUOTES), htmlentities($_POST["password"], ENT_QUOTES));
            Session::getInstance()->setFlash("success", "Un email vous a été envoyé pour valider votre compte.");
            App::redirect("index.php");
        }
        else {
            $errors = $validator->getErrors();
        }

    }

?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>42gram - rbourgeat</title>
    </head>
    <body>
        <?php require_once 'elements/header.php'; ?>
        <div class="content">
            <h1 class="page-title">Inscription</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert">
                <p>Certains champs ne sont pas conformes : </p>
                <?php foreach($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach ?>   
                </div>
            <?php endif ?>

            <div class = "form-div">
                <form action="" method="POST" id="register-form">
                    <div class="form-group">
                        <label for="">Nom d'utilisateur</label>
                        <input type="text" name="username" />
                    </div>

                    <div class="form-group">
                        <label for="">Adresse mail</label>
                        <input type="email" name="email" />
                    </div>

                    <div class="form-group">
                        <label for="">Mot de passe</label>
                        <input type="password" name="password" />
                    </div>

                    <div class="form-group">
                        <label for="">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirm" />
                    </div>

                    <button type="submit">Inscription</button>

                </form>
            </div>
        </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>
