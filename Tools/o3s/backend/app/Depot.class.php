<?php
class Depot extends GitRepo{

    private $_repo_name;
    private $_repo_path;
    
    /*
     *  Initialise the repo
     */
    public function __construct($name){
        $basepath = dirname(__FILE__).'/../';
        $this->_repo_name = $name;
        $this->_repo_path = $basepath . $this->_repo_name;
        parent::__construct($this->_repo_path);
    }

    public function getRepoPath(){
        return $this->_repo_path . "/";
    }
    
    /*
     *  Determine the status of an user
     */
    public function userAccess($bdd){
        if(!isset($_SESSION)){
            session_start();
        }
        if (isset($_SESSION['login'])) { 
            $login = $_SESSION['login'];
            $sql = 'SELECT status FROM users WHERE login= ?'; 
            $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array($login));
            $data = $sth->fetchAll();
            return $data[0][0];
        }
    }


    /*
     * Display all files in the category
     */
    public function displayCategory($type = "evaluations", $category = "all"){
        require('dataconf.php');
        $status = $this->userAccess($bdd);

	if ($type == "evaluations") {
	  $cmd = "ls-files *.qsos";
	} else {
	  $cmd = "ls-files *.mm";
	}
        $listRepo = $this->run($cmd);
        $listRepo = nl2br($listRepo);
        
        $contenu = explode("<br />", $listRepo);
        array_pop($contenu); //Remove the last value in the array

        for($i=0; $i<sizeof($contenu); $i++){
            $contenu[$i]= preg_replace("(\r\n|\n|\r)",'',$contenu[$i]);

	    if ($type == "evaluations") {
	      //Select informations about the evaluation
	      $file_info = $bdd->query("SELECT qsosappname, appname, qsosappfamily, evaluations.release, language, file FROM evaluations WHERE file='". $contenu[$i] ."'");
	      $file_data = $file_info->fetch();
	      
	      //Display all files in the repo
	      if($category != "all"){
		  $cat = strtolower($file_data['qsosappfamily']);
	      }else{
		  $cat = "all";
	      }
	      //Display only the files filled in the database
	      if($category == $cat && $file_data['file'] == $contenu[$i]){
		  //Add options for admin and moderator
		  if($status == "admin" || $status == "moderator"){
		      if($this->_repo_name != "master"){
			  //if the file is in incoming repo
			  echo "<div class='itemLine incoming' id='$contenu[$i]'><div class='name'>" . $file_data['appname'] . "</div><div class='release'>v" . $file_data['release'] . "</div><div class='appfamily'>" . strtolower($file_data['qsosappfamily']) . "</div><span class='toollist'><span class='openEval'>".TXT_REPO_OPEN."</span>|<span class='upgradeEval'>".TXT_REPO_UPGRADE."</span>|<span class='delEval'>".TXT_REPO_DELETE."</span></span><div class='info'></div></div>";
		      }else{
			  //if the file is in master repo   
			  echo "<div class='itemLine master' id='$contenu[$i]'><div class='name'>" . $file_data['appname'] . "</div><div class='release'>v" . $file_data['release'] . "</div><div class='appfamily'>" . strtolower($file_data['qsosappfamily']) . "</div><div class='certified'>Certified</div><span class='toollist'><span class='openEval'>".TXT_REPO_OPEN."</span>|<span class='delEval'>".TXT_REPO_DELETE."</span></span><div class='info'></div></div>";
		      }
		  }else{ //Line for simple user and visitor
		      if($this->_repo_name != "master"){
			  echo "<div class='itemLine incoming' id='$contenu[$i]'><div class='name'>" . $file_data['appname'] . "</div><div class='release'>v" . $file_data['release'] . "</div><div class='appfamily'>" . strtolower($file_data['qsosappfamily']) . "</div><span class='toollist'><span class='openEval'>".TXT_REPO_OPEN."</span></span><div class='info'></div></div>";
		      }else{
			  echo "<div class='itemLine master' id='$contenu[$i]'><div class='name'>" . $file_data['appname'] . "</div><div class='release'>v" . $file_data['release'] . "</div><div class='appfamily'>" . strtolower($file_data['qsosappfamily']) . "</div><div class='certified'>Certified</div><span class='toollist'><span class='openEval'>".TXT_REPO_OPEN."</span></span><div class='info'></div></div>";
		      }
		  }
	      }
	  } else {
	      //Select informations about the template
	      $file_info = $bdd->query("SELECT qsosappfamily, qsosspecificformat, language, file, uploader FROM templates WHERE file='". $contenu[$i] ."'");
	      $file_data = $file_info->fetch();
	      
	      //Display all files in the repo
	      if($category != "all"){
		  $cat = strtolower($file_data['qsosappfamily']);
	      }else{
		  $cat = "all";
	      }
	      //Display only the files filled in the database
	      if($category == $cat && $file_data['file'] == $contenu[$i]){
		  //Add options for admin and moderator
		  if($status == "admin" || $status == "moderator"){
		      if($this->_repo_name != "master"){
			  //if the file is in incoming repo
			  echo "<div class='itemLine incoming' id='$contenu[$i]'><div class='name'>" . strtolower($file_data['qsosappfamily']) . "</div><div class='release'>v" . $file_data['qsosspecificformat'] . "</div><span class='toollist'><span class='openTpl'>".TXT_REPO_OPEN."</span>|<span class='upgradeTpl'>".TXT_REPO_UPGRADE."</span>|<span class='delTpl'>".TXT_REPO_DELETE."</span></span><div class='info'></div></div>";
		      }else{
			  //if the file is in master repo   
			  echo "<div class='itemLine master' id='$contenu[$i]'><div class='name'>" . strtolower($file_data['qsosappfamily']) . "</div><div class='release'>v" . $file_data['qsosspecificformat'] . "</div><div class='certified'>Certified</div><span class='toollist'><span class='openTpl'>".TXT_REPO_OPEN."</span>|<span class='delTpl'>".TXT_REPO_DELETE."</span></span><div class='info'></div></div>";
		      }
		  }else{ //Line for simple user and visitor
		      if($this->_repo_name != "master"){
			  echo "<div class='itemLine incoming' id='$contenu[$i]'><div class='name'>" . strtolower($file_data['qsosappfamily']) . "</div><div class='release'>v" . $file_data['qsosspecificformat'] . "</div><span class='toollist'><span class='openTpl'>".TXT_REPO_OPEN."</span></span><div class='info'></div></div>";
		      }else{
			  echo "<div class='itemLine master' id='$contenu[$i]'><div class='name'>" . strtolower($file_data['qsosappfamily']) . "</div><div class='release'>v" . $file_data['qsosspecificformat'] . "</div><div class='certified'>Certified</div><span class='toollist'><span class='openTpl'>".TXT_REPO_OPEN."</span></span><div class='info'></div></div>";
		      }
		  }
	      }
	  }
        }
    }
    
    /*
     * Display informations about the evaluation
     */
    public function openEval($file){
        require('dataconf.php');
        $file_info = $bdd->query("SELECT appname, qsosspecificformat, evaluations.release, language, licensedesc, qsosappfamily, file, uploader FROM evaluations WHERE file='". $file ."'");
        $info = $file_info->fetch();

        echo "<div class='desc'>";
            echo TXT_REPO_APP_NAME . " : " . $info['appname'] . "<br />";
            echo TXT_REPO_APP_FAMILY . " : " . $info['qsosappfamily'] . "<br />";
            echo TXT_REPO_VERSION . " : " . $info['release'] . "<br />";
            echo TXT_REPO_LANGUAGE . " : " . $info['language'] . "<br />";
            echo TXT_REPO_LICENSE . " : " . $info['licensedesc'] . "<br />";
            echo TXT_REPO_FILE . " : " . $info['file'] . "<br />";
            echo TXT_REPO_UPLOADED . " : " . $info['uploader'];
        echo "</div>";
        echo "<a href='download.php?file=". $info['file'] ."'><button class='btn btn-success download'><i class='icon-download-alt icon-white'></i> ". TXT_REPO_DOWNLOAD ."</button></a>";
    }

    /*
     * Display informations about the template
     */
    public function openTpl($file){
        require('dataconf.php');
        $file_info = $bdd->query("SELECT qsosappfamily, qsosspecificformat, language, file, uploader FROM templates  WHERE file='". $file ."'");
        $info = $file_info->fetch();

        echo "<div class='desc'>";
            echo TXT_REPO_APP_NAME . " : " . $info['qsosappfamily'] . "<br />";
            echo TXT_REPO_VERSION . " : " . $info['qsosspecificformat'] . "<br />";
            echo TXT_REPO_LANGUAGE . " : " . $info['language'] . "<br />";
            echo TXT_REPO_FILE . " : " . $info['file'] . "<br />";
            echo TXT_REPO_UPLOADED . " : " . $info['uploader'];
        echo "</div>";
        echo "<a href='download.php?file=". $info['file'] ."'><button class='btn btn-success download'><i class='icon-download-alt icon-white'></i> ". TXT_REPO_DOWNLOAD ."</button></a>";
    }

    /*
     * Display logs of the repo
     */
    public function logs(){
        $logs = $this->run("log");
        $logs = nl2br($logs);
        $listLogs= explode("commit", $logs);
        array_shift($listLogs); //Supprime la derniere valeure vide du tableau.
        
        for($i=0; $i<sizeof($listLogs); $i++){
            $strCommit = $listLogs[$i];
            $idCommit = substr($strCommit, 1, 30);
	    $detailLog = explode("<br />", $listLogs[$i]);
	    echo '<div class="alert alert-success span5 offset3" id="' . $idCommit . '">' . $detailLog[2] . '<br/>' . $detailLog[4] . '</div>';
        }
    }

    /*
     * Upgrade an evaluation to the master repo
     */
    public function upgradeEval($file){
        require('dataconf.php');
        try{
            if($this->_repo_name == "incoming" && is_file($this->getRepoPath().$file)){

		//Check if evaluation is already in master repo
		$return = "added";
		$file_info = $bdd->query("SELECT id FROM evaluations WHERE file='". $file ."' and repo='master'");
		$info = $file_info->fetch();
		if($info) {
		  $remove_file = $bdd->query("DELETE FROM evaluations WHERE id=".$info[0]);
		  $return = "updated";
		}

		$sql = "UPDATE evaluations SET repo='master' WHERE file=?";
		$sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array($file));
                rename("../incoming/$file", "../master/$file");
	      
		return $return;
            }
        }
        catch (Exception $e){
            die(TXT_REPO_ERROR . "$file");
        }
    }

    /*
     * Upgrade a template to the master repo
     */
    public function upgradeTpl($file){
        require('dataconf.php');
        try{
            if($this->_repo_name == "incoming" && is_file($this->getRepoPath().$file)){

		//Check if evaluation is already in master repo
		$return = "added";
		$file_info = $bdd->query("SELECT id FROM templates WHERE file='". $file ."' and repo='master'");
		$info = $file_info->fetch();
		if($info) {
		  $remove_file = $bdd->query("DELETE FROM templates WHERE id=".$info[0]);
		  $return = "updated";
		}

		$sql = "UPDATE templates SET repo='master' WHERE file=?";
		$sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array($file));
                rename("../incoming/$file", "../master/$file");
	      
		return $return;
            }
        }
        catch (Exception $e){
            die(TXT_REPO_ERROR . "$file");
        }
    }
    
    /*
     * Delete the evaluation from the repo
     */
    public function deleteEval($file){
        require('dataconf.php');
        if(unlink($this->getRepoPath() . $file)){
            $this->commit("$file was deleted");
            $remove_file = $bdd->query("DELETE FROM evaluations WHERE file='". $file ."' AND repo='".$this->_repo_name."'");
        }
    }

    /*
     * Delete the template from the repo
     */
    public function deleteTpl($file){
        require('dataconf.php');
        if(unlink($this->getRepoPath() . $file)){
            $this->commit("$file was deleted");
            $remove_file = $bdd->query("DELETE FROM templates WHERE file='". $file ."' AND repo='".$this->_repo_name."'");
        }
    }

}
?>