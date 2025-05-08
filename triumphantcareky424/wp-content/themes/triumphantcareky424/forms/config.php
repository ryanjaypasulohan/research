<?php
ini_set('display_errors', 'off');
error_reporting(E_ALL);
define('COMP_EMAIL', 'Infos@triumphantcaresolutions.com'); // clients email

define('SMTP_ENCRYPTION', 'off'); // TLS, SSL or off
define('SMTP_PORT', 587); // SMPT port number 587 or default
define('COMP_NAME', 'Triumphant Care Solutions LLC'); // company name
define('MAIL_TYPE', 2); // 1 - html, 2 - txt
define('MAIL_DOMAIN', 'www.triumphantcaresolutions.com'); // company domain
define('DEV_MODE',false); //if false = launched account , true = pages account


// Update it using a working google Site key
$recaptcha_sitekey = '6LehRSUjAAAAAOUb_GZi0DR1iRASHzSUSicn1noK';
// Update it using a working google Privite key
$recaptcha_privite = '6LehRSUjAAAAAM1so21sPH_oN979VuuE6VVU-AS6';

// do not edit
$subject_incoming = COMP_NAME . " [" . $formname . "]";
$template = 'template';
$to_name = NULL;
$from_email = 'email@proweaverforms.com';
$from_name = 'Message From Your Site';
$attachments = array();

// testing here
$testform = false;
if($testform){
	$to_email 	= array('webtest1o@outlook.com');
	$cc = '';
	$bcc = '';
}else{
	$to_email 	= array('Infos@triumphantcaresolutions.com');
	$cc = '';
	$bcc = '';
}