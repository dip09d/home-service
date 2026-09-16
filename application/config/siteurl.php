<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$lang_key="";
/*$ci =& get_instance();
$lang_ignore=$ci->config->item('lang_ignore');
if($_COOKIE['user_lang'] && $lang_ignore==FALSE){
	$lang_key=$_COOKIE['user_lang']."/";	
}
define('USER_LANG_KEY',$lang_key);
$lang_key=USER_LANG_KEY;*/
$config['homeURL'] = '';
$config['loginURL']= 'login/';
$config['forgotURL']= 'forgot/';
$config['IsLoginURL']= 'user/is_login';
$config['registerURL'] = 'sign-up/';
$config['registerAgencyURL'] = 'sign-up/agency';
$config['signupsavelogo'] = 'user/get_save_logo_check';
$config['registerSuccessURL'] = 'register-success';


$config['settingsURL'] = 'settings/';
$config['SetLanguage'] = 'home/setlanguage';
$config['VerifyURL'] = 'verify-user/'; #router
$config['resetPasswordURL'] = 'user/resetpassword';
$config['ForgotVerifyURL'] = 'verify-user-forgot/'; #router
$config['FortgotURLAJAX'] = 'ajax/forget-check';
$config['resetURLAJAX']= 'user/userresetCheckAjax';
$config['resendEmailURLAJAX']= 'user/resendemail';
$config['verifyDocumentURL']= 'dashboard/verifydocument';
$config['SaveDocumentAJAXURL']= 'dashboard/verifydocumentCheckAjax';

$config['servicecategorydetailsUrl']= 'services';





$config['CMSaboutus'] = 'about-us'; #router
$config['conatctURL'] = 'contact-us'; #router
$config['conatctCheckAjaxURL'] = 'contact-request-check'; #router
$config['CMStermsandconditions'] = 'terms-and-conditions'; #router
$config['CMSprivacypolicy'] = 'privacy-policy'; #router
$config['CMSrefundpolicy'] = 'refund-policy'; #router
$config['enterpriseURL'] = 'enterprise'; #router
$config['membershipURL'] = 'membership'; #router
$config['processMembershipURL'] = 'process-membership'; #router
$config['processMembershipFormCheckAJAXURL'] = 'membership/processmembership';
$config['CMShelp'] = 'help'; #router
$config['CMShowitworks'] = 'how-it-works'; #router
$config['CMSuseragreement'] = 'user-agreement'; #router


$config['InvoiceDetailsURL'] = 'invoice/details';



$config['downloadProjectFileURL'] = 'projectview/downloadfile';
$config['downloadTempURL'] = 'welcome/downloadtempfile';

$config['servicesURL'] = 'job/services'; #router
$config['CMSnurseURL'] = 'nurse'; #router
$config['blogURL'] = 'blog'; #router

$config['blogListURL'] = 'blogs';
$config['blogDetailsURL'] = 'blog-details'; #router
