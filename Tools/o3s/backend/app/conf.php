<?php
    function loadClass ($class){
        require $class . '.class.php';
    }
    spl_autoload_register ('loadClass');
    require('./lib/Git.php');  

    /*
     *  Select language from the browser
     */
    $lang = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
    if (strstr($lang[0], 'fr')){
            include('lang/fr.php');
    }elseif (strstr($lang[0], 'en')){
            include('lang/en.php');
    }else{
        include('lang/en.php');
    }
    
    /*
     *  Initialisation of the repositories
     */
    try
    {
        $master = new Depot('master');
        $incoming = new Depot('incoming');
    }
    catch (Exception $e)
    {
            die('Error 001: Repository initialisation: '.$e->getMessage());
    }

    require('dataconf.php');
    
?>