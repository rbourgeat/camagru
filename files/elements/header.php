<?php 
$user = App::getAuth(Session::getInstance())->actualUser();
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/css/header.css" />
    </head>
    <header>
        <div class="header-side">
            <a href="/gallery.php?page=1" class="header-box">Galerie</a>
            <?php if ($user): ?>
                <a href="/selfie.php" class="header-box">Selfie</a>
            <?php endif ?>
        </div>
        <h1><a href="/index.php" id="website-title">42gram</a></h1>
        <div class="header-side">
            <?php if ($user): ?>
                <a href="/account.php" class="header-box">Compte</a>
                <a href="/logout.php" class="header-box">Déconnexion</a>
            <?php else: ?>    
                <a href="/register.php" class="header-box">S'inscrire</a>
                <a href="/login.php" class="header-box">Se connecter</a>
            <?php endif ?>
        </div>
    </header>

    <?php if(Session::getInstance()->hasFlashes()): ?>
        <?php foreach(Session::getInstance()->getFlashes() as $type => $message): ?>
            <div class="session-alert">
                <?= $message; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
        
</html>
