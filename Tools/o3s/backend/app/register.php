<?php require("header.php"); ?>

<?php
if(isset($_POST['inscription'])){ 

    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && strlen($_POST['pass']) > 5 && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm']) && isset($_POST['mail']) && !empty($_POST['mail']))) { 
        
        $pattern = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
            
        if($_POST['pass'] != $_POST['pass_confirm']){ 
            $erreur = TXT_REGISTER_PWD_SAME;
        }
        elseif(!preg_match($pattern, $_POST['mail'])){
            $erreur = TXT_REGISTER_MAIL;
        }
        else{
            //Check if the user already exists
            $sql = 'SELECT count(*) FROM users WHERE login= ? OR mail= ?'; 
            $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array($_POST['login'], $_POST['mail']));
            $data = $sth->fetchAll();
            
            if ($data[0][0] == 0) {
                $sql = "INSERT INTO users(login, pass_md5, status, mail) VALUES(?, ?, 'user', ?)";
                $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array($_POST['login'], md5($_POST['pass']), $_POST['mail']));
    
                header('Location: connect.php'); 
                exit(); 
            } 
            else { 
                $erreur = TXT_REGISTER_EXIST; 
            } 
        }
    }elseif(strlen($_POST['pass']) < 6){
        $erreur = TXT_REGISTER_PWD;
    }else{ 
        $erreur = TXT_REGISTER_EMPTY;
    }
}
if (isset($erreur)){
    echo '<div class="alert alert-error">' . $erreur . '</div>';
}
?>

    <form action="register.php" method="post" id="connect">
        Login : <input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"><br />
        <?php echo TXT_CONNECT_PWD ?><input type="password" name="pass"><br />
        <?php echo TXT_CONNECT_PWD_CONFIRM ?><input type="password" name="pass_confirm" /><br />
        Email : <input type="text" name="mail" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['mail'])); ?>" /><br />
        <input type="submit" name="inscription" value="<?php echo TXT_REGISTER_SIGN ?>">
    </form>

</div><!-- End of div.clearfix-->
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>