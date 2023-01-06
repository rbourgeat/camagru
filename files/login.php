<?php

    session_start();
    require_once "config/class.php";

    $db = App::getDatabase();

    $auth = App::getAuth();
    $auth->connectFromCookie($db);

    

    if ($auth->actualUser()) {
        App::redirect("account.php");
    }

    if(!empty($_POST["username"]) && !empty($_POST["password"])) {
        $session = Session::getInstance();
        $user = $auth->login($db, htmlentities($_POST["username"], ENT_QUOTES), htmlentities($_POST["password"], ENT_QUOTES), isset($_POST["remember"]));
        if ($user) {
            $session->setFlash("success", "Vous êtes bien connecté !");
            App::redirect("account.php");
        }
        else {
            $session->setFlash("danger", "Identifiant ou mot de passe incorrect....");
            App::redirect("login.php");
        }
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
                <h1 class="page-title">Connexion</h1>

                <?php if (!empty($errors)): ?>
                    <div class="alert">
                    <?php foreach($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach ?>   
                    </div>
                <?php endif ?>
                <div class="form-div">
                    <form action="" method="POST" id="login-form">
                        <div class="form-group">
                            <label for="">Nom d'utilisateur ou adresse email</label>
                            <input type="text" name="username" />
                        </div>

                        <div class="form-group">
                            <label for="">Mot de passe <a class="forgotten_password" href="forget.php">(oublié ?)</a></label>
                            <input type="password" name="password" />
                        </div>

                    <div id="checkbox-remember">
                        <label>
                            <input type="checkbox" name="remember" value="1" id="" /> Se souvenir de moi
                        </label>
                    </div>

                        <button type="submit">Login</button>

                    </form>
                </div>
            </div>
        <?php require_once 'elements/footer.php'?>
    </body>
</html>