<?php require("header.php"); ?>

<?php
    if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') { 
        if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) { 
            
            //Check if the user exists
            $sql = 'SELECT count(*) FROM users WHERE login= ? AND pass_md5= ?'; 
            $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array($_POST['login'], md5($_POST['pass'])));
            $data = $sth->fetchAll();
            
            //If the user exists go to index.php
            if ($data[0][0] == 1) { 
               $_SESSION['login'] = $_POST['login']; 
               header('Location: index.php');
               exit(); 
            } 

            elseif ($data[0][0] == 0) { 
               $erreur = TXT_CONNECT_ERROR_PWD; 
            } 
            else { 
               $erreur = TXT_CONNECT_ERROR_DB; 
            } 
        } 
        else { 
           $erreur = TXT_REGISTER_EMPTY; 
        }  
    }  
    if (isset($erreur)) echo '<div class="alert alert-error">' . $erreur . '</div>';  
?>
    <form action="connect.php" method="post" id="connect">
        Login : <input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"><br />
        <?php echo TXT_CONNECT_PWD ?><input type="password" name="pass" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['pass'])); ?>"><br />
        <input type="submit" name="connexion" value="Connexion">
    </form>
    <a href="register.php"><?php echo TXT_CONNECT_REGISTER ?></a>
        
</div><!-- End of div.clearfix-->
</div><!-- End of div.well-->
</div><!-- End of div#mainWindow-->
</body>
</html>