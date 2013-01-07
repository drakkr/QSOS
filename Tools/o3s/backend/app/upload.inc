<?php

function upload($fichier, $login) {   
  global $incoming, $master, $lang, $bdd;

    if(isset($fichier)){
        $content_dir = 'upload/';

        $tmp_file = $fichier['tmp_name'];
        $name_file = $fichier['name'];

	$type_file = array_reverse(explode('.', $name_file))[0];

        if( !is_uploaded_file($tmp_file) ) {
            alertError("No file existing");
        }

//         if(file_exists($incoming->getRepoPath() . $name_file)) {
//             $error = TXT_UPLOAD_ERROR_EXIST;
//             alertError("Step 2: Upload $name_file [ERROR] : $error");
// 	    return;
//         }

	$re1='.*?';
	$re2='(\\.)';
	$re3='(qsos)';
	$re4='(mm)';
        
        if(!(preg_match_all ("/".$re1.$re2.$re3."/is", $name_file, $matches)) && !(preg_match_all ("/".$re1.$re2.$re4."/is", $name_file, $matches))){
            $error = TXT_UPLOAD_ERROR_TYPE;
            alertError("Step 3: Upload $name_file [ERROR] : $error");
	    exit();
        }
    
        if( preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name_file) ) {
            $error = TXT_UPLOAD_ERROR_NAME;
            alertError("Step 4: Upload $name_file [ERROR] : $error");
	    exit();
        }
        else if( !move_uploaded_file($tmp_file, $content_dir . $name_file) ) {
            $error = TXT_UPLOAD_ERROR_MOVE;
            alertError("Step 5: Upload $name_file [ERROR] : $error");
	    exit();
        }

        $file = "upload/" . $name_file;

	if(is_file($file) && $type_file == "qsos") {

	  include("../../formats/libs/QSOSDocument.php");
	
	  $myDoc = new QSOSDocument($file);
	  $name_file = $myDoc->getFileName();

	  //XSD schema validation
	  $XSD = "../../formats/xml/xsd/qsos.xsd";

	  if($myDoc->checkXSD($XSD) != "ok") {
	    $errors = $myDoc->checkXSD($XSD);

	    alertError(TXT_UPLOAD_NOT_VALID);
	    for ($i=0; $i < count($errors); $i++) {
	      alertError($errors[$i]);
	    }
	    unlink("upload/" . $name_file);
	  } else {

	    $metadata = array();
	    $keys = array( "qsosappfamily", "qsosspecificformat", "appname", "qsosappname", "release", "language", "licensedesc" ,"creation", "validation" );
	    
	    foreach ($keys as $key) {
	      $metadata[$key] = $myDoc->getkey($key);
	    }
	    
	    $metadata["authors"] = $myDoc->getauthors();

	    $metadata["sections"] = $myDoc->getcountkey("section");
	    
	    $metadata["criteria_total"] = $myDoc->getcountkey("element");
	    $metadata["criteria_scorable"] = $myDoc->getcountkey("element/score");
	    $metadata["criteria_scored"] = $myDoc->getcountkey("element[score >= 0]");
	    $metadata["criteria_notscored"] = $myDoc->getcountkey("element[score = '']");
	    
	    $metadata["comments_total"] = $myDoc->getcountkey("element/comment");
	    $metadata["comments_commented"] = $myDoc->getcountkey("element[comment != '']");
	    $metadata["comments_notcommented"] = $myDoc->getcountkey("element[comment = '']");

	    $tuple_info = $bdd->query("SELECT id, uploader FROM evaluations WHERE repo='incoming' AND qsosappname = '" . $metadata['qsosappname']
	      . "' AND evaluations.release  = '" . $metadata['release'] . "' AND language = '" . $metadata['language'] . "'");

	    if ($info = $tuple_info->fetch()) {
		if($info['uploader'] != $login) {
		  alertError(TXT_UPLOAD_ERROR_ALREADY.$info['uploader']);	      
		  unlink($file);
		  return;
		} else {
		  $action = "updated";
		}
	    } else {
	      $action = "added";
	    }

	    alertSuccess($name_file.TXT_UPLOAD_VALID);
	    rename($file, $incoming->getRepoPath() . $name_file);
	    $incoming->add("*");

	    $qsosappfamily = strtolower($metadata['qsosappfamily']);

	    if ($action == "updated") {
	      $req = $bdd->exec(
		"UPDATE evaluations SET qsosappfamily='$qsosappfamily', qsosspecificformat='$metadata[qsosformat]', appname='$metadata[appname]', file='$name_file', licensedesc='$metadata[licensedesc]', creation='$metadata[creation]', validation='$metadata[validation]', sections='$metadata[sections]', criteria='$metadata[criteria_total]', criteria_scorable='$metadata[criteria_scorable]', criteria_scored='$metadata[criteria_scored]', criteria_notscored='$metadata[criteria_notscored]', comments='$metadata[comments_total]', criteria_commented='$metadata[comments_commented]', criteria_notcommented='$metadata[comments_notcommented]', repo='incoming' WHERE id=$info[id];"
	      );
	    } else {
	      $req = $bdd->exec(
		"INSERT INTO evaluations(qsosappfamily, qsosspecificformat, qsosappname, evaluations.release, appname, language, file, licensedesc, creation, validation, sections, criteria, criteria_scorable, criteria_scored, criteria_notscored, comments, criteria_commented, criteria_notcommented, uploader, repo)
		  VALUES('$qsosappfamily', '$metadata[qsosformat]', '$metadata[qsosappname]', '$metadata[release]', '$metadata[appname]', '$metadata[language]', '$name_file', '$metadata[licensedesc]', '$metadata[creation]', '$metadata[validation]', '$metadata[sections]', '$metadata[criteria_total]', '$metadata[criteria_scorable]', '$metadata[criteria_scored]', '$metadata[criteria_notscored]', '$metadata[comments_total]', '$metadata[comments_commented]', '$metadata[comments_notcommented]', '$login', 'incoming');"
	      );
	    }

	    try {
	      $incoming->commit("$name_file was $action by $login");
	    } catch (Exception $e) {
	      //Hack: Git seems to return an empty error via proc_close (cf; lib/Git.php class line 210) when file's content is not modified
	      if ($e->getMessage()) alertError($e->getMessage());
	    }

	    //Ajoute +1 au compteur des contributions de l'utilisateur
	    $sql = "UPDATE users SET contributions=contributions+1 WHERE login=?";
	    $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	    $sth->execute(array($login));
	  }
	} else if (is_file($file) && $type_file == "mm") {

	  include("../../formats/libs/QSOSTemplate.php");
	
	  $myTpl= new QSOSTemplate($file);
	  $name_file = $myTpl->getTemplateFileName();

	  //XSD schema validation
	  $XSD = "../../formats/xml/xsd/freemind.xsd";

	  if($myTpl->checkFreemindXSD($XSD) != "ok") {
	    $errors = $myTpl->checkFreemindXSD($XSD);

	    alertError(TXT_UPLOAD_TPL_NOT_VALID);
	    for ($i=0; $i < count($errors); $i++) {
	      alertError($errors[$i]);
	    }
	    unlink("upload/" . $name_file);
	    exit();
	  } elseif($myTpl->checkTemplateMetadata() != "ok") {
	    //Check template's metadata
	    $errors = $myTpl->checkTemplateMetadata();

	    alertError(TXT_UPLOAD_TPL_MEDATADA_NOT_VALID);
	    for ($i=0; $i < count($errors); $i++) {
	      alertError($errors[$i]);
	    }
	    unlink("upload/" . $name_file);
	    exit();
	  } else {
	    alertSuccess($name_file.TXT_UPLOAD_VALID);
	    rename($file, $incoming->getRepoPath() . $name_file);
	    $incoming->add("*");

	    $qsosappfamily = strtolower($myTpl->getTemplateDomainName());
	    $qsosspecificformat = $myTpl->getTemplateVersion();
	    $language = $myTpl->getTemplateLanguage();
	    $creation = $myTpl->getTemplateCreationDate();
	    $update = $myTpl->getTemplateModificationDate();

	    $req = $bdd->exec(
	      "INSERT INTO templates(qsosappfamily, qsosspecificformat, language, file, creation, templates.update, uploader, repo)
		VALUES('$qsosappfamily', '$qsosspecificformat', '$language', '$name_file', '$creation', '$update', '$login', 'incoming');"
	    );

	    $incoming->commit("$name_file was added by $login");

	    //Ajoute +1 au compteur des contributions de l'utilisateur
	    $sql = "UPDATE users SET contributions=contributions+1 WHERE login=?";
	    $sth = $bdd->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	    $sth->execute(array($login));
	  }
	}
    }
}
