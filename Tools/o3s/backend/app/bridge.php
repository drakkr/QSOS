<?php
    require('conf.php');
    
    /*
     *  Select the status of an user
     */
    function userAccess($bdd){
        if(!isset($_SESSION)){
            session_start();
        }
        if (isset($_SESSION['login'])) { 
            $sql = 'SELECT status FROM users WHERE login= ?'; 
            $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array($_SESSION['login']));
            $data = $sth->fetchAll();
            return $data[0][0];
        }
    }

    $status = userAccess($bdd);
    
    switch ($_POST['function']) {
        
    /*
     *  Change Repository in listRepo
     */
    case('checkout'):
        $repo= $_POST['repo'];
	$type= $_POST['type'];
        if($repo == 'incoming'){
            $incoming->displayCategory($type);
        }else{
            $master->displayCategory($type);           
        }
        break;

    /*
     *  Display the file of a category
     */ 
    case('displayCategory'):
        $category = strtolower($_POST['category']);
        $master->displayCategory("evaluations", $category);
        $incoming->displayCategory("evaluations", $category);
        break;
    
    /*
     *  Display information about the evaluation
     */
    case('openEval'):
        $file = $_POST['file'];
        $switch = $_POST['repo'];
        if($switch == 'incoming'){
            $incoming->openEval($file);
        }else{
            $master->openEval($file);           
        }
        break;

    /*
     *  Display information about the template
     */
    case('openTpl'):
        $file = $_POST['file'];
        $switch = $_POST['repo'];
        if($switch == 'incoming'){
            $incoming->openTpl($file);
        }else{
            $master->openTpl($file);           
        }
        break;
 
    /*
     *  Upgrade a file to the master repo
     */
    case('upgradeEval'):
        if($status == "admin" || $status == "moderator"){
            $var = $_POST['file'];
            $return = $incoming->upgradeEval($var);
            $master->add();
            $incoming->commit("$var was moved !");
            $master->commit("$var was $return !");
        }
        break;

    /*
     *  Upgrade a file to the master repo
     */
    case('upgradeTpl'):
        if($status == "admin" || $status == "moderator"){
            $var = $_POST['file'];
            $return = $incoming->upgradeTpl($var);
            $master->add();
            $incoming->commit("$var was moved !");
            $master->commit("$var was $return !");
        }
        break;
    
    /*
     *  Delete an evaluation from the repo and the DB
     */
    case('delEval'):
        if($status == "admin" || $status == "moderator"){
            $var = $_POST['file'];
            $switch = $_POST['repo'];
            if($switch == 'incoming'){
                $incoming->deleteEval($var, "incoming");
            }elseif($switch == 'master'){
                $master->deleteEval($var, "incoming");
            }
        }
        break;

    /*
     *  Delete a template from the repo and the DB
     */
    case('delTpl'):
        if($status == "admin" || $status == "moderator"){
            $var = $_POST['file'];
            $switch = $_POST['repo'];
            if($switch == 'incoming'){
                $incoming->deleteTpl($var, "incoming");
            }elseif($switch == 'master'){
                $master->deleteTpl($var, "incoming");
            }
        }
        break;
    
    /*
     *  Display logs of the repo
     */
    case('logs'):
        $switch = $_POST['repo'];
        if($switch == 'incoming'){
            $incoming->logs();
        }else{
            $master->logs();           
        }
        break;
    
    /*
     *  Promote a user at moderator
     */
    case('promote'):
        if($status == "admin"){
            $sql = "UPDATE users SET status='moderator' WHERE login=?";
            $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array($_POST['user']));
            break;
        }
    default:
    break;
    }
?>