<?php require('header.php');
if(isset($_SESSION['login'])){
    if(isset($_POST['change_pwd']) && $_POST['oldpwd'] != "" && $_POST['newpwd'] != "" && $_POST['newpwdconfirm'] != ""){
        
        //If newpwd and newpwdconfirm match
        if(strlen($_POST['newpwd']) > 5 && $_POST['newpwd'] == $_POST['newpwdconfirm']){
            
            //Check if the user exists
            $sql = 'SELECT count(*) FROM users WHERE login= ? AND pass_md5= ?'; 
            $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array($_SESSION['login'], md5($_POST['oldpwd'])));
            $data = $sth->fetchAll();
            
            if ($data[0][0] == 1){
                $sql = "UPDATE users SET pass_md5=? WHERE login=?";
                $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array(md5($_POST['newpwd']), $_SESSION['login']));
                echo '<div class="alert alert-success">'. TXT_PROFIL_PWD_SUCCESS .'</div>'; 
            }else{
                echo '<div class="alert alert-error">'. TXT_PROFIL_PWD_ERROR .'</div>';
            }
        }else{
            echo '<div class="alert alert-error">'. TXT_PROFIL_PWD_FORM .'</div>'; 
        }
    }
    $sql = "SELECT login, mail, contributions, status FROM users WHERE login='". $_SESSION['login'] ."'";
    $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $data = $sth->fetchAll();
    echo "<p>". TXT_PROFIL_HELLO . " " .$data[0][0] ." !</p>";
    echo "<hr><h4>". TXT_PROFIL_MODIFY ."</h4>";
    echo "<p><br />";
    echo '<form action="profil.php" method="post" id="connect">';
    echo TXT_PROFIL_OLD .' : <input type="password" name="oldpwd"><br />';
    echo TXT_PROFIL_NEW .' : <input type="password" name="newpwd"><br />';
    echo TXT_PROFIL_NEW_CONFIRM .' : <input type="password" name="newpwdconfirm"><br />';
    echo '<input type="submit" name="change_pwd" value="Change">';
    echo '</form></p>';
}else{
    echo '<div class="alert alert-success">'. TXT_UPLOAD_LOGIN .'</div>';   
}
?>
</div>
</div>
</div>
</body>
</html>