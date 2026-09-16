<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['sign-up']='user/signup';
$route['sign-up/agency']='user/signup_agency';
$route['ajax/signup-check']='user/usersignupCheckAjax';
$route['ajax/signup-check-agency']='user/usersignupAgencyCheckAjax';
$route['register-success']='user/success';


$route['forgot'] = 'user/forgot';
$route['login'] = 'user/login';
$route['signup'] = 'user/signup';


$route['how-it-works']='cms/howitworks';
$route['terms-and-conditions']='cms/index/terms-and-conditions';
$route['privacy-policy']='cms/index/privacy-policy';
$route['refund-policy']='cms/index/refund-policy';
$route['about-us']='cms/index/about-us';
$route['user-agreement']='cms/index/user-agreement';
$route['help']='cms/help';
$route['help/(:any)']='cms/help_details/$1';
$route['contact-us']='cms/contactus';
$route['enterprise']='home/enterprise';
$route['membership']='membership/index';
$route['process-membership/(:any)/(:any)']='membership/select_membership/$1/$2';

$route['contact-request-check']='cms/submit_contact';

$route['services']='job/services';

$route['services/(:any)']='cms/index/$1';


$route['blogs']='blog';

$route['blogs']='blog';
$route['blog-details/(:any)']='blog/details/$1';