<?php

    session_start();
    require_once "config/class.php";
    $session = Session::getInstance();
    $auth = App::getAuth();
    $db = App::getDatabase();
    $picture = new Pictures($session);

    $auth->restrict("restriction_msg_assembly");

    $actualUserPseudo = $auth->getUserID($db, $auth->actualUser()->username);

    $userIDs = $picture->getUserPicsID($db, $actualUserPseudo);
?>

<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/content.css" />
        <title>42gram - rbourgeat</title>
    </head>

    <body>
        <?php require_once 'elements/header.php'; ?>
            <section id="assemblyUp">
                <aside id="filterContainer">
                    <h5>Filtres</h5>
                    <div id="filters">
                        <input type="image" onClick="applyFilter('0')" class="filterClass" id="filter0" alt="Filter0" src="/filters/Filter0.png">
                        <input type="image" onClick="applyFilter('1')" class="filterClass" id="filter1" alt="Filter1" src="/filters/Filter1.png">
                        <input type="image" onClick="applyFilter('2')" class="filterClass" id="filter2" alt="Filter2" src="/filters/Filter2.png">
                        <input type="image" onClick="applyFilter('3')" class="filterClass" id="filter3" alt="Filter3" src="/filters/Filter3.png">
                        <input type="image" onClick="applyFilter('4')" class="filterClass" id="filter4" alt="Filter4" src="/filters/Filter4.png">
                        <input type="image" onClick="applyFilter('5')" class="filterClass" id="filter5" alt="Filter5" src="/filters/Filter5.png">
                        <input type="image" onClick="applyFilter('6')" class="filterClass" id="filter6" alt="Filter6" src="/filters/Filter6.png">
                    </div>
                </aside>
                <div id="picContainer">
                    <img id="outputImage"/>
                    <video autoplay="true" id="videoFeed"></video>
                    <img id="positionnedFilter"/>
                    <div id="webcamButton">
                        <input id="picUp" type="button" value="📸"/>
                    </div>
                    <input id="insertImageButton" name="userImage" type="file" accept="image/*" onchange="preview_image(event)">
                </div>
                <aside id="picResultZone">
                    <h5>Résultats</h5>
                    <div id="picResults">
                        <?php
                        for ($i = 0; $i < count($userIDs); $i++) {
                            echo ('<button class="deleteImageButton" onclick="deleteImage(' . $userIDs[count($userIDs) - 1 - $i] . ')">Supprimer</button>
                                    <img class="assemblyResultImage" src="image.php?id=' . $userIDs[count($userIDs) - 1 - $i] . '"/>');
                        };
                        ?>
                    </div>
                </aside>
            </section>
            
        <?php require_once 'elements/footer.php'?>
        <script src="photoEditor.js"></script>
        <script src="webCam.js"></script>
    </body>
</html>
