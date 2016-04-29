<?php

class Caracteres{


public static function systemVerification($var){
if(count($var)==0){
	return False;
}
$arrayV = array();
$arrayV = explode("|",$var);
if(count($arrayV)!=0){
 return False;
}
$trimed = trim($var);
$strlower = strtolower($trimed);
if($strlower[0]=='r' && $strlower[1]=='m'){
 return False;
}
return True;
}


}

?>
