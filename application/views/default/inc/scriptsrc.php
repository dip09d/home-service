<?php
$currentLang=$this->config->item('language');
//$seo_images=array(LOGO);
$curreentDir="ltr";
if($currentLang=='ar'){
	$curreentDir="rtl";
}
$canonical=VPATH.uri_string();
$canonicals[]=$canonical;
$title=strip_tags(($meta_title? $meta_title : 'SnapHive Manpower Services in Kolkata | Hourly Staffing – Powered by People'));
$description=strip_tags(($meta_description? $meta_description : 'SnapHive provides trusted manpower services in Kolkata including Aya, Nurse, Driver, Physiotherapist, Sanitation Worker & more. Hourly basis service. Powered by People.'));
$seo_title=substr($title,0,70);
$seo_description=substr($description,0,160);
$fb_app_id=get_setting('fb_app_id');
$google_client_id=get_setting('google_client_id');
$tw_page_username=get_setting('tw_page_username');
$tw_creator_username=get_setting('tw_creator_username');
$website_name=get_setting('website_name');

?>
<!doctype html>
<html lang="<?php D($currentLang);?>" dir="<?php D($curreentDir);?>">
<head>

<!-- Basic Page Needs
================================================== -->
<title><?php echo !empty($title) ? $title : get_setting('site_title'); ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<?php $this->layout->load_meta(); ?>

<meta name="robots" content="index, follow" /> <!--– Means index and follow this web page.-->
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php D($canonical);?>" />
<meta name="twitter:url" content="<?php D($canonical);?>" />

<meta property="og:title" content="<?php D($title);?>" />
<meta property="og:description" content="<?php D($description);?>" />
<meta property="og:site_name" content="<?php D($website_name);?>" />
<?php if($fb_app_id){?>
<meta property="fb:app_id" content="<?php D($fb_app_id);?>" />
<?php }?>
<?php if($google_client_id){?>
<meta name="google-signin-client_id" content="<?php D($google_client_id);?>">
<?php }?>
<!--<meta name="twitter:card" content="" />-->
<meta name="twitter:title" content="<?php D($title);?>" />
<meta name="twitter:description" content="<?php D($description);?>" />
<?php /* if($seo_images){
	foreach($seo_images as $image){
	?>
<meta property="og:image" content="<?php D($image);?>" />
<meta name="twitter:image" content="<?php D($image);?>" />
<?php 	
	}
} */?>
<?php if($tw_page_username){?>
<meta name="twitter:site" content="<?php D($tw_page_username)?>" /> <!--@username-->
<?php }?>
<?php if($tw_creator_username){?>
<meta name="twitter:creator" content="<?php D($tw_creator_username)?>" /> <!--@username-->
<?php }?>
<meta name="author" content="<?php D($website_name);?>" />
<meta name="organization" content="<?php D($website_name);?>" />
<link rel="shortcut icon" href="<?php D(FAVICON)?>" type="image/x-icon">
<?php foreach($canonicals as $url){?>
<link rel="canonical" href="<?php D($url);?>" /> 
<?php }?>
<?php

$this->minify->add_css('icons.css');
$this->minify->add_css('global'.($currentLang=='ar'? '.rtl':'').'.css');
$this->minify->add_css('theme'.($currentLang=='ar'? '.rtl':'').'.css');
$this->minify->add_css('keyframes.css');
$this->minify->add_css('colors.css');
$load_css=$this->layout->load_css(); 
if(!empty($load_css)){
	foreach($load_css as $files){
		$this->minify->add_css($files);
	}
}
$this->minify->add_css('lang_'.($currentLang=='ar' ? 'ar':'en').'.css');
echo $this->minify->deploy_css(FALSE, 'header.min.css');
?>


<script type="text/javascript">
    var VPATH = '<?php echo base_url();?>';
</script>
</head>
<body>

<!-- Wrapper -->
<!--<div id="wrapper" class="wrapper-with-transparent-header"> for transparent header -->
<div id="wrapper" class="">