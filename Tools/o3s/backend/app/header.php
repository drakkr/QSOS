<?php
    require("conf.php");
    session_start();
    
    /*
     *  Display user's name
     */
    if (!isset($_SESSION['login'])) { 
        $login = '<a href="connect.php">Connexion</a>';
        $logout = '';
    }else{
        $login = '<a href="profil.php">' . htmlentities(trim($_SESSION['login'])) . '</a>';
        $logout = '<li id="logout"><a href="disconnect.php">(Déconnexion)</a></li>';
    }
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>QSOS Backend</title>
        <meta charset="utf-8" />
        <script src="lib/jquery.js"></script>
        
        <!--Twitter BootStrap-->
        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.css" type="text/css" />
        <script src="lib/bootstrap/js/bootstrap.js"></script>
        
        <link rel="stylesheet" href="./style.css" type="text/css" />
    </head>

    <body>
    <div class="container">
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <span class="brand">{QSOS Backend}</span>
                    
                    <!--Tabs-->
                    <ul class="nav" id="buttonBar">
                        <li><a href="index.php"><i class="icon-home icon-white"></i> <?php echo TXT_HOME; ?></a></li>
                        <li><a href="listRepo.php"><i class="icon-folder-close icon-white"></i> <?php echo TXT_GIT; ?></a></li>
                        <li><a href="logs.php"><i class="icon-time icon-white"></i> <?php echo TXT_TIMELINE; ?></a></li>
                        <li><a href="upload.php"><i class="icon-download-alt icon-white"></i> <?php echo TXT_UPLOAD; ?></a></li>
                    </ul>
                    <ul class="nav pull-right">
                            <li id="login"><?php echo $login ?></li>
                            <?php echo $logout ?>
                            
                            <!--Display or not settings tab-->
                            <?php
                            //If the user is a moderator or the admin, display settings tab
                            if(isset($_SESSION['login'])){
                                $sql = "SELECT status FROM users WHERE login=?";
                                $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                                $sth->execute(array($_SESSION['login']));
                                $data = $sth->fetchAll();
                                $status = $data[0][0];
                                if($status == "admin" || $status == "moderator"){
                                    echo '<li><a href="settings.php"><i class="icon-cog icon-white"></i>' . TXT_SETTINGS .'</a></li>';
                                }
                            }
                            ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="mainWindow">
        <div class="well">
            <div class="clearfix">
