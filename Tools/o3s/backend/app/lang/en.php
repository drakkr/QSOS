<?php

	/*
	 *	Header
	 */
	define('TXT_HOME', 'Home'); 
	define('TXT_GIT', 'Repositories');
	define('TXT_TIMELINE', 'Timeline');
	define('TXT_SETTINGS', 'Settings');

	/*
	 *	listRepo
	 */
	define('TXT_REPO_OPEN', 'Open');
	define('TXT_REPO_UPGRADE', 'Upgrade');
	define('TXT_REPO_EVALUATE', 'Evaluate');
	define('TXT_REPO_DELETE', 'Delete');
	define('TXT_REPO_ERROR', 'Error : Permission Denied, impossible to move ');
	define('TXT_REPO_SEARCH', 'Search...');
	define('TXT_REPO_CHANGED_FILES', 'Displays only changed files');
	define('TXT_REPO_DISPLAY_LIST', 'Change view');
	define('TXT_REPO_CATEGORIES', 'All categories');
        define('TXT_REPO_DOWNLOAD', 'Download');
        define('TXT_REPO_UPLOADED', 'Uploaded by');
        define('TXT_REPO_FILE', 'File');
        define('TXT_REPO_LICENSE', 'License');
        define('TXT_REPO_LANGUAGE', 'Language');
        define('TXT_REPO_VERSION', 'Version');
        define('TXT_REPO_APP_FAMILY', 'App family');
        define('TXT_REPO_APP_NAME', 'Application');


	/*
	 *	Logs
	 */
	define('TXT_DISPLAY_LOGS', 'View logs of: '); 
	define('TXT_BACK_IN_TIME', 'Back in time'); 

	/*
	 *	Settings
	 */
        define('TXT_SETTING_USER', 'Users list');
        define('TXT_SETTING_PROMOTE', 'Promote');
        define('TXT_UPLOAD_ROOT', 'You\'re not an administrator');
	define('TXT_DBCONF_UNDEF', 'Error: Configuration file not found ');
	define('TXT_DBTYPE_UNDEF', 'Error: Database type must be defined!');
	define('TXT_DBNAME_UNDEF', 'Error: Database name must be defined!');
	define('TXT_DBUSER_UNDEF', 'Error: Database user must be defined!');
	define('TXT_DBPASS_UNDEF', 'Error: Database password must be defined!');
	define('TXT_ERROR', 'Error: ');
        /*
         *      Upload
         */
        define('TXT_UPLOAD', 'Upload');
        define('TXT_UPLOAD_ERROR_EXIST', 'A file already has that name !');
        define('TXT_UPLOAD_ERROR_TYPE', 'The file is not a QSOS evaluation !');
        define('TXT_UPLOAD_ERROR_NAME', 'Invalid name !');
        define('TXT_UPLOAD_ERROR_MOVE', 'Impossible to move the file in the repository !');
        define('TXT_UPLOAD_ERROR_NOT_COMPLETE', 'Your evaluation isn\'t fully completed !');
        define('TXT_UPLOAD_ERROR_ALREADY', 'An evaluation with same name, version and language has already been uploaded by user ');
        define('TXT_UPLOAD_VALID', ': evaluation is valid and has been uploaded in the QSOS repository.');
        define('TXT_UPLOAD_NOT_VALID', 'Your document doesn\'t respect the QSOS schema:');
        define('TXT_UPLOAD_STAR', 'Fields with <span class="red">*</span> must be filled in');
        define('TXT_UPLOAD_LOGIN', 'You must login to access this feature !');
        define('TXT_UPLOAD_SELECT', 'Select a QSOS or a Template file on your computer:');

        define('TXT_UPLOAD_TPL_NOT_VALID', 'Your template doesn\'t respect the Freemind schema:');
        define('TXT_UPLOAD_TPL_MEDATADA_NOT_VALID', 'Following metatada is missing from your template:');
        define('TXT_UPLOAD_TPL_VALID', ': template is valid and has been uploaded in the QSOS repository.');
        
        /*
         *      Register.php
         */
        define('TXT_REGISTER_PWD_SAME', 'The passwords aren\'t the same.');
        define('TXT_REGISTER_PWD', 'The password must contain at minimum of 6 characters.');
        define('TXT_REGISTER_MAIL', 'Invalid email address.');
        define('TXT_REGISTER_EXIST', 'Email address or login already exists.');
        define('TXT_REGISTER_EMPTY', 'A field is empty.');
        define('TXT_REGISTER_SIGN', 'Sign On');
        
        /*
         *      Connect.php
         */
        define('TXT_CONNECT_PWD', 'Password :');
        define('TXT_CONNECT_PWD_CONFIRM', 'Password confirmation :');
        define('TXT_CONNECT_ERROR_PWD', 'Login/Password incorrect.');
        define('TXT_CONNECT_ERROR_DB', 'Databases error.');
        define('TXT_CONNECT_REGISTER', 'Sign on');
        
        /*
         *      Profil.php
         */
        define('TXT_PROFIL_HELLO', 'Hello');
        define('TXT_PROFIL_PWD_SUCCESS', 'Your password has been updated');
        define('TXT_PROFIL_PWD_ERROR', 'New passwords do not match');
        define('TXT_PROFIL_PWD_FORM', 'An error was occured');
        define('TXT_PROFIL_OLD', 'Old password');
        define('TXT_PROFIL_NEW', 'New password');
        define('TXT_PROFIL_NEW_CONFIRM', 'Confirm new password');
        define('TXT_PROFIL_MODIFY', 'Change password (6 print minimum)');
        
?>
