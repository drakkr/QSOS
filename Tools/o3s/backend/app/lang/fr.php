<?php

	/*
	 *	Header
	 */
	define('TXT_HOME', 'Accueil'); 
	define('TXT_GIT', 'Référentiels');
	define('TXT_TIMELINE', 'Historique');
	define('TXT_SETTINGS', 'Options');

	/*
	 *	listRepo
	 */
	define('TXT_REPO_OPEN', 'Ouvrir');
	define('TXT_REPO_UPGRADE', 'Valider');
	define('TXT_REPO_EVALUATE', 'Evaluer');
	define('TXT_REPO_DELETE', 'Supprimer');
	define('TXT_REPO_ERROR', 'Erreur : Impossible de déplacer ');
	define('TXT_REPO_SEARCH', 'Rechercher...');
	define('TXT_REPO_CATEGORIES', 'Tous les domaines fonctionnels');
        define('TXT_REPO_DOWNLOAD', 'Télécharger');
        define('TXT_REPO_UPLOADED', 'Mis en ligne par');
        define('TXT_REPO_FILE', 'Fichier');
        define('TXT_REPO_LICENSE', 'Sous licence');
        define('TXT_REPO_LANGUAGE', 'Langue');
        define('TXT_REPO_VERSION', 'Version');
        define('TXT_REPO_APP_FAMILY', 'Domaine foncionnel');
        define('TXT_REPO_APP_NAME', 'Application');

	/*
	 *	Logs
	 */
	define('TXT_DISPLAY_LOGS', 'Voir les logs de : '); 
	define('TXT_BACK_IN_TIME', 'Revenir dans le temps'); 

	/*
	 *	Settings
	 */
        define('TXT_SETTING_USER', 'Liste des utilisateurs');
        define('TXT_SETTING_PROMOTE', 'Promouvoir');
        define('TXT_UPLOAD_ROOT', 'Vous n\'avez pas les droit pour accéder à cette partie du site.');
        /*
         *      Upload.php
         */
        define('TXT_UPLOAD', 'Contribuer');
        define('TXT_UPLOAD_ERROR_EXIST', 'Un fichier porte déjà ce nom !');
        define('TXT_UPLOAD_ERROR_TYPE', 'Le fichier n\'est pas une évaluation QSOS !');
        define('TXT_UPLOAD_ERROR_NAME', 'Nom incorrect !');
        define('TXT_UPLOAD_ERROR_MOVE', 'Impossible de déplacer le fichier dans le dépôt !');
        define('TXT_UPLOAD_ERROR_NOT_COMPLETE', 'Votre évaluation ne contient pas certaines données importantes pour le référencement : Veuillez compléter l\'entête.');
        define('TXT_UPLOAD_ERROR_ALREADY', 'L\'application à déjà été évaluée pour cette version par l\'utilisateur ');
        define('TXT_UPLOAD_VALID', ' : votre évaluation est valide et a été intégrée au référentiel !');
        define('TXT_UPLOAD_NOT_VALID', 'Le document ne respecte pas le schéma QSOS :');
        define('TXT_UPLOAD_STAR', 'Les champs <span class="red">*</span> sont obligatoires');
        define('TXT_UPLOAD_LOGIN', 'Vous devez vous authentifier pour accéder à cette fonctionnalité !'); //Settings.php use it
        define('TXT_UPLOAD_SELECT', 'Selectionnez un fichier QSOS ou un Template sur votre ordinateur :');

        define('TXT_UPLOAD_TPL_NOT_VALID', 'Votre template ne respecte pas le schéma Freemind :');
        define('TXT_UPLOAD_TPL_MEDATADA_NOT_VALID', 'Les metadonnées suivantes manquent dans votre template :');
        define('TXT_UPLOAD_TPL_VALID', ' : votre template est valide et a été intégré au référentiel !');
        
        /*
         *      Register.php
         */
        define('TXT_REGISTER_PWD_SAME', 'Les 2 mots de passe sont différents.');
        define('TXT_REGISTER_PWD', 'Le mot de passe doit contenir au minimum 6 caractères !');
        define('TXT_REGISTER_MAIL', 'Adresse email invalide !');
        define('TXT_REGISTER_EXIST', 'L\'adresse email et/ou le login existent déjà');
        define('TXT_REGISTER_EMPTY', 'Au moins un des champs est vide.');
        define('TXT_REGISTER_SIGN', 'Inscription');
        
        /*
         *      Connect.php
         */
        define('TXT_CONNECT_PWD', 'Mot de passe :');
        define('TXT_CONNECT_PWD_CONFIRM', 'Confirmation du mot de passe :');
        define('TXT_CONNECT_ERROR_PWD', 'Login/Mot de passe incorrecte');
        define('TXT_CONNECT_ERROR_DB', 'Erreur base de données !');
        define('TXT_CONNECT_REGISTER', 'S\'inscrire');
        
        /*
         *      Profil.php
         */
        define('TXT_PROFIL_HELLO', 'Bonjour');
        define('TXT_PROFIL_PWD_SUCCESS', 'Votre mot de passe a été modifié');
        define('TXT_PROFIL_PWD_ERROR', 'Vos mots de passe ne correspondent pas');
        define('TXT_PROFIL_PWD_FORM', 'Une erreur est survenue');
        define('TXT_PROFIL_OLD', 'Ancien mot de passe');
        define('TXT_PROFIL_NEW', 'Nouveau mot de passe');
        define('TXT_PROFIL_NEW_CONFIRM', 'Confirmer mot de passe');
        define('TXT_PROFIL_MODIFY', 'Modifier mot de passe (6 caractères minimum)');
        
?>