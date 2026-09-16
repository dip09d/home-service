<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
.up-active-editor .up-active-container .up-active-context, .up-active-editor .up-active-container .up-active-context-hover {
    position: relative;
}
.up-active-control {
    margin-top: 1px;
    padding-top: 1px;
    padding-left: 1px;
    display: none;
}
.p-lg-bottom {
    padding-bottom: 30px !important;
}
.m-0-top-bottom {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}
.m-0-left-right-xl {
    margin-left: 0 !important;
    margin-right: 0 !important;
}
.m-xs-bottom {
    margin-bottom: 5px !important;
}
fe-profile-map .fe-map-trigger {
    padding-bottom: 2px;
}
.up-active-editor .up-active-control {
    right: -35px;
    position: absolute;
    top: -1px;
    z-index: 1000;
}
.up-active-editor .up-active-control.up-active-control-portrait {
    position: absolute;
    margin-bottom: -10px;
    margin-top: -5px;
    top: 0px;
    left: 10px;
    right: auto;
}
.up-active-editor .up-active-container:hover .up-active-control {
    display: inline-block;
}
.cfe-avatar {
    width: 80px;
    height: 80px;
    margin: 20px;
    padding: 0;
}
.fe-ui-application img, .fe-ui-window img {
    border: medium none;
    max-width: 100%;
    overflow: hidden;
    vertical-align: middle;
}	
h3 .up-active-control {
    top: -7px !important;
    margin-left: 5px;
}
.up-active-editor .up-active-control.up-active-control-title {
    margin-top: 4px;
}
.cfe-overview {
    position: relative;
}
.cfe-overview .up-active-control {
    top: 0 !important;
    right: -5px !important;
}
.cfe-aggregates ul > li {
    width: 135px !important;
    min-width: 135px !important;
    max-width: none;
    margin-bottom: 0;
}



.thumb-block {
    position: relative;
    overflow: hidden;
}
.thumb-grid .thumbnail {
    height: 242px;
    overflow: hidden;
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
}
.thumbnail {
    display: block;
    margin-bottom: 30px;
    line-height: 1.42857;
    background-color: #F9F9F9;
    border: 1px solid #E0E0E0;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
}
.thumbnail-controls {
    width: 100%;
    height: 100%;
    position: absolute;
    text-align: center;
    z-index: -1;
}
.thumbnail-controls article {
    padding: 14px 9px 9px;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
}

.thumbnail:focus, .thumbnail:hover {
    border-color: transparent;
    -webkit-box-shadow: 0 1px 6px rgba(57,73,76,0.35);
    box-shadow: 0 1px 6px rgba(57,73,76,0.35);
}
.edit-mode .thumbnail-controls article, .is-owner .thumb-block:hover .thumbnail-controls article, .is-owner .thumbnail:hover .thumbnail-controls article, .thumb-wrapper:hover .thumbnail-controls article {
    display: inline-block;
}
.thumb-footer {
    position: absolute;
    bottom: 15px;
}
strong {
    font-weight: 700;
}
.thumbnail-controls::before {
    content: " ";
    position: absolute;
    background-color: black;
    display: block;
    width: 100%;
    height: 100%;
    top: 0;
}
.edit-mode .thumbnail-controls, .is-owner .thumb-block:hover .thumbnail-controls, .is-owner .thumbnail:hover .thumbnail-controls, .thumb-wrapper:hover .thumbnail-controls {
    visibility: visible;
    z-index: 100;
    cursor: pointer;
    opacity: 1;
}
.edit-mode .thumbnail-controls::before,
.is-owner .thumb-block:hover .thumbnail-controls::before,
.is-owner .thumbnail:hover .thumbnail-controls::before,
.thumb-wrapper:hover .thumbnail-controls::before {
    opacity: 0.3;
}
.o-tag, .o-tag-tested, .o-tag-certified, .o-tag-skill, .tokenizer-wrapper>.tokenizer-token-list .tokenizer-token {
    background-color: #E0E0E0;
    border-radius: 4px;
    color: #222;
    font-size: 12px;
    display: inline-block;
    cursor: default;
    padding: 5px 10px;
    line-height: 1;
    margin: 10px;
}
.o-tag-skill {
    margin: 0 6px 10px 0;
}
.is-owner h4.fe-employment-history-editor-title, .is-owner h4.fe-education-editor-title, .is-owner h4.fe-other-experience-editor-title {
    margin-right: 90px !important;
}
.up-active-editor .up-active-control.up-active-control-edit {
    right: -50px;
}
.up-active-editor .up-active-control.up-active-control-delete {
    top: 0;
    right: -85px;
}
.up-active-editor .up-active-control.up-active-control-profile-link, .up-active-editor .up-active-control.up-active-control-availability {
    top: -5px;
}
.up-active-editor .up-active-control.up-active-control-language {
    right: auto !important;
    left: auto !important;
    margin-left: 10px;
    top: -5px;
}
.up-active-editor .up-active-control.up-active-control-language.up-active-control-delete {
    margin-left: 50px;
    top: -5px;
}
.up-active-editor .up-active-container:hover .up-active-context .fa {
    color: #008329;
}
</style>
<div id="layout" class="is-owner">
<div class="container p-lg-bottom p-0-top-bottom-xs p-0-top-bottom-sm">
            <div data-o-smf="" data-o-smf-location="top" class="ng-isolate-scope"><!-- ngRepeat: message in messages --> </div>


<div data-ng-controller="fe.app.profile.ProfileDetailsCtrl as vm" class="ng-scope">
    <div>
        <!-- ngIf: vm.error -->

                    <!-- ngIf: vpd -->

        
        <!-- ngIf: vm.isShowProfile() --><fe-profile ng-if="vm.isShowProfile()" fe-vpd="vm.overlay.vpd" fe-settings="vm.overlay.settings" class="ng-scope ng-isolate-scope"><!-- ngIf: vpd.ready --><div data-ng-if="vpd.ready" class="ng-scope">
<!-- ngIf: actor.isOwner() && settings.scrubbingFlags --><o-scrubbing-header ng-if="actor.isOwner() &amp;&amp; settings.scrubbingFlags" scrubbing-flags="settings.scrubbingFlags" class="ng-scope ng-isolate-scope"><!-- ngIf: vm.scrubbingFlags.violationsRemoved -->
<!-- ngIf: vm.scrubbingFlags.violations && vm.scrubbingFlags.violations.length --></o-scrubbing-header><!-- end ngIf: actor.isOwner() && settings.scrubbingFlags -->
<!-- ngIf: actor.isOwner() || isOnboardingV2() --><o-onboarding-header ng-if="actor.isOwner() || isOnboardingV2()" state="vpd.profile.state" qt-allocations="settings.qt" is-ace="actor.isACE()" is-byof="isBYOF" is-onboarding-v2="isOnboardingV2()" on-resubmit-success="onProfileResubmitSuccess" class="ng-scope ng-isolate-scope"><!-- ngIf: isOnboardingV2 && (state == profileStates.DRAFT || state == profileStates.IN_PROCESS) -->
<!-- ngIf: !isOnboardingV2 && !submitted -->
<!-- ngIf: !isOnboardingV2 && showSuccess -->
<!-- ngIf: !isOnboardingV2 && isInReviewStatus() -->
<!-- ngIf: !isOnboardingV2 && isInReviewAsByoc() -->
<!-- ngIf: !isOnboardingV2 && (state == profileStates.DRAFT || state == profileStates.IN_PROCESS) -->
<!-- ngIf: !isOnboardingV2 && state == profileStates.NOT_STARTED -->
</o-onboarding-header><!-- end ngIf: actor.isOwner() || isOnboardingV2() -->
<!-- ngIf: actor.isOwner() --><cfe-profile-migration-alert ng-if="actor.isOwner()" selected-profile="cfe.selectedProfile" service-profiles="cfe.serviceProfiles" on-review-and-publish-click="cfe.navigateToSelectedProfile(profile)" class="ng-scope ng-isolate-scope"><!-- ngIf: $ctrl.isComponentVisible -->
</cfe-profile-migration-alert><!-- end ngIf: actor.isOwner() -->
</div><!-- end ngIf: vpd.ready -->
<div class="fe-ui-application responsive">

<!-- ngIf: vpd.ready && actor.isVisitor() -->
<div class="fe-ui-application cfe-ui-application">
<!-- ngIf: vpd.ready && actor.isVisitor() && isBackToSearchBtnVisible() -->
<div id="oProfilePage" class="row eo-block-none o-profile ng-isolate-scope cfe-feature-tour-inactive" eo-new-feature-tour="" location="custom" group="service-profiles" module="*" open="open">
<div data-ng-show="vpd.ready" class="cfe-main p-0-left-right-xs col-xs-12 col-md-9" data-ng-class="settings.hideRightColumn ? 'col-xs-12' : 'col-xs-12 col-md-9'">
<!-- ngIf: isProfilePrivate -->
<!-- ngIf: !isProfilePrivate --><fe-profile-header data-ng-if="!isProfilePrivate" data-fe-vpd="vpd" data-fe-settings="settings" data-show-map="isShowMap()" data-has-job-stats="hasJobStats()" data-is-show-fl-earnings="isShowFlEarnings" data-is-show-fl-recent-earnings="isShowFlRecentEarnings" data-job-type="settings.jobType" data-is-body-rate-slot-enabled="isSlotFilled('feBodyRateSlot')" data-fl-earnings="isShowFlRecentEarnings ? vpd.stats.recentEarnings : getFlEarnings()" class="ng-scope ng-isolate-scope"><!-- ngIf: vm.vpd --><div data-ng-if="vm.vpd" class="fe-profile-header ng-scope" itemscope="" itemtype="http://schema.org/Person">
<!-- ngIf: vm.cfe.showCreateServiceProfile() -->
<div data-eo-block="false" data-target="body" data-block-decorator="eo-block-large full-page-loading" class="ng-scope">
</div>
<div id="optimizely-header-container-default" data-ng-class="{'up-active-editor': vm.actor.isOwner(), 'p-0-bottom': !vm.settings.isOwner &amp;&amp; vm.settings.isShowShareBanner}" data-eo-block="" data-message="Reviewing your photo..." class="air-card m-0-left-right-md m-0-left-right-xl m-0-top-bottom m-0-right ng-scope up-active-editor">
<div class="row m-lg-bottom">
<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
<div class="media">
<div class="float-start">
<fe-profile-portrait-categories is-blocked="false" is-editable="true" on-profile-portrait-uploaded="vm.onProfilePortraitUploaded(portrait)" portrait="vm.vpd.profile.portrait" username="vm.vpd.profile.shortName" class="ng-isolate-scope"><div class="up-active-container ng-isolate-scope up-cursor-pointer" up-highlight-editor="" up-highlight-editor-selector="up-active-context-title" ng-class="{'up-cursor-pointer':isEditable(), 'm-md-top': dragover, 'm-md-right':dragover}" eo-file-drop="onFileDrop($files, $event)">
<!-- ngIf: !portrait.bigPortrait -->
<!-- ngIf: portrait.bigPortrait --><img ng-hide="dragover" ng-if="portrait.bigPortrait" ng-click="ui.openEditDialog(portrait)" id="userPortrait" ng-src="https://odesk-prod-portraits.s3.amazonaws.com/Users:asish9735:PortraitUrl_100?AWSAccessKeyId=AKIAIKIUKM3HBSWUGCNQ&amp;Expires=2147483647&amp;Signature=g3oH1kHKa2JwcGSVqbbnkKA%2Fhzs%3D&amp;1536918124141191&amp;LWzOB" alt="Asharam P." class="avatar cfe-avatar m-0 ng-scope" drop-box="" src="https://odesk-prod-portraits.s3.amazonaws.com/Users:asish9735:PortraitUrl_100?AWSAccessKeyId=AKIAIKIUKM3HBSWUGCNQ&amp;Expires=2147483647&amp;Signature=g3oH1kHKa2JwcGSVqbbnkKA%2Fhzs%3D&amp;1536918124141191&amp;LWzOB"><!-- end ngIf: portrait.bigPortrait -->
<span ng-click="ui.openEditDialog(portrait)" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-portrait" aria-hidden="true">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
</div>
</fe-profile-portrait-categories>
</div>
<div class="media-body">
<h2 class="m-xs-bottom">
<span itemprop="name" class="ng-binding">
Asharam P.
</span>
</h2>
<div class="fe-profile-header-local-time">
<fe-profile-map profile-uid="vm.vpd.identity.uid" map-disabled="!vm.isShowMap" class="ng-isolate-scope"><span class="fe-map-trigger">
<ng-transclude><fe-profile-location-label location="vm.vpd.profile.location" icon-class="m-xs-right" label-class="w-700" view-type="vm.settings.locationViewType" class="ng-scope ng-isolate-scope"><span aria-hidden="true" class="glyphicon air-icon-location m-0-left vertical-align-middle m-xs-right" ng-class="iconClass"></span>
<span ng-class="labelClass" class="w-700">
<!-- ngIf: isCityVisible --><span data-ng-if="isCityVisible" class="ng-binding ng-scope">
<span itemprop="locality" class="text-capitalize ng-binding">chandrakona</span>,
</span><!-- end ngIf: isCityVisible -->
<!-- ngIf: isStateVisible -->
<!-- ngIf: isCountryVisible --><span itemprop="country-name" data-ng-if="isCountryVisible" class="ng-binding ng-scope">India</span><!-- end ngIf: isCountryVisible -->
</span>
</fe-profile-location-label></ng-transclude>
<!-- ngIf: !mapDisabled -->
</span>
</fe-profile-map><!-- ngIf: !vm.actor.isVisitor() --><span data-ng-if="!vm.actor.isVisitor()" class="cfe-local-time ng-scope">
<span class="d-none d-md-inline">-</span>
<span class="o-support-info text-muted" data-o-timezone-offset="19800" data-o-utimezone-offset="19800" data-o-append-text="local time" data-o-local-time="">12:40 am local time</span></span><!-- end ngIf: !vm.actor.isVisitor() -->
</div>
<div class="d-block d-sm-none m-xs-top p-lg-right">
<cfe-job-success vpd="vm.vpd" jss-value="0" class="ng-isolate-scope"><div class="d-none d-sm-block">
<!-- ngIf: $ctrl.showJSS && $ctrl.isJssScorePublic() -->
<!-- ngIf: $ctrl.isJssScorePrivate() -->
</div>
<div class="d-block d-sm-none">
<!-- ngIf: $ctrl.showJSS && $ctrl.isJssScorePublic() -->
<!-- ngIf: $ctrl.isJssScorePrivate() -->
</div>
<!-- ngIf: $ctrl.showTopRatedBadge() || $ctrl.showHipoBadge() -->
</cfe-job-success>
</div>
</div>
</div>
</div>
<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 p-0-left d-none d-sm-block">
<cfe-job-success vpd="vm.vpd" jss-value="0" class="ng-isolate-scope"><div class="d-none d-sm-block">
<!-- ngIf: $ctrl.showJSS && $ctrl.isJssScorePublic() -->
<!-- ngIf: $ctrl.isJssScorePrivate() -->
</div>
<div class="d-block d-sm-none">
<!-- ngIf: $ctrl.showJSS && $ctrl.isJssScorePublic() -->
<!-- ngIf: $ctrl.isJssScorePrivate() -->
</div>
<!-- ngIf: $ctrl.showTopRatedBadge() || $ctrl.showHipoBadge() -->
</cfe-job-success>
</div>
</div>
<!-- ngIf: vm.settings.enableServiceProfiles && vm.cfe.serviceProfiles.length -->
<div class="overlay-container">
<div class="up-active-container" up-highlight-editor="" up-edit-disabled="false">
<!-- ngIf: vm.vpd.profile.title && vm.vpd.profile.title.trim() --><h3 data-ng-if="vm.vpd.profile.title &amp;&amp; vm.vpd.profile.title.trim()" data-ng-click="vm.cfe.isServiceProfile ? vm.cfe.openEditTitleModal() : vm.openEditTitleModal()" class="m-0-top m-sm-bottom ng-scope">
<span class="up-active-context up-active-context-title fe-job-title inline-block m-lg-right">
<span data-ng-bind-html="vm.cfe.getProfileTitle() | htmlToPlaintext | linkRewrite:'/leaving?ref='" class="ng-binding">Expert Web Developer: Codeigniter / PHP / Wordpress  / Opencart</span>
<span class="btn btn-default btn-circle btn-sm up-active-control up-active-control-title" aria-hidden="true">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
</span>
</h3><!-- end ngIf: vm.vpd.profile.title && vm.vpd.profile.title.trim() -->
<!-- ngIf: (!vm.vpd.profile.title || !vm.vpd.profile.title.trim()) && vm.actor.isOwner() -->
</div>
<div class="up-active-container cfe-overview" up-highlight-editor="" up-edit-disabled="false">
<o-profile-overview words="80" class="d-none d-md-block ng-isolate-scope" placeholder="EXAMPLE: I have a Bachelor Degree in Computer Engineering from Cairo University and a Masters Degree in Computer Science from University College Cork. I am a Salesforce.com certified Professional and Zoho developer with 6+ years of experience.

I have worked on real-time projects in diverse business domains. I have good knowledge of Software Development Life Cycle (SDLC) and Agile Methodology which helps in building “High-Tech” solutions for end users and results in low maintenance costs. I offer all kinds of Salesforce services and have worked on various Zoho implementations.

I am flexible to project demands and shifting of priorities. I thrive in unfamiliar situations and enjoy opportunities to learn and gain exposure to new ideas and experiences. I am open and willing to learn whatever is necessary to accomplish my client's goals. Thank you, I look forward to working with you." overview="vm.cfe.getProfileDescription()" is-service-profile="vm.cfe.isServiceProfile" show-edit-icon="true" on-save-callback="vm.cfe.saveProfileOverview(overview)"><div>
<p itemprop="description" o-text-truncate="vm.overview | htmlToPlaintext" class="break text-pre-line up-active-context m-0-bottom m-lg-right ng-isolate-scope" ng-click="vm.openEditDialog()" on-click-more="vm.clickOverviewMoreLog()" o-words-threshold="80"><span><span ng-show="!open" class="ng-scope">Hi, my name is Asharam Pakhira.  
I have over 6+ years of experience in web development. My experience as a proficient web developer has helped me to acquire the trust of clients globally. My portfolio includes projects of successful clients for whom I have developed projects in my professional life. I believe that nothing is impossible in this world, if a person have faith in oneself and will to do something. Behind my success certainly there are absolute Positive...</span> <a href="javascript:;" class="oTruncateToggleText ng-scope" ng-click="toggleShow($event)" ng-show="!open">more</a><span ng-show="open" class="ng-scope ng-hide">Hi, my name is Asharam Pakhira.  
I have over 6+ years of experience in web development. My experience as a proficient web developer has helped me to acquire the trust of clients globally. My portfolio includes projects of successful clients for whom I have developed projects in my professional life. I believe that nothing is impossible in this world, if a person have faith in oneself and will to do something. Behind my success certainly there are absolute Positive Thinking, Experience, Creativeness, Dedication &amp; Punctuality.    
I like to interact with clients while working for any particular projects. This allows me to know about every minor detail about the project and thus help me to give a true shape to the client’s imagination. My first and foremost goal is to ensure full satisfaction to clients after project delivery. I’m straightforward, honest and transparent.   
I have a B.Tech (WBUT) (Information Technology) certification received from the Bankura Unnayani Institute of Engineering. Listed below are my major skills and experience. 
- Programming languages: PHP, JavaScript;
- Core web: HTML5, CSS3, XML, JSON, jQuery;
- PHP frameworks:  Codeigniter;
- CMS: Wordpress,Opencart
- OS: Linux, Windows;
- Git;
- MYSQL;
- Htaccess;
- 3rd party API integration (Stripe, PayPal, Authorize.net, Skrill, Google Maps, etc.) <a href="javascript:;" class="oTruncateToggleText" ng-click="toggleShow($event)">less</a>
                    </span></span></p>
<!-- ngIf: vm.showEditIcon --><span ng-if="vm.showEditIcon" class="btn btn-default btn-circle btn-sm up-active-control ng-scope" ng-click="vm.openEditDialog()" aria-hidden="true">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span><!-- end ngIf: vm.showEditIcon -->
</div>
</o-profile-overview>
<o-profile-overview words="25" class="d-block d-md-none ng-isolate-scope" placeholder="EXAMPLE: I have a Bachelor Degree in Computer Engineering from Cairo University and a Masters Degree in Computer Science from University College Cork. I am a Salesforce.com certified Professional and Zoho developer with 6+ years of experience.

I have worked on real-time projects in diverse business domains. I have good knowledge of Software Development Life Cycle (SDLC) and Agile Methodology which helps in building “High-Tech” solutions for end users and results in low maintenance costs. I offer all kinds of Salesforce services and have worked on various Zoho implementations.

I am flexible to project demands and shifting of priorities. I thrive in unfamiliar situations and enjoy opportunities to learn and gain exposure to new ideas and experiences. I am open and willing to learn whatever is necessary to accomplish my client's goals. Thank you, I look forward to working with you." overview="vm.cfe.getProfileDescription()" is-service-profile="vm.cfe.isServiceProfile" show-edit-icon="true" on-save-callback="vm.cfe.saveProfileOverview(overview)"><div>
<p itemprop="description" o-text-truncate="vm.overview | htmlToPlaintext" class="break text-pre-line up-active-context m-0-bottom m-lg-right ng-isolate-scope" ng-click="vm.openEditDialog()" on-click-more="vm.clickOverviewMoreLog()" o-words-threshold="25"><span><span ng-show="!open" class="ng-scope">Hi, my name is Asharam Pakhira.  
I have over 6+ years of experience in web development. My experience as a proficient web developer has...</span> <a href="javascript:;" class="oTruncateToggleText ng-scope" ng-click="toggleShow($event)" ng-show="!open">more</a><span ng-show="open" class="ng-scope ng-hide">Hi, my name is Asharam Pakhira.  
I have over 6+ years of experience in web development. My experience as a proficient web developer has helped me to acquire the trust of clients globally. My portfolio includes projects of successful clients for whom I have developed projects in my professional life. I believe that nothing is impossible in this world, if a person have faith in oneself and will to do something. Behind my success certainly there are absolute Positive Thinking, Experience, Creativeness, Dedication &amp; Punctuality.    
I like to interact with clients while working for any particular projects. This allows me to know about every minor detail about the project and thus help me to give a true shape to the client’s imagination. My first and foremost goal is to ensure full satisfaction to clients after project delivery. I’m straightforward, honest and transparent.   
I have a B.Tech (WBUT) (Information Technology) certification received from the Bankura Unnayani Institute of Engineering. Listed below are my major skills and experience. 
- Programming languages: PHP, JavaScript;
- Core web: HTML5, CSS3, XML, JSON, jQuery;
- PHP frameworks:  Codeigniter;
- CMS: Wordpress,Opencart
- OS: Linux, Windows;
- Git;
- MYSQL;
- Htaccess;
- 3rd party API integration (Stripe, PayPal, Authorize.net, Skrill, Google Maps, etc.) <a href="javascript:;" class="oTruncateToggleText" ng-click="toggleShow($event)">less</a>
                    </span></span></p>
<!-- ngIf: vm.showEditIcon --><span ng-if="vm.showEditIcon" class="btn btn-default btn-circle btn-sm up-active-control ng-scope" ng-click="vm.openEditDialog()" aria-hidden="true">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span><!-- end ngIf: vm.showEditIcon -->
</div>
</o-profile-overview>
<!-- ngIf: !vm.cfe.hasProfileDescription() -->
</div>
</div>
<!-- ngIf: (vm.actor.isOwner() || vm.vpd.video) && !vm.cfe.isServiceProfile --><div ng-if="(vm.actor.isOwner() || vm.vpd.video) &amp;&amp; !vm.cfe.isServiceProfile" class="up-active-container ng-scope">
<hr class="m-md-top m-sm-bottom">
<div class="m-sm-top-bottom d-flex justify-content-center">
<div class="up-active-context m-lg-right">
<!-- ngIf: !vm.vpd.video --><button class="btn btn-link btn-sm p-0-left-right m-0 ng-scope" ng-if="!vm.vpd.video" ng-click="vm.openAddVideoDialog()">
<span aria-hidden="true" class="fa fa-play-circle"></span> Place video
</button><!-- end ngIf: !vm.vpd.video -->
<!-- ngIf: vm.vpd.video -->
<!-- ngIf: vm.vpd.video -->
</div>
</div>
<hr class="m-sm-top m-lg-bottom">
</div><!-- end ngIf: (vm.actor.isOwner() || vm.vpd.video) && !vm.cfe.isServiceProfile -->
<div class="m-lg-top cfe-aggregates">
<ul class="list-inline m-0-bottom">
<li>
<!-- ngIf: !vm.settings.hideRate --><div data-ng-if="!vm.settings.hideRate" class="up-active-container ng-scope" up-highlight-editor="" up-edit-disabled="false">
<!-- ngIf: vm.isBodyRateSlotEnabled -->
<!-- ngIf: !vm.isBodyRateSlotEnabled --><div data-ng-if="!vm.isBodyRateSlotEnabled" class="ng-scope">
<!-- ngIf: !vm.vpd.profile.hideAgencyEarnings --><h3 data-ng-if="!vm.vpd.profile.hideAgencyEarnings" class="m-xs-bottom ng-scope">
<cfe-profile-rate data-fe-vpd="vm.vpd" data-service-profile="vm.cfe.selectedProfile" data-is-service-profile="vm.cfe.isServiceProfile" class="vertical-align-text-bottom ng-isolate-scope"><span data-ng-click="$ctrl.openEditRateDialog()" ng-class="{ 'up-active-context': !$ctrl.User.isACE() }" class="up-active-context">
<span itemprop="pricerange" class="ng-binding">
$12.00
</span>
<!-- ngIf: $ctrl.User.isOwner() && !$ctrl.User.isACE() --><span aria-hidden="true" data-ng-if="$ctrl.User.isOwner() &amp;&amp; !$ctrl.User.isACE()" class="btn btn-default btn-circle btn-sm up-active-control ng-scope">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span><!-- end ngIf: $ctrl.User.isOwner() && !$ctrl.User.isACE() -->
</span>
</cfe-profile-rate>
</h3><!-- end ngIf: !vm.vpd.profile.hideAgencyEarnings -->
<!-- ngIf: vm.vpd.profile.hideAgencyEarnings -->
</div><!-- end ngIf: !vm.isBodyRateSlotEnabled -->
</div><!-- end ngIf: !vm.settings.hideRate -->
<div data-ng-bind="(vm.jobType === 'Fixed') ? 'Bid' : 'Hourly rate'" class="text-muted ng-binding">Hourly rate</div>
</li>
<!-- ngIf: !vm.cfe.isServiceProfile && vm.hasJobStats && vm.isShowFlEarnings && vm.flEarnings --><li data-ng-if="!vm.cfe.isServiceProfile &amp;&amp; vm.hasJobStats &amp;&amp; vm.isShowFlEarnings &amp;&amp; vm.flEarnings" eo-tooltip="Amount earned in the past 6 months." eo-tooltip-trigger="mouseenter" eo-tooltip-placement="bottom" eo-tooltip-enable="vm.isShowFlRecentEarnings" eo-popover-size="lg" class="ng-scope" eo-tooltip-id="103">
<h3 class="m-xs-bottom">
<span itemprop="pricerange" class="ng-binding">
$60+
</span>
</h3>
<div class="text-muted ng-binding">
Total earned
</div>
</li><!-- end ngIf: !vm.cfe.isServiceProfile && vm.hasJobStats && vm.isShowFlEarnings && vm.flEarnings -->
<!-- ngIf: vm.cfe.isServiceProfile && vm.hasJobStats && vm.isShowFlEarnings && vm.cfe.getProfileEarnings() -->
<!-- ngIf: !vm.cfe.isServiceProfile && vm.isShowFlRecentEarnings && vm.vpd.stats.averageRecentEarnings -->
<!-- ngIf: !vm.cfe.isServiceProfile && vm.hasJobStats && (vm.isShowFlRecentEarnings ? vm.vpd.stats.totalJobsWorkedRecent : vm.vpd.stats.totalJobsWorked) --><li data-ng-if="!vm.cfe.isServiceProfile &amp;&amp; vm.hasJobStats &amp;&amp; (vm.isShowFlRecentEarnings ? vm.vpd.stats.totalJobsWorkedRecent : vm.vpd.stats.totalJobsWorked)" eo-tooltip="Number of jobs completed and in progress in the past six months." eo-tooltip-trigger="mouseenter" eo-tooltip-placement="bottom" eo-tooltip-enable="vm.isShowFlRecentEarnings" eo-popover-size="lg" class="ng-scope" eo-tooltip-id="105">
<h3 class="m-xs-bottom ng-binding">
2
</h3>
<div class="text-muted ng-binding">
<!-- ngIf: vm.isShowFlRecentEarnings -->
Jobs
</div>
</li><!-- end ngIf: !vm.cfe.isServiceProfile && vm.hasJobStats && (vm.isShowFlRecentEarnings ? vm.vpd.stats.totalJobsWorkedRecent : vm.vpd.stats.totalJobsWorked) -->
<!-- ngIf: vm.cfe.isServiceProfile && vm.hasJobStats && vm.cfe.getProfileJobs() -->
<!-- ngIf: !vm.cfe.isServiceProfile && !vm.isShowFlRecentEarnings && vm.hasJobStats && vm.actor.isFeature('ShowFreelancerTotalHoursUpdated') && vm.vpd.stats.totalHoursActual -->
<!-- ngIf: !vm.cfe.isServiceProfile && !vm.isShowFlRecentEarnings && vm.hasJobStats && !vm.actor.isFeature('ShowFreelancerTotalHoursUpdated') && vm.vpd.stats.totalHours -->
<!-- ngIf: vm.cfe.isServiceProfile && vm.hasJobStats && vm.cfe.getProfileHours() -->
</ul>
</div>
<hr class="m-0-bottom d-block d-md-none">
<div class="clearfix cfe-header-extras d-block d-md-none">
<ul class="list-inline m-0-bottom">
<!-- ngIf: vm.vpd.availability.uid --><li data-ng-if="vm.vpd.availability.uid" class="ng-scope">
<cfe-availability vpd="vm.vpd" settings="vm.settings" on-edit="vm.openEditAvailabilityDialog" class="ng-isolate-scope"><!-- ngIf: $ctrl.vpd.availability.uid --><div up-highlight-editor="" data-ng-if="$ctrl.vpd.availability.uid" class="p-0-top-bottom up-active-container ng-scope">
<!-- ngIf: $ctrl.hasJobStats() || $ctrl.isJssScorePrivate() || ($ctrl.showJSSTemplate() && $ctrl.isJssScorePublic()) --><hr class="m-lg-top-bottom d-none d-md-block ng-scope" ng-if="$ctrl.hasJobStats() || $ctrl.isJssScorePrivate() || ($ctrl.showJSSTemplate() &amp;&amp; $ctrl.isJssScorePublic())"><!-- end ngIf: $ctrl.hasJobStats() || $ctrl.isJssScorePrivate() || ($ctrl.showJSSTemplate() && $ctrl.isJssScorePublic()) -->
<h4 class="up-active-context display-inline-block p-lg-bottom m-0-bottom">
<span ng-click="$ctrl.onEdit()">Availability</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-availability" ng-click="$ctrl.onEdit()">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
</h4>
<up-dev-availability data-availability="$ctrl.vpd.availability" data-ng-click="$ctrl.onEdit()" class="up-active-context m-xs-top ng-isolate-scope"><!-- ngIf: vm.availability --><div data-ng-if="vm.availability" class="ng-scope">
<!-- ngIf: vm.availability.capacity --><div data-ng-if="vm.availability.capacity" class="m-0-top-bottom ng-scope">
<strong>Available</strong>
<span class="d-inline d-md-none ng-binding">- More than 30 hrs/week</span>
</div><!-- end ngIf: vm.availability.capacity -->
<!-- ngIf: vm.availability.capacity --><div data-ng-if="vm.availability.capacity" class="m-0-top-bottom d-none d-md-block ng-scope">
<span class="ng-binding">More than 30 hrs/week</span>
</div><!-- end ngIf: vm.availability.capacity -->
<!-- ngIf: !vm.availability.capacity -->
</div><!-- end ngIf: vm.availability -->
</up-dev-availability>
<!-- ngIf: $ctrl.vpd.isResponsiveStateShorten() && $ctrl.vpd.getResponsiveTimeFF() -->
<!-- ngIf: !$ctrl.vpd.isResponsiveStateShorten() && $ctrl.vpd.getResponsiveTime() -->
</div><!-- end ngIf: $ctrl.vpd.availability.uid -->
</cfe-availability>
</li><!-- end ngIf: vm.vpd.availability.uid -->
<!-- ngIf: vm.vpd.profile.idVerified || vm.vpd.profile.phoneVerified -->
<li class="languages-container">
<o-profile-languages items="vm.vpd.languages" is-owner="vm.actor.isOwner()" class="ng-isolate-scope"><!-- ngIf: vm.isOwner || vm.items.length --><div class="p-0-top-bottom up-active-container ng-scope" data-ng-if="vm.isOwner || vm.items.length">
<hr class="m-lg-top-bottom d-none d-md-block">
<h4 class="up-active-context p-lg-bottom m-0-bottom display-inline-block">
Languages
<span aria-hidden="true" ng-click="vm.openLanguageManageDialog(vm.items)" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-availability">
<span aria-hidden="true" class="fa fa-plus"></span>
</span>
</h4>
<!-- ngIf: vm.items --><ul class="list-unstyled ng-scope" data-ng-if="vm.items">
<!-- ngRepeat: item in vm.items | orderBy:vm.languageSorter --><li class="up-active-context up-active-context-language p-lg-bottom m-0-top-bottom clearfix ng-scope" data-ng-repeat="item in vm.items | orderBy:vm.languageSorter">
<div class="editable-lang">
<span ng-click="vm.openLanguageManageDialog(vm.items, item)" class="float-start ng-binding">
English:
<small class="text-muted ng-binding">Fluent</small>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language" ng-click="vm.openLanguageManageDialog(vm.items, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<!-- ngIf: item.language.iso639Code != 'en' -->
<!-- ngIf: item.verified -->
</div>
</li><!-- end ngRepeat: item in vm.items | orderBy:vm.languageSorter --><li class="up-active-context up-active-context-language p-lg-bottom m-0-top-bottom clearfix ng-scope" data-ng-repeat="item in vm.items | orderBy:vm.languageSorter">
<div class="editable-lang">
<span ng-click="vm.openLanguageManageDialog(vm.items, item)" class="float-start ng-binding">
Bengali:
<small class="text-muted ng-binding">Fluent</small>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language" ng-click="vm.openLanguageManageDialog(vm.items, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<!-- ngIf: item.language.iso639Code != 'en' --><span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language up-active-control-delete ng-scope" ng-if="item.language.iso639Code != 'en'" data-ng-click="vm.openLanguageDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span><!-- end ngIf: item.language.iso639Code != 'en' -->
<!-- ngIf: item.verified -->
</div>
</li><!-- end ngRepeat: item in vm.items | orderBy:vm.languageSorter --><li class="up-active-context up-active-context-language p-lg-bottom m-0-top-bottom clearfix ng-scope" data-ng-repeat="item in vm.items | orderBy:vm.languageSorter">
<div class="editable-lang">
<span ng-click="vm.openLanguageManageDialog(vm.items, item)" class="float-start ng-binding">
Hindi:
<small class="text-muted ng-binding">Fluent</small>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language" ng-click="vm.openLanguageManageDialog(vm.items, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<!-- ngIf: item.language.iso639Code != 'en' --><span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language up-active-control-delete ng-scope" ng-if="item.language.iso639Code != 'en'" data-ng-click="vm.openLanguageDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span><!-- end ngIf: item.language.iso639Code != 'en' -->
<!-- ngIf: item.verified -->
</div>
</li><!-- end ngRepeat: item in vm.items | orderBy:vm.languageSorter -->
</ul><!-- end ngIf: vm.items -->
</div><!-- end ngIf: vm.isOwner || vm.items.length -->
</o-profile-languages>
</li>
</ul>
<!-- ngIf: vm.vpd.agencies.length -->
<cfe-profile-associations agencies="vm.vpd.agencies" show-inside-header="true" class="ng-isolate-scope"><!-- ngIf: $ctrl.agencies.length -->
</cfe-profile-associations>
</div>
<!-- ngIf: !vm.settings.isOwner && vm.settings.isShowShareBanner -->
<!-- ngIf: blocked --></div>
</div><!-- end ngIf: vm.vpd -->
</fe-profile-header><!-- end ngIf: !isProfilePrivate -->
<!-- ngIf: isSlotFilled('feBodyTopSlot') -->
<!-- ngIf: actor.isVisitor() -->
<!-- ngIf: actor.isClient() && !actor.isOwner() && !settings.areClientControlsHidden -->
<!-- ngIf: !isProfilePrivate --><div data-ng-if="!isProfilePrivate" data-ng-class="{'up-active-editor': actor.isOwner()}" class="ng-scope up-active-editor">
<!-- ngIf: settings.enableServiceProfiles -->
<!-- ngIf: vpd.isWorkHistorySectionVisible() && !settings.enableServiceProfiles --><o-profile-assignments id="oProfileAssignments" ng-if="vpd.isWorkHistorySectionVisible() &amp;&amp; !settings.enableServiceProfiles" assignments-ended="vpd.assignmentsEnded" assignments-in-progress="vpd.assignmentsInProgress" assignments-selected="vpd.getSelectedAssignments()" recommended-reasons-enabled="vpd.isRecommendReasonsEnabled()" freelancer-uid="424294450345394176" is-visitor="actor.isVisitor()" hide-earning="!vpd.profile.exposeBillings || vpd.profile.hideAgencyEarnings" hide-billing="vpd.profile.exposeBillings" is-best-match-work-explanation="settings.isBestMatchWorkExplanation" class="ng-scope ng-isolate-scope"><div data-ng-class="{'air-card': !vm.isEditMode}" class="p-0-top-bottom m-0-right m-0-left-right-md m-0-left-right-xl air-card">
<!-- ngIf: !vm.isEditMode --><header data-ng-if="!vm.isEditMode" class="ng-scope">
<div class="clearfix">
<!-- ngIf: vm.isOwner && vm.isServiceProfile -->
<!-- ngInclude: vm.assignmentSortCriteriaTmpl --><div class="cfe-assignments-sort-a ng-scope" data-ng-include="vm.assignmentSortCriteriaTmpl"><!-- ngIf: vm.isOwner && vm.isServiceProfile -->
<div class="float-end ng-scope">
<!-- ngIf: !vm.isPTCFilterVisible --><div class="float-end cfe-assignments-sort-select ng-pristine ng-untouched ng-valid ng-scope ng-isolate-scope ng-not-empty" ng-if="!vm.isPTCFilterVisible" eo-select="" eo-select-options="item.name as item.title for item in vm.sortCriteria" eo-select-class="o-small-select" eo-select-size="sm" data-ng-hide="!vm.assignmentsEndedTotalCount || (vm.isRecommendedOpeningsSelected &amp;&amp; !vm.assignmentsEnded.filterClientAssignments &amp;&amp; !vm.isServiceProfile)" data-ng-change="vm.doSortBy()" data-ng-model="vm.sortBy"><div class="btn-group dropdown btn-group-sm" eo-dropdown="" is-open="false"> <button class="btn btn-default dropdown-toggle o-small-select" type="button" eo-dropdown-toggle="" ng-disabled="ngDisabled" aria-haspopup="true" aria-expanded="false"> <span class="eo-select-label ellipsis ng-binding" ng-bind="label">Newest first</span> <span aria-hidden="true" class="caret fa fa-angle-down"></span> </button> <ul class="eo-dropdown-menu" role="menu" eo-dropdown-menu=""> <!-- ngRepeat: option in selectOptions track by option.id --><li ng-repeat="option in selectOptions track by option.id" class="ng-scope"> <a href="javascript:" ng-class="{disabled: option.disabled, active: option.isSelected}" ng-bind-html="option.label" ng-click="selectionChanged(option.id)" class="ng-binding active">Newest first</a> </li><!-- end ngRepeat: option in selectOptions track by option.id --><li ng-repeat="option in selectOptions track by option.id" class="ng-scope"> <a href="javascript:" ng-class="{disabled: option.disabled, active: option.isSelected}" ng-bind-html="option.label" ng-click="selectionChanged(option.id)" class="ng-binding">Highest rated</a> </li><!-- end ngRepeat: option in selectOptions track by option.id --><li ng-repeat="option in selectOptions track by option.id" class="ng-scope"> <a href="javascript:" ng-class="{disabled: option.disabled, active: option.isSelected}" ng-bind-html="option.label" ng-click="selectionChanged(option.id)" class="ng-binding">Lowest rated</a> </li><!-- end ngRepeat: option in selectOptions track by option.id --><li ng-repeat="option in selectOptions track by option.id" class="ng-scope"> <a href="javascript:" ng-class="{disabled: option.disabled, active: option.isSelected}" ng-bind-html="option.label" ng-click="selectionChanged(option.id)" class="ng-binding">Largest projects</a> </li><!-- end ngRepeat: option in selectOptions track by option.id --> </ul> </div> </div><!-- end ngIf: !vm.isPTCFilterVisible -->
<!-- ngIf: vm.isPTCFilterVisible -->
</div>
<!-- ngIf: vm.isPTCFilterVisible -->
</div>
<h2 class="cfe-assignments-title m-0-top-bottom" ng-class="{'m-xs-top': vm.isPTCFilterVisible}">
<span>
Work history and feedback
</span>
<!-- ngIf: vm.subTitle -->
</h2>
</div>
</header><!-- end ngIf: !vm.isEditMode -->
<!-- ngIf: vm.isEditMode -->
<!-- ngIf: !vm.itemsLoading && vm.errorLoading -->
<!-- ngIf: vm.isEditAlertVisible -->
<section class="p-lg-top responsive">
<!-- ngIf: (vm.assignmentsEndedTotalCount || vm.isPTCFilterVisible) --><!-- ngInclude: vm.assignmentSortCriteriaTmpl --><div data-ng-if="(vm.assignmentsEndedTotalCount || vm.isPTCFilterVisible)" class="cfe-assignments-sort-b clearfix ng-scope" data-ng-include="vm.assignmentSortCriteriaTmpl"><!-- ngIf: vm.isOwner && vm.isServiceProfile -->

<!-- ngIf: vm.isPTCFilterVisible -->
</div><!-- end ngIf: (vm.assignmentsEndedTotalCount || vm.isPTCFilterVisible) -->
<div data-eo-block="" class="assigment-list-content ng-scope">
<ng-transclude>
</ng-transclude>
<!-- ngIf: vm.assignmentsSelected.length && !vm.assignmentsEnded.filterClientAssignments -->
<!-- ngIf: !vm.assignmentsSelected.length || vm.assignmentsEnded.filterClientAssignments --><div class="m-sm-bottom ng-scope" data-ng-if="!vm.assignmentsSelected.length || vm.assignmentsEnded.filterClientAssignments">
<!-- ngInclude: vm.assignmentListTmpl --><div data-ng-include="vm.assignmentListTmpl" class="ng-scope"><!-- ngIf: vm.isPTCFilterVisible && vm.assignmentsEnded.filterClientAssignments && !vm.hasAssignments() -->
<div data-ng-show="vm.hasManyItemsInProgress &amp;&amp; !vm.itemsLoading" class="ng-scope ng-hide">
<hr class="m-md-bottom m-0-top">
<a href="javascript:" class="vertical-align-middle ng-binding" data-ng-click="vm.toggleCollapse()">
<span aria-hidden="true" class="fa fa-angle-down" data-ng-class="{'air-icon-arrow-expand': vm.isCollapsed, 'air-icon-arrow-collapse': !vm.isCollapsed}"></span>
0 jobs in progress
</a>
<hr class="m-md-top m-lg-bottom">
</div>
<!-- ngIf: vm.items.length && !vm.isEditMode --><ul class="list-unstyled ng-scope" data-ng-if="vm.items.length &amp;&amp; !vm.isEditMode">
<!-- ngRepeat: item in vm.items as assignmentsListAlias track by $index --><li data-ng-repeat="item in vm.items as assignmentsListAlias track by $index" class="ng-scope">
<!-- ngInclude: vm.assignmentItemTmpl --><div ng-include="vm.assignmentItemTmpl" class="ng-scope"><div class="row ng-scope">
<!-- ngIf: vm.isEditMode -->
<div data-ng-class="{'col-sm-12': !vm.isEditMode, 'col-sm-11 col-xs-11': vm.isEditMode}" class="col-sm-12">
<div class="row">
<div class="col-sm-6">
<h4 class="m-0-top m-xs-bottom">
<!-- ngIf: !vm.isVisitor && !vm.isEditMode --><a href="javascript:" data-ng-click="vm.openAssignmentDetailsDialog($index, assignmentsListAlias)" data-ng-if="!vm.isVisitor &amp;&amp; !vm.isEditMode" class="ng-binding ng-scope">
Update Wordpress Homepage
</a><!-- end ngIf: !vm.isVisitor && !vm.isEditMode -->
<!-- ngIf: vm.isVisitor || vm.isEditMode -->
</h4>
<ul class="list-inline m-0-left">
<!-- ngIf: item.feedback.score && item.feedback.commentIsPublic --><li class="m-xs-bottom p-0-left ng-scope" data-ng-if="item.feedback.score &amp;&amp; item.feedback.commentIsPublic">
<div eo-rating="" stars="5" read-only="true" ng-model="item.feedback.stars" star-radius="6" star-fill-color="#5bbc2e" container-color="#FFFFFF" class="ng-pristine ng-untouched ng-valid ng-isolate-scope ng-not-empty"><div class="stars" ng-mousemove="changeRating($event)" ng-mouseleave="leaveRating()" style="visibility: visible;"> <canvas ng-click="secureNewRating()" class="star ng-scope" height="12" width="60"></canvas></div></div>
</li><!-- end ngIf: item.feedback.score && item.feedback.commentIsPublic -->
<!-- ngIf: item.feedback.score && item.feedback.commentIsPublic --><li class="m-xs-bottom p-0-left ng-scope" data-ng-if="item.feedback.score &amp;&amp; item.feedback.commentIsPublic">
<strong class="ng-binding">5.00</strong>
</li><!-- end ngIf: item.feedback.score && item.feedback.commentIsPublic -->
<li class="m-xs-bottom p-0-left">
<small class="text-muted ng-binding">Oct 2018</small>
</li>
</ul>
<!-- ngIf: !item.endedOn -->
<!-- ngIf: item.endedOn && !item.feedback && item.isOpeningVisible -->
<!-- ngIf: item.endedOn && !item.feedback && !item.isOpeningVisible -->
<!-- ngIf: item.endedOn && item.feedback && !item.feedback.commentIsPublic -->
<!-- ngIf: item.endedOn && item.feedback && item.feedback.commentIsPublic && (item.feedback.comment || item.feedback.response) --><div data-ng-if="item.endedOn &amp;&amp; item.feedback &amp;&amp; item.feedback.commentIsPublic &amp;&amp; (item.feedback.comment || item.feedback.response)" class="ng-scope">
<!-- ngIf: !vm.isEditMode && item.feedback.comment --><em data-ng-if="!vm.isEditMode &amp;&amp; item.feedback.comment" class="break text-pre-line ng-binding ng-scope">Working with this freelancer has been a very rewarding experience. He is clear about how to help even when I don't know what to do and he is honest about timelines. If you want your work DONE ON TIME with the HIGHEST QUALITY. HIRE HIM</em><!-- end ngIf: !vm.isEditMode && item.feedback.comment -->
<!-- ngIf: vm.isEditMode && item.feedback.comment -->
<!-- ngIf: item.feedback.response -->
</div><!-- end ngIf: item.endedOn && item.feedback && item.feedback.commentIsPublic && (item.feedback.comment || item.feedback.response) -->
</div>
<div class="col-sm-6 text-right cfe-assignment-stats">
<div class="d-block d-sm-none text-left">
<div class="row">
<!-- ngIf: !vm.hideEarning && item.totalCharges.amount --><div class="col-xs-4 m-xs-bottom ng-scope" data-ng-if=" !vm.hideEarning &amp;&amp; item.totalCharges.amount">
<strong class="ng-binding">$35.00</strong>
</div><!-- end ngIf: !vm.hideEarning && item.totalCharges.amount -->
<!-- ngIf: item.type != 1 && !vm.hideEarning && item.hourlyRate.amount -->
<!-- ngIf: vm.hideEarning -->
<!-- ngIf: item.type == 1 --><div class="col-xs-4 ng-scope" data-ng-if="item.type == 1">
<small class="text-muted nowrap">Fixed-price</small>
</div><!-- end ngIf: item.type == 1 -->
<!-- ngIf: item.totalHours -->
</div>
</div>
<div class="d-none d-sm-block">
<!-- ngIf: !vm.hideEarning && item.totalCharges.amount --><div class="m-xs-bottom ng-scope" data-ng-if=" !vm.hideEarning &amp;&amp; item.totalCharges.amount">
<strong class="ng-binding">$35.00</strong>
</div><!-- end ngIf: !vm.hideEarning && item.totalCharges.amount -->
<!-- ngIf: item.type != 1 && !vm.hideEarning && item.hourlyRate.amount -->
<!-- ngIf: vm.hideEarning -->
<!-- ngIf: item.type == 1 --><div class="m-xs-bottom ng-scope" data-ng-if="item.type == 1">
<small class="text-muted">Fixed-price</small>
</div><!-- end ngIf: item.type == 1 -->
<!-- ngIf: item.totalHours -->
</div>
</div>
<!-- ngIf: vm.isEditMode -->
<div class="col-sm-12">
<span class="m-sm-top d-block d-md-none"></span>
<!-- ngIf: item.linkedPortfolio && vm.exposePortfolioItems(item.linkedPortfolio) && !vm.isVisitor -->
</div>
<!-- ngIf: item.linkedPortfolio && vm.exposePortfolioItems(item.linkedPortfolio) && !vm.isVisitor -->
</div>
</div>
</div>
</div>
<!-- ngIf: !$last --><hr class="cfe-assignment-hr m-lg-top m-0-bottom ng-scope" data-ng-if="!$last"><!-- end ngIf: !$last -->
</li><!-- end ngRepeat: item in vm.items as assignmentsListAlias track by $index --><li data-ng-repeat="item in vm.items as assignmentsListAlias track by $index" class="ng-scope">
<!-- ngInclude: vm.assignmentItemTmpl --><div ng-include="vm.assignmentItemTmpl" class="ng-scope"><div class="row ng-scope">
<!-- ngIf: vm.isEditMode -->
<div data-ng-class="{'col-sm-12': !vm.isEditMode, 'col-sm-11 col-xs-11': vm.isEditMode}" class="col-sm-12">
<div class="row">
<div class="col-sm-6">
<h4 class="m-0-top m-xs-bottom">
<!-- ngIf: !vm.isVisitor && !vm.isEditMode --><a href="javascript:" data-ng-click="vm.openAssignmentDetailsDialog($index, assignmentsListAlias)" data-ng-if="!vm.isVisitor &amp;&amp; !vm.isEditMode" class="ng-binding ng-scope">
Web Front-End refactoring
</a><!-- end ngIf: !vm.isVisitor && !vm.isEditMode -->
<!-- ngIf: vm.isVisitor || vm.isEditMode -->
</h4>
<ul class="list-inline m-0-left">
<!-- ngIf: item.feedback.score && item.feedback.commentIsPublic --><li class="m-xs-bottom p-0-left ng-scope" data-ng-if="item.feedback.score &amp;&amp; item.feedback.commentIsPublic">
<div eo-rating="" stars="5" read-only="true" ng-model="item.feedback.stars" star-radius="6" star-fill-color="#5bbc2e" container-color="#FFFFFF" class="ng-pristine ng-untouched ng-valid ng-isolate-scope ng-not-empty"><div class="stars" ng-mousemove="changeRating($event)" ng-mouseleave="leaveRating()" style="visibility: visible;"> <canvas ng-click="secureNewRating()" class="star ng-scope" height="12" width="60"></canvas></div></div>
</li><!-- end ngIf: item.feedback.score && item.feedback.commentIsPublic -->
<!-- ngIf: item.feedback.score && item.feedback.commentIsPublic --><li class="m-xs-bottom p-0-left ng-scope" data-ng-if="item.feedback.score &amp;&amp; item.feedback.commentIsPublic">
<strong class="ng-binding">4.80</strong>
</li><!-- end ngIf: item.feedback.score && item.feedback.commentIsPublic -->
<li class="m-xs-bottom p-0-left">
<small class="text-muted ng-binding">Sep 2018</small>
</li>
</ul>
<!-- ngIf: !item.endedOn -->
<!-- ngIf: item.endedOn && !item.feedback && item.isOpeningVisible -->
<!-- ngIf: item.endedOn && !item.feedback && !item.isOpeningVisible -->
<!-- ngIf: item.endedOn && item.feedback && !item.feedback.commentIsPublic -->
<!-- ngIf: item.endedOn && item.feedback && item.feedback.commentIsPublic && (item.feedback.comment || item.feedback.response) --><div data-ng-if="item.endedOn &amp;&amp; item.feedback &amp;&amp; item.feedback.commentIsPublic &amp;&amp; (item.feedback.comment || item.feedback.response)" class="ng-scope">
<!-- ngIf: !vm.isEditMode && item.feedback.comment --><em data-ng-if="!vm.isEditMode &amp;&amp; item.feedback.comment" class="break text-pre-line ng-binding ng-scope">The job was done very quickly with adequate quality. Thank You.</em><!-- end ngIf: !vm.isEditMode && item.feedback.comment -->
<!-- ngIf: vm.isEditMode && item.feedback.comment -->
<!-- ngIf: item.feedback.response --><div data-ng-if="item.feedback.response" class="ng-scope">
<div class="m-sm-top m-xs-bottom">
<strong>Freelancer's Response</strong>
</div>
<!-- ngIf: !vm.isEditMode --><em ng-if="!vm.isEditMode" class="break text-pre-line ng-binding ng-scope">Thanks for your feedback . will wait for your next contract .
Thanks you.</em><!-- end ngIf: !vm.isEditMode -->
<!-- ngIf: vm.isEditMode -->
</div><!-- end ngIf: item.feedback.response -->
</div><!-- end ngIf: item.endedOn && item.feedback && item.feedback.commentIsPublic && (item.feedback.comment || item.feedback.response) -->
</div>
<div class="col-sm-6 text-right cfe-assignment-stats">
<div class="d-block d-sm-none text-left">
<div class="row">
<!-- ngIf: !vm.hideEarning && item.totalCharges.amount --><div class="col-xs-4 m-xs-bottom ng-scope" data-ng-if=" !vm.hideEarning &amp;&amp; item.totalCharges.amount">
<strong class="ng-binding">$30.00</strong>
</div><!-- end ngIf: !vm.hideEarning && item.totalCharges.amount -->
<!-- ngIf: item.type != 1 && !vm.hideEarning && item.hourlyRate.amount -->
<!-- ngIf: vm.hideEarning -->
<!-- ngIf: item.type == 1 --><div class="col-xs-4 ng-scope" data-ng-if="item.type == 1">
<small class="text-muted nowrap">Fixed-price</small>
</div><!-- end ngIf: item.type == 1 -->
<!-- ngIf: item.totalHours -->
</div>
</div>
<div class="d-none d-sm-block">
<!-- ngIf: !vm.hideEarning && item.totalCharges.amount --><div class="m-xs-bottom ng-scope" data-ng-if=" !vm.hideEarning &amp;&amp; item.totalCharges.amount">
<strong class="ng-binding">$30.00</strong>
</div><!-- end ngIf: !vm.hideEarning && item.totalCharges.amount -->
<!-- ngIf: item.type != 1 && !vm.hideEarning && item.hourlyRate.amount -->
<!-- ngIf: vm.hideEarning -->
<!-- ngIf: item.type == 1 --><div class="m-xs-bottom ng-scope" data-ng-if="item.type == 1">
<small class="text-muted">Fixed-price</small>
</div><!-- end ngIf: item.type == 1 -->
<!-- ngIf: item.totalHours -->
</div>
</div>
<!-- ngIf: vm.isEditMode -->
<div class="col-sm-12">
<span class="m-sm-top d-block d-md-none"></span>
<!-- ngIf: item.linkedPortfolio && vm.exposePortfolioItems(item.linkedPortfolio) && !vm.isVisitor -->
</div>
<!-- ngIf: item.linkedPortfolio && vm.exposePortfolioItems(item.linkedPortfolio) && !vm.isVisitor -->
</div>
</div>
</div>
</div>
<!-- ngIf: !$last -->
</li><!-- end ngRepeat: item in vm.items as assignmentsListAlias track by $index -->
<!-- ngIf: vm.countMoreItemsToShow() -->
</ul><!-- end ngIf: vm.items.length && !vm.isEditMode -->
<!-- ngIf: vm.items.length && vm.isEditMode -->
</div>
</div><!-- end ngIf: !vm.assignmentsSelected.length || vm.assignmentsEnded.filterClientAssignments -->
<!-- ngIf: blocked --></div>
</section>
</div>
</o-profile-assignments><!-- end ngIf: vpd.isWorkHistorySectionVisible() && !settings.enableServiceProfiles -->
<!-- ngIf: actor.isVisitor() && (vpd.assignments.length < vpd.stats.totalJobsWorked) && !settings.enableServiceProfiles -->
<!-- ngIf: settings.enableServiceProfiles -->
<!-- ngIf: !actor.isVisitor() && !settings.enableServiceProfiles --><o-profile-portfolio items="vpd.portfolios" is-owner="actor.isOwner()" is-client="actor.isClient()" data-ng-if="!actor.isVisitor() &amp;&amp; !settings.enableServiceProfiles" class="ng-scope ng-isolate-scope"><div data-ng-show="vm.filteredPortfolios.length || vm.isEditable()" class="">
<div class="air-card m-0-left-right-md m-0-left-right-xl p-0-top-bottom">
<header>
<!-- ngIf: vm.isEditable() --><span aria-hidden="true" ng-if="vm.isEditable()" ng-click="vm.openAddDialog()" class="btn btn-default btn-circle btn-sm float-end m-md-left ng-scope">
<span aria-hidden="true" class="fa fa-plus"></span>
</span><!-- end ngIf: vm.isEditable() -->
<div class="float-end o-portfolio-filter cfe-portfolio-filter-a ng-pristine ng-untouched ng-valid ng-isolate-scope ng-empty" eo-select="" eo-select-options="category for category in vm.getPortfolioSubcategories()" eo-select-size="sm" data-ng-model="vm.portfolioFilter" data-ng-show="vm.showPortfolioSubcategoriesFilter" data-ng-change="vm.applyPortfolioFilter(vm.portfolioFilter)"><div class="btn-group dropdown btn-group-sm" eo-dropdown="" is-open="false"> <button class="btn btn-default dropdown-toggle" type="button" eo-dropdown-toggle="" ng-disabled="ngDisabled" aria-haspopup="true" aria-expanded="false"> <span class="eo-select-label ellipsis ng-binding" ng-bind="label">All categories</span> <span aria-hidden="true" class="caret fa fa-angle-down"></span> </button> <ul class="eo-dropdown-menu" role="menu" eo-dropdown-menu=""> <!-- ngRepeat: option in selectOptions track by option.id --><li ng-repeat="option in selectOptions track by option.id" class="ng-scope"> <a href="javascript:" ng-class="{disabled: option.disabled, active: option.isSelected}" ng-bind-html="option.label" ng-click="selectionChanged(option.id)" class="ng-binding active">All categories</a> </li><!-- end ngRepeat: option in selectOptions track by option.id --><li ng-repeat="option in selectOptions track by option.id" class="ng-scope"> <a href="javascript:" ng-class="{disabled: option.disabled, active: option.isSelected}" ng-bind-html="option.label" ng-click="selectionChanged(option.id)" class="ng-binding">Web Development</a> </li><!-- end ngRepeat: option in selectOptions track by option.id --><li ng-repeat="option in selectOptions track by option.id" class="ng-scope"> <a href="javascript:" ng-class="{disabled: option.disabled, active: option.isSelected}" ng-bind-html="option.label" ng-click="selectionChanged(option.id)" class="ng-binding">Ecommerce Development</a> </li><!-- end ngRepeat: option in selectOptions track by option.id --> </ul> </div> </div>
<h2 class="m-0-top-bottom">
Portfolio
</h2>
</header>
<ng-transclude>
</ng-transclude>
<section class="p-lg-top">


<!-- ngIf: vm.filteredPortfolios.length --><div data-ng-if="vm.filteredPortfolios.length" class="ng-scope">
<div class="row thumb-grid">
<!-- ngRepeat: portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage --><div class="m-lg-bottom ng-binding ng-scope col-md-6" data-ng-class="{1: 'col-md-12', 2: 'col-md-6', 3: 'col-md-4' }[vm.columns]" data-ng-repeat="portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage">
<up-project-thumb project="portfolio" ng-click="vm.openPortfolioDetails(vm.filteredPortfolios, (vm.portfolioPager.currentPage - 1) * vm.portfolioPager.itemsPerPage + $index)" delete-action="vm.openDeleteDialog(portfolio)" reorder-action="vm.openReorderDialog(portfolio)" hide-controls="false" edit-action="vm.openEditDialog(portfolio)" class="ng-isolate-scope"><div class="up-cursor-pointer up-active-container">
<div class="thumbnail thumb-block m-md-bottom p-0" ng-style="{'background-image': project.thumbnail &amp;&amp; 'url(\'/att/download/portfolio/persons/uid/424294450345394176/profile/projects/files/1040609631132229632\')' || 'none'}" style="background-image: url(&quot;/att/download/portfolio/persons/uid/424294450345394176/profile/projects/files/1040609631132229632&quot;);">
<!-- ngIf: !vm.hideControls --><div class="thumbnail-controls ng-scope" ng-if="!vm.hideControls">
<article>
<span aria-hidden="true" ng-click="vm.reorder($event)" class="btn btn-default btn-circle btn-sm m-sm-right d-none d-md-inline-block">
<span aria-hidden="true" class="fa fa-sort-alpha-up"></span>
</span>
<span aria-hidden="true" ng-click="vm.edit($event)" class="btn btn-default btn-circle btn-sm m-sm-right">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" ng-click="vm.remove($event)" class="btn btn-default btn-circle btn-sm">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</article>
</div><!-- end ngIf: !vm.hideControls -->
<!-- ngIf: project.thumbnail && project.linkedAssignment && ((project.linkedAssignment.state=='pendingApproval') || (project.linkedAssignment.state=='denied')) -->
<!-- ngIf: !project.thumbnail -->
</div>
<p class="text-truncate up-active-context m-0 project-title"><strong class="ng-binding">Tea Raja</strong></p>
</div>
</up-project-thumb>

</div><!-- end ngRepeat: portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage --><div class="m-lg-bottom ng-binding ng-scope col-md-6" data-ng-class="{1: 'col-md-12', 2: 'col-md-6', 3: 'col-md-4' }[vm.columns]" data-ng-repeat="portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage">
<up-project-thumb project="portfolio" ng-click="vm.openPortfolioDetails(vm.filteredPortfolios, (vm.portfolioPager.currentPage - 1) * vm.portfolioPager.itemsPerPage + $index)" delete-action="vm.openDeleteDialog(portfolio)" reorder-action="vm.openReorderDialog(portfolio)" hide-controls="false" edit-action="vm.openEditDialog(portfolio)" class="ng-isolate-scope"><div class="up-cursor-pointer up-active-container">
<div class="thumbnail thumb-block m-md-bottom p-0" ng-style="{'background-image': project.thumbnail &amp;&amp; 'url(\'\')' || 'none'}" style="background-image: none;">
<!-- ngIf: !vm.hideControls --><div class="thumbnail-controls ng-scope" ng-if="!vm.hideControls">
<article>
<span aria-hidden="true" ng-click="vm.reorder($event)" class="btn btn-default btn-circle btn-sm m-sm-right d-none d-md-inline-block">
<span aria-hidden="true" class="fa fa-sort-alpha-up"></span>
</span>
<span aria-hidden="true" ng-click="vm.edit($event)" class="btn btn-default btn-circle btn-sm m-sm-right">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" ng-click="vm.remove($event)" class="btn btn-default btn-circle btn-sm">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</article>
</div><!-- end ngIf: !vm.hideControls -->
<!-- ngIf: project.thumbnail && project.linkedAssignment && ((project.linkedAssignment.state=='pendingApproval') || (project.linkedAssignment.state=='denied')) -->
<!-- ngIf: !project.thumbnail --><div class="p-md-left-right ng-scope" ng-if="!project.thumbnail">
<h4 class="m-md-top text-truncate">
<a href="" class="ng-binding">Just Tutors</a>
</h4>
<!-- ngIf: project.linkedAssignment && project.linkedAssignment.state == 'pendingApproval' -->
<!-- ngIf: project.linkedAssignment && project.linkedAssignment.state == 'denied' -->
<p class="small m-sm-bottom cfe-project-description ng-isolate-scope" eo-truncation="project.description" eo-chars-threshold="125" eo-more-label="​"><span ng-show="!isOpen" class="ng-binding"> Private tutor agency <!-- ngIf: truncationMade --> </span> <span ng-show="isOpen" class="ng-binding ng-hide"> Private tutor agency <a ng-click="toggle()" class="eo-truncate-toggle-text eo-truncate-toggle-text-open ng-binding" href="javascript:void(0)">less</a> </span> </p>
<strong class="thumb-footer small ng-binding">
Web Development
<!-- ngIf: project.completedOn --><span data-ng-if="project.completedOn" class="ng-binding ng-scope"> | Feb 8, 2014</span><!-- end ngIf: project.completedOn -->
</strong>
</div><!-- end ngIf: !project.thumbnail -->
</div>
<p class="text-truncate up-active-context m-0 project-title"><strong class="ng-binding">Just Tutors</strong></p>
</div>
</up-project-thumb>

</div><!-- end ngRepeat: portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage --><div class="m-lg-bottom ng-binding ng-scope col-md-6" data-ng-class="{1: 'col-md-12', 2: 'col-md-6', 3: 'col-md-4' }[vm.columns]" data-ng-repeat="portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage">
<up-project-thumb project="portfolio" ng-click="vm.openPortfolioDetails(vm.filteredPortfolios, (vm.portfolioPager.currentPage - 1) * vm.portfolioPager.itemsPerPage + $index)" delete-action="vm.openDeleteDialog(portfolio)" reorder-action="vm.openReorderDialog(portfolio)" hide-controls="false" edit-action="vm.openEditDialog(portfolio)" class="ng-isolate-scope"><div class="up-cursor-pointer up-active-container">
<div class="thumbnail thumb-block m-md-bottom p-0" ng-style="{'background-image': project.thumbnail &amp;&amp; 'url(\'/att/download/portfolio/persons/uid/424294450345394176/profile/projects/files/1040604997156118528\')' || 'none'}" style="background-image: url(&quot;/att/download/portfolio/persons/uid/424294450345394176/profile/projects/files/1040604997156118528&quot;);">
<!-- ngIf: !vm.hideControls --><div class="thumbnail-controls ng-scope" ng-if="!vm.hideControls">
<article>
<span aria-hidden="true" ng-click="vm.reorder($event)" class="btn btn-default btn-circle btn-sm m-sm-right d-none d-md-inline-block">
<span aria-hidden="true" class="fa fa-sort-alpha-up"></span>
</span>
<span aria-hidden="true" ng-click="vm.edit($event)" class="btn btn-default btn-circle btn-sm m-sm-right">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" ng-click="vm.remove($event)" class="btn btn-default btn-circle btn-sm">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</article>
</div><!-- end ngIf: !vm.hideControls -->
<!-- ngIf: project.thumbnail && project.linkedAssignment && ((project.linkedAssignment.state=='pendingApproval') || (project.linkedAssignment.state=='denied')) -->
<!-- ngIf: !project.thumbnail -->
</div>
<p class="text-truncate up-active-context m-0 project-title"><strong class="ng-binding">Foxlancer</strong></p>
</div>
</up-project-thumb>

</div><!-- end ngRepeat: portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage --><div class="m-lg-bottom ng-binding ng-scope col-md-6" data-ng-class="{1: 'col-md-12', 2: 'col-md-6', 3: 'col-md-4' }[vm.columns]" data-ng-repeat="portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage">
<up-project-thumb project="portfolio" ng-click="vm.openPortfolioDetails(vm.filteredPortfolios, (vm.portfolioPager.currentPage - 1) * vm.portfolioPager.itemsPerPage + $index)" delete-action="vm.openDeleteDialog(portfolio)" reorder-action="vm.openReorderDialog(portfolio)" hide-controls="false" edit-action="vm.openEditDialog(portfolio)" class="ng-isolate-scope"><div class="up-cursor-pointer up-active-container">
<div class="thumbnail thumb-block m-md-bottom p-0" ng-style="{'background-image': project.thumbnail &amp;&amp; 'url(\'\')' || 'none'}" style="background-image: none;">
<!-- ngIf: !vm.hideControls --><div class="thumbnail-controls ng-scope" ng-if="!vm.hideControls">
<article>
<span aria-hidden="true" ng-click="vm.reorder($event)" class="btn btn-default btn-circle btn-sm m-sm-right d-none d-md-inline-block">
<span aria-hidden="true" class="fa fa-sort-alpha-up"></span>
</span>
<span aria-hidden="true" ng-click="vm.edit($event)" class="btn btn-default btn-circle btn-sm m-sm-right">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" ng-click="vm.remove($event)" class="btn btn-default btn-circle btn-sm">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</article>
</div><!-- end ngIf: !vm.hideControls -->
<!-- ngIf: project.thumbnail && project.linkedAssignment && ((project.linkedAssignment.state=='pendingApproval') || (project.linkedAssignment.state=='denied')) -->
<!-- ngIf: !project.thumbnail --><div class="p-md-left-right ng-scope" ng-if="!project.thumbnail">
<h4 class="m-md-top text-truncate">
<a href="" class="ng-binding">Flow20</a>
</h4>
<!-- ngIf: project.linkedAssignment && project.linkedAssignment.state == 'pendingApproval' -->
<!-- ngIf: project.linkedAssignment && project.linkedAssignment.state == 'denied' -->
<p class="small m-sm-bottom cfe-project-description ng-isolate-scope" eo-truncation="project.description" eo-chars-threshold="125" eo-more-label="​"><span ng-show="!isOpen" class="ng-binding"> Online digital marketing agency. <!-- ngIf: truncationMade --> </span> <span ng-show="isOpen" class="ng-binding ng-hide"> Online digital marketing agency. <a ng-click="toggle()" class="eo-truncate-toggle-text eo-truncate-toggle-text-open ng-binding" href="javascript:void(0)">less</a> </span> </p>
<strong class="thumb-footer small ng-binding">
Web Development
<!-- ngIf: project.completedOn --><span data-ng-if="project.completedOn" class="ng-binding ng-scope"> | Jan 31, 2013</span><!-- end ngIf: project.completedOn -->
</strong>
</div><!-- end ngIf: !project.thumbnail -->
</div>
<p class="text-truncate up-active-context m-0 project-title"><strong class="ng-binding">Flow20</strong></p>
</div>
</up-project-thumb>

</div><!-- end ngRepeat: portfolio in vm.filteredPortfolios | offset:(vm.portfolioPager.currentPage-1)*vm.portfolioPager.itemsPerPage | limitTo:vm.portfolioPager.itemsPerPage -->
</div>
</div><!-- end ngIf: vm.filteredPortfolios.length -->
<!-- ngIf: !vm.filteredPortfolios.length && !vm.isServiceProfileEditMode -->
<!-- ngIf: !vm.filteredPortfolios.length && vm.isServiceProfileEditMode -->
</section>
<footer data-ng-show="vm.showPagination()" class="p-md-top">
<div class="o-pagination-container">
<ul class="float-end m-sm-top m-0-bottom pagination ng-pristine ng-untouched ng-valid ng-isolate-scope ng-not-empty" ng-show="pages.length > 1" total-items="vm.filteredPortfolios.length" items-per-page="vm.portfolioPager.itemsPerPage" ng-model="vm.portfolioPager.currentPage" ng-change="vm.pageChanged()" max-size="vm.portfolioMaxSize" rotate="false"> <!-- ngIf: boundaryLinks --> <!-- ngIf: directionLinks --><li ng-if="directionLinks" ng-class="{disabled: noPrevious()||ngDisabled}" class="pagination-prev ng-scope disabled"><a href="" ng-click="selectPage(page - 1, 'previous', $event)"><span aria-hidden="true" class="fa fa-angle-left m-0-left"></span><span class="d-none d-sm-inline ng-binding">Previous</span></a></li><!-- end ngIf: directionLinks --> <!-- ngRepeat: page in pages track by page.number --><li ng-repeat="page in pages track by page.number" ng-class="{active: page.active, disabled: ngDisabled &amp;&amp; !page.active}" class="pagination-page d-none d-sm-inline ng-scope active"><a href="" ng-click="selectPage(page.number, 'page_number', $event)" class="ng-binding">1</a></li><!-- end ngRepeat: page in pages track by page.number --><li ng-repeat="page in pages track by page.number" ng-class="{active: page.active, disabled: ngDisabled &amp;&amp; !page.active}" class="pagination-page d-none d-sm-inline ng-scope"><a href="" ng-click="selectPage(page.number, 'page_number', $event)" class="ng-binding">2</a></li><!-- end ngRepeat: page in pages track by page.number --> <li class="pagination-summary d-flex flex-1 d-sm-none"><a role="presentation" class="flex-1 ng-binding">1 of 2</a></li> <!-- ngIf: directionLinks --><li ng-if="directionLinks" ng-class="{disabled: noNext()||ngDisabled}" class="pagination-next ng-scope"><a href="" ng-click="selectPage(page + 1, 'next', $event)"><span class="d-none d-sm-inline ng-binding">Next</span><span aria-hidden="true" class="fa fa-angle-right m-0-right"></span></a></li><!-- end ngIf: directionLinks --> <!-- ngIf: boundaryLinks --> </ul>
</div>
</footer>
</div>
</div>
</o-profile-portfolio><!-- end ngIf: !actor.isVisitor() && !settings.enableServiceProfiles -->
<!-- ngIf: !settings.enableServiceProfiles && actor.isVisitor() -->
<cfe-profile-skills-integration profile="cfe.selectedProfile" is-service-profile="cfe.isServiceProfile" vpd="vpd" class="ng-isolate-scope"><!-- ngIf: $ctrl.showSkillsCard() --><div data-ng-if="$ctrl.showSkillsCard()" class="ng-scope">
<div class="air-card m-0-left-right-md m-0-left-right-xl p-0-top-bottom">
<header>
<h2 class="m-0-bottom">
Skills
<!-- ngIf: $ctrl.actor.isOwner() --><span aria-hidden="true" data-ng-if="$ctrl.actor.isOwner()" data-ng-click="$ctrl.openEditSkillDialog()" class="btn btn-default btn-circle btn-sm float-end ng-scope">
<span aria-hidden="true" data-ng-class="{'air-icon-add': $ctrl.showSkillsNoData(), 'air-icon-edit': !$ctrl.showSkillsNoData()}" class="fa fa-pencil-alt"></span>
</span><!-- end ngIf: $ctrl.actor.isOwner() -->
</h2>
</header>
<section>
<div>
<!-- ngIf: !$ctrl.isServiceProfile && !$ctrl.actor.isVisitor() --><o-edit-skills-section data-ng-if="!$ctrl.isServiceProfile &amp;&amp; !$ctrl.actor.isVisitor()" on-change="$ctrl.onSkillChange()" skills="$ctrl.vpd.profile.skills" use-categories-layout="true" control="$ctrl.skillsControl" limit="$ctrl.vpd.profile.skills.length || 5" class="ng-scope ng-isolate-scope"><div eo-block="false" class="ng-scope">
<!-- ngIf: skills.length --><div class="o-profile-skills m-sm-top p-lg-right ng-scope" data-ng-if="skills.length">
<span class="up-active-context display-inline-block m-md-right">
<!-- ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="196">
PHP
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="198">
AJAX
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="200">
jQuery
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="202">
CodeIgniter
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="204">
WordPress
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="206">
OpenCart
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="208">
MySQL Administration
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="210">
JavaScript
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="212">
HTML5
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit --><span data-ng-repeat="skill in skills | orderBy:'rank' | limitTo:showSkillsLimit" eo-popover="fe.components.profile.skillDescriptionTooltip.tmpl.html" eo-popover-placement="bottom" eo-popover-trigger="mouseenter" eo-popover-append-to-body="true" ng-mouseover="setActiveSkill(skill)" data-ng-class="{'o-tag-certified': skill.active}" class="o-tag-skill ng-binding ng-scope o-tag-certified" eo-popover-id="214">
CSS3
<!-- ngIf: isSkillChecked(skill) -->
</span><!-- end ngRepeat: skill in skills | orderBy:'rank' | limitTo:showSkillsLimit -->
<!-- ngIf: hasMoreSkillsToShow() -->
</span>
</div><!-- end ngIf: skills.length -->
<!-- ngIf: blocked --></div>
</o-edit-skills-section><!-- end ngIf: !$ctrl.isServiceProfile && !$ctrl.actor.isVisitor() -->
<!-- ngIf: !$ctrl.isServiceProfile && $ctrl.vpd.profile.skills.length && $ctrl.actor.isVisitor() -->
<!-- ngIf: $ctrl.hasSelectedAttributes() -->
<!-- ngIf: $ctrl.showSkillsNoData() -->
</div>
</section>
</div>
</div><!-- end ngIf: $ctrl.showSkillsCard() -->
</cfe-profile-skills-integration>
<o-profile-tests items="vpd.tests" is-owner="actor.isOwner()" edit-type="" is-visitor="actor.isVisitor()" class="ng-isolate-scope"><!-- ngIf: (items|filter:showFiltered).length || isEditable() --><div data-ng-if="(items|filter:showFiltered).length || isEditable()" class="ng-scope">
<div class="air-card m-0-left-right-md m-0-left-right-xl p-0-top-bottom">
<header>
<!-- ngIf: isEditable() --><span aria-hidden="true" ng-if="isEditable()" ng-click="redirectToPassTests()" class="btn btn-default btn-circle btn-sm float-end ng-scope">
<span aria-hidden="true" class="fa fa-plus"></span>
</span><!-- end ngIf: isEditable() -->
<h2 class="m-0-bottom">
Tests
</h2>
</header>
<section class="p-lg-top">
<!-- ngIf: items.length -->
<!-- ngIf: !items.length --><p class="m-0-bottom ng-scope" data-ng-if="!items.length">
You have not taken any tests. Successful tests increase your chances of getting jobs.
</p><!-- end ngIf: !items.length -->
</section>
</div>
</div><!-- end ngIf: (items|filter:showFiltered).length || isEditable() -->
</o-profile-tests>
<!-- ngIf: !actor.isVisitor() --><o-profile-certificates items="vpd.certificates" is-owner="actor.isOwner()" data-ng-if="!actor.isVisitor()" class="ng-scope ng-isolate-scope"><!-- ngIf: items.length || isEditable() --><div data-ng-if="items.length || isEditable()" class="ng-scope">
<div class="air-card m-0-left-right-md m-0-left-right-xl p-0-top-bottom collapsible">
<header ng-click="isCollapsed = !isCollapsed">
<!-- ngIf: isEditable() --><button ng-if="isEditable()" ng-click="openCertificatesAddDialog()" class="btn btn-default btn-circle btn-sm float-end d-none d-md-block ng-scope">
<span class="fa fa-plus" aria-hidden="true"></span>
</button><!-- end ngIf: isEditable() -->
<h2 class="m-0-bottom">
Certifications
<span class="glyphicon collapse-arrow float-end d-block d-md-none text-info air-icon-arrow-expand" data-ng-class="isCollapsed ? 'air-icon-arrow-expand' : 'air-icon-arrow-collapse'"></span>
</h2>
</header>
<section class="p-lg-top collapse" data-eo-collapse="isCollapsed" aria-expanded="false" aria-hidden="true" style="height: 0px;">
<!-- ngIf: isEditable() --><div class="clearfix d-block d-md-none ng-scope" data-ng-if="isEditable()">
<button ng-click="openCertificatesAddDialog()" class="btn btn-default btn-block-sm">
<span class="glyphicon glyphicon-sm air-icon-add vertical-align-middle m-0-left m-xs-right" aria-hidden="true"></span> Add
</button>
</div><!-- end ngIf: isEditable() -->
<ul class="list-unstyled ng-hide" data-ng-show="items.length">
<!-- ngRepeat: item in items | orderBy:certificateSorter:true | limitTo:showItemsLimit -->
<o-items-limiter items="items" step="5" limit="showItemsLimit" class="ng-isolate-scope"><!-- ngIf: hasMoreItemsToShow() -->
</o-items-limiter>
</ul>
<!-- ngIf: !items.length --><p class="m-0-bottom ng-scope" data-ng-if="!items.length">
No items to display.
</p><!-- end ngIf: !items.length -->
</section>
</div>
</div><!-- end ngIf: items.length || isEditable() -->
</o-profile-certificates><!-- end ngIf: !actor.isVisitor() -->
<!-- ngIf: !actor.isVisitor() --><o-profile-employment-history items="vpd.employmentHistory" edit-type="" country-code="vpd.profile.location.countryCode" data-ng-if="!actor.isVisitor()" class="ng-scope ng-isolate-scope"><!-- ngIf: vm.items.length || vm.isEditable() --><div data-ng-if="vm.items.length || vm.isEditable()" class="ng-scope">
<div class="air-card m-0-left-right-md m-0-left-right-xl p-0-top-bottom collapsible">
<header ng-click="vm.isCollapsed = !vm.isCollapsed">
<!-- ngIf: vm.isEditable() --><button ng-if="vm.isEditable()" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode)" class="btn btn-default btn-circle btn-sm float-end d-none d-md-block ng-scope">
<span class="fa fa-plus" aria-hidden="true"></span>
</button><!-- end ngIf: vm.isEditable() -->
<h2 class="m-0-bottom">Employment history
<span class="glyphicon collapse-arrow float-end d-block d-md-none text-info air-icon-arrow-expand" data-ng-class="vm.isCollapsed ? 'air-icon-arrow-expand' : 'air-icon-arrow-collapse'"></span>
</h2>
</header>
<section class="p-lg-top collapse" data-eo-collapse="vm.isCollapsed" aria-expanded="false" aria-hidden="true" style="height: 0px;">
<!-- ngIf: vm.isEditable() --><div class="clearfix d-block d-md-none ng-scope" data-ng-if="vm.isEditable()">
<button ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode)" class="btn btn-default btn-block-sm">
<span class="glyphicon glyphicon-sm air-icon-add vertical-align-middle m-0-left m-xs-right" aria-hidden="true"></span> Add
</button>
</div><!-- end ngIf: vm.isEditable() -->
<!-- ngIf: vm.items.length --><ul class="list-unstyled ng-scope" data-ng-if="vm.items.length">
<!-- ngRepeat: item in vm.items | orderBy:['-startDate', '-endDate'] | limitTo:vm.showItemsLimit --><li class="up-active-container m-0 ng-scope" up-highlight-editor="" ng-repeat="item in vm.items | orderBy:['-startDate', '-endDate'] | limitTo:vm.showItemsLimit">
<h4 class="m-0-top-bottom up-active-context fe-employment-history-editor-title" data-ng-class="{'fe-employment-history-editor-title': vm.isEditable()}">
<span class="m-xs-right ng-binding" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)">
Web Developer |  Originatesoft
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm m-sm-right up-active-control up-active-control-edit" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-delete" ng-click="vm.openEmploymentHistoryDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</h4>
<div class="m-sm-top text-muted">
<div class="up-active-context" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)">
<span class="ng-binding">April 2016 - Present</span>
</div>
</div>
<!-- ngIf: item.description --><p class="break text-pre-line m-0-bottom up-active-context ng-scope ng-isolate-scope" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)" o-text-truncate="item.description | htmlToPlaintext | linkRewrite:'/leaving?ref='" o-words-threshold="25" data-ng-if="item.description">On my current company I am playing multiple role.Handling project on single handed as well as lead the team.</p><!-- end ngIf: item.description -->
<!-- ngIf: !$last --><hr class="m-lg-top-bottom ng-scope" data-ng-if="!$last"><!-- end ngIf: !$last -->
</li><!-- end ngRepeat: item in vm.items | orderBy:['-startDate', '-endDate'] | limitTo:vm.showItemsLimit --><li class="up-active-container m-0 ng-scope" up-highlight-editor="" ng-repeat="item in vm.items | orderBy:['-startDate', '-endDate'] | limitTo:vm.showItemsLimit">
<h4 class="m-0-top-bottom up-active-context fe-employment-history-editor-title" data-ng-class="{'fe-employment-history-editor-title': vm.isEditable()}">
<span class="m-xs-right ng-binding" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)">
Sr. Web Developer |  Scriptgiant technologies pvt. ltd
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm m-sm-right up-active-control up-active-control-edit" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-delete" ng-click="vm.openEmploymentHistoryDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</h4>
<div class="m-sm-top text-muted">
<div class="up-active-context" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)">
<span class="ng-binding">January 2012 - March 2016</span>
</div>
</div>
<!-- ngIf: item.description --><p class="break text-pre-line m-0-bottom up-active-context ng-scope ng-isolate-scope" ng-click="vm.openEmploymentHistoryManageDialog(vm.countryCode, item)" o-text-truncate="item.description | htmlToPlaintext | linkRewrite:'/leaving?ref='" o-words-threshold="25" data-ng-if="item.description">Worked in Scriptgiant technologies pvt. ltd  for 4 Yrs during this period I have handled different kind of project.</p><!-- end ngIf: item.description -->
<!-- ngIf: !$last -->
</li><!-- end ngRepeat: item in vm.items | orderBy:['-startDate', '-endDate'] | limitTo:vm.showItemsLimit -->
<o-items-limiter items="vm.items" step="5" limit="vm.showItemsLimit" class="ng-isolate-scope"><!-- ngIf: hasMoreItemsToShow() -->
</o-items-limiter>
</ul><!-- end ngIf: vm.items.length -->
<!-- ngIf: !vm.items.length -->
</section>
</div>
</div><!-- end ngIf: vm.items.length || vm.isEditable() -->
</o-profile-employment-history><!-- end ngIf: !actor.isVisitor() -->
<!-- ngIf: !actor.isVisitor() --><o-profile-education items="vpd.education" edit-type="" country-code="vpd.profile.location.countryCode" data-ng-if="!actor.isVisitor()" class="ng-scope ng-isolate-scope"><!-- ngIf: vm.items.length || vm.isEditable() --><div data-ng-if="vm.items.length || vm.isEditable()" class="ng-scope">
<div class="air-card m-0-left-right-md m-0-left-right-xl p-0-top-bottom collapsible">
<header ng-click="vm.isCollapsed = !vm.isCollapsed">
<!-- ngIf: vm.isEditable() --><button ng-if="vm.isEditable()" ng-click="vm.openEducationManageDialog(vm.countryCode)" class="btn btn-default btn-circle btn-sm float-end d-none d-md-block ng-scope">
<span class="fa fa-plus" aria-hidden="true"></span>
</button><!-- end ngIf: vm.isEditable() -->
<h2 class="m-0-bottom">Education
<span class="glyphicon collapse-arrow float-end d-block d-md-none text-info air-icon-arrow-expand" data-ng-class="vm.isCollapsed ? 'air-icon-arrow-expand' : 'air-icon-arrow-collapse'"></span>
</h2>
</header>
<section class="p-lg-top collapse" data-eo-collapse="vm.isCollapsed" aria-expanded="false" aria-hidden="true" style="height: 0px;">
<!-- ngIf: vm.isEditable() --><div class="clearfix d-block d-md-none ng-scope" data-ng-if="vm.isEditable()">
<button ng-click="vm.openEducationManageDialog(vm.countryCode)" class="btn btn-default btn-block-sm">
<span class="glyphicon glyphicon-sm air-icon-add vertical-align-middle m-0-left m-xs-right" aria-hidden="true"></span> Add
</button>
</div><!-- end ngIf: vm.isEditable() -->
<!-- ngIf: vm.items.length --><ul class="list-unstyled m-sm-bottom ng-scope" data-ng-if="vm.items.length">
<!-- ngRepeat: item in vm.items | orderBy:['-dateStarted', '-dateEnded'] | limitTo:vm.showItemsLimit --><li class="up-active-container ng-scope" up-highlight-editor="" data-ng-repeat="item in vm.items | orderBy:['-dateStarted', '-dateEnded'] | limitTo:vm.showItemsLimit">
<h4 class="m-0-top-bottom up-active-context fe-education-editor-title" data-ng-class="{'fe-education-editor-title': vm.isEditable()}">
<span ng-click="vm.openEducationManageDialog(vm.countryCode, item)" class="ng-binding">
Bachelor of Technology (B.Tech.),  Information Technology |  West Bengal University of Technology
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm m-sm-right up-active-control up-active-control-edit" ng-click="vm.openEducationManageDialog(vm.countryCode, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-delete" ng-click="vm.openEducationDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</h4>
<div class="m-sm-top text-muted">
<div class="up-active-context" ng-click="vm.openEducationManageDialog(vm.countryCode, item)">
<span class="ng-binding">2007 - 2011</span>
</div>
</div>
<!-- ngIf: item.comment --><p class="break text-pre-line m-0-bottom up-active-context ng-scope ng-isolate-scope" ng-click="vm.openEducationManageDialog(vm.countryCode, item)" o-text-truncate="item.comment | htmlToPlaintext | linkRewrite:'/leaving?ref='" o-words-threshold="25" data-ng-if="item.comment">Bankura Unnayani Institute of Engineering, Bankura, West Bengal, CGPA: 7.68</p><!-- end ngIf: item.comment -->
<!-- ngIf: !$last --><hr class="m-lg-top-bottom ng-scope" data-ng-if="!$last"><!-- end ngIf: !$last -->
</li><!-- end ngRepeat: item in vm.items | orderBy:['-dateStarted', '-dateEnded'] | limitTo:vm.showItemsLimit --><li class="up-active-container ng-scope" up-highlight-editor="" data-ng-repeat="item in vm.items | orderBy:['-dateStarted', '-dateEnded'] | limitTo:vm.showItemsLimit">
<h4 class="m-0-top-bottom up-active-context fe-education-editor-title" data-ng-class="{'fe-education-editor-title': vm.isEditable()}">
<span ng-click="vm.openEducationManageDialog(vm.countryCode, item)" class="ng-binding">
High school degree,  Science |  West Bengal Council of Higher Secondary Education
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm m-sm-right up-active-control up-active-control-edit" ng-click="vm.openEducationManageDialog(vm.countryCode, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-delete" ng-click="vm.openEducationDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</h4>
<div class="m-sm-top text-muted">
<div class="up-active-context" ng-click="vm.openEducationManageDialog(vm.countryCode, item)">
<span class="ng-binding">2005 - 2007</span>
</div>
</div>
<!-- ngIf: item.comment --><p class="break text-pre-line m-0-bottom up-active-context ng-scope ng-isolate-scope" ng-click="vm.openEducationManageDialog(vm.countryCode, item)" o-text-truncate="item.comment | htmlToPlaintext | linkRewrite:'/leaving?ref='" o-words-threshold="25" data-ng-if="item.comment">Chandrakona Jirat High School,Chandrakona,Paschaim Medinipur,West Bengal,  61.57%</p><!-- end ngIf: item.comment -->
<!-- ngIf: !$last --><hr class="m-lg-top-bottom ng-scope" data-ng-if="!$last"><!-- end ngIf: !$last -->
</li><!-- end ngRepeat: item in vm.items | orderBy:['-dateStarted', '-dateEnded'] | limitTo:vm.showItemsLimit --><li class="up-active-container ng-scope" up-highlight-editor="" data-ng-repeat="item in vm.items | orderBy:['-dateStarted', '-dateEnded'] | limitTo:vm.showItemsLimit">
<h4 class="m-0-top-bottom up-active-context fe-education-editor-title" data-ng-class="{'fe-education-editor-title': vm.isEditable()}">
<span ng-click="vm.openEducationManageDialog(vm.countryCode, item)" class="ng-binding">
High school degree  |  West Bengal Board of Secondary Education
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm m-sm-right up-active-control up-active-control-edit" ng-click="vm.openEducationManageDialog(vm.countryCode, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-delete" ng-click="vm.openEducationDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span>
</h4>
<div class="m-sm-top text-muted">
<div class="up-active-context" ng-click="vm.openEducationManageDialog(vm.countryCode, item)">
<span class="ng-binding">2004 - 2005</span>
</div>
</div>
<!-- ngIf: item.comment --><p class="break text-pre-line m-0-bottom up-active-context ng-scope ng-isolate-scope" ng-click="vm.openEducationManageDialog(vm.countryCode, item)" o-text-truncate="item.comment | htmlToPlaintext | linkRewrite:'/leaving?ref='" o-words-threshold="25" data-ng-if="item.comment">Palaschabri Nigamananda High School,Palashchabri, Rampur,Paschaim Medinipur,West Bengal,  73.50%</p><!-- end ngIf: item.comment -->
<!-- ngIf: !$last -->
</li><!-- end ngRepeat: item in vm.items | orderBy:['-dateStarted', '-dateEnded'] | limitTo:vm.showItemsLimit -->
<o-items-limiter items="vm.items" step="5" limit="vm.showItemsLimit" class="ng-isolate-scope"><!-- ngIf: hasMoreItemsToShow() -->
</o-items-limiter>
</ul><!-- end ngIf: vm.items.length -->
<!-- ngIf: !vm.items.length -->
</section>
</div>
</div><!-- end ngIf: vm.items.length || vm.isEditable() -->
</o-profile-education><!-- end ngIf: !actor.isVisitor() -->
<!-- ngIf: !actor.isVisitor() --><o-profile-other-experience items="vpd.otherExperiences" edit-type="" is-owner="actor.isOwner()" data-ng-if="!actor.isVisitor()" class="ng-scope ng-isolate-scope"><!-- ngIf: items.length || isEditable() --><div data-ng-if="items.length || isEditable()" class="ng-scope">
<div class="air-card m-0-left-right-md m-0-left-right-xl p-0-top-bottom collapsible">
<header ng-click="isCollapsed = !isCollapsed">
<!-- ngIf: isEditable() --><button ng-if="isEditable()" ng-click="openOtherExperienceManageDialog()" class="btn btn-default btn-circle btn-sm float-end d-none d-md-block ng-scope">
<span class="fa fa-plus" aria-hidden="true"></span>
</button><!-- end ngIf: isEditable() -->
<h2 class="m-0-bottom">Other experiences
<span class="glyphicon collapse-arrow float-end d-block d-md-none text-info air-icon-arrow-expand" data-ng-class="isCollapsed ? 'air-icon-arrow-expand' : 'air-icon-arrow-collapse'"></span>
</h2>
</header>
<section class="p-lg-top collapse" data-eo-collapse="isCollapsed" aria-expanded="false" aria-hidden="true" style="height: 0px;">
<!-- ngIf: isEditable() --><div class="clearfix d-block d-md-none ng-scope" data-ng-if="isEditable()">
<button ng-click="openOtherExperienceManageDialog()" class="btn btn-default btn-block-sm">
<span class="glyphicon glyphicon-sm air-icon-add vertical-align-middle m-0-left m-xs-right" aria-hidden="true"></span> Add
</button>
</div><!-- end ngIf: isEditable() -->
<!-- ngIf: items.length -->
<!-- ngIf: !items.length --><p class="m-sm-bottom ng-scope" data-ng-if="!items.length">
No items to display.
</p><!-- end ngIf: !items.length -->
</section>
</div>
</div><!-- end ngIf: items.length || isEditable() -->
</o-profile-other-experience><!-- end ngIf: !actor.isVisitor() -->
<cfe-profile-talent-clouds clouds="vpd.clouds" class="d-block d-md-none ng-isolate-scope"><!-- ngIf: $ctrl.clouds.length -->
</cfe-profile-talent-clouds>
<!-- ngIf: vpd.ready && !actor.isOwner() && !actor.isVisitor() && !isOnboardingV2() && !settings.areClientControlsHidden -->
</div><!-- end ngIf: !isProfilePrivate -->
</div>
<!-- ngIf: !settings.hideRightColumn --><div class="col-md-3 cfe-sidebar d-none d-md-block ng-scope up-active-editor" data-ng-if="!settings.hideRightColumn" ng-class="{'up-active-editor': actor.isOwner()}" data-ng-show="vpd.ready">
<div ng-transclude="feSidebarTopSlot"></div>
<!-- ngIf: vpd.ready && actor.isOwner() --><div ng-if="vpd.ready &amp;&amp; actor.isOwner()" class="text-center cfe-profile-settings ng-scope">
<a href="/profile/settings" class="btn btn-block m-0-top m-md-bottom btn-default">
Profile Settings
</a>
<a href="/freelancers/~01bdab2160eb164725?viewMode=1" ng-click="profileDetailsLogger.clickViewMyProfileAsOthers()">
<small>View my profile as others see it</small>
</a>
</div><!-- end ngIf: vpd.ready && actor.isOwner() -->
<!-- ngIf: actor.isVisitor() -->
<!-- ngIf: actor.isClient() && !actor.isOwner() && !settings.areClientControlsHidden -->
<!-- ngIf: !isProfilePrivate --><div data-ng-if="!isProfilePrivate" class="ng-scope">
<!-- ngIf: vpd.ready && !actor.isOwner() && !actor.isVisitor() && !isOnboardingV2() && !settings.areClientControlsHidden -->
<!-- ngIf: hasJobStats() && isShowFlRecentEarnings -->
<!-- ngIf: hasJobStats() && isShowFlRecentEarnings -->
<cfe-availability class="custom-display-block m-md-top ng-isolate-scope" vpd="vpd" settings="settings" on-edit="openEditAvailabilityDialog"><!-- ngIf: $ctrl.vpd.availability.uid --><div up-highlight-editor="" data-ng-if="$ctrl.vpd.availability.uid" class="p-0-top-bottom up-active-container ng-scope">
<!-- ngIf: $ctrl.hasJobStats() || $ctrl.isJssScorePrivate() || ($ctrl.showJSSTemplate() && $ctrl.isJssScorePublic()) --><hr class="m-lg-top-bottom d-none d-md-block ng-scope" ng-if="$ctrl.hasJobStats() || $ctrl.isJssScorePrivate() || ($ctrl.showJSSTemplate() &amp;&amp; $ctrl.isJssScorePublic())"><!-- end ngIf: $ctrl.hasJobStats() || $ctrl.isJssScorePrivate() || ($ctrl.showJSSTemplate() && $ctrl.isJssScorePublic()) -->
<h4 class="up-active-context display-inline-block p-lg-bottom m-0-bottom">
<span ng-click="$ctrl.onEdit()">Availability</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-availability" ng-click="$ctrl.onEdit()">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
</h4>
<up-dev-availability data-availability="$ctrl.vpd.availability" data-ng-click="$ctrl.onEdit()" class="up-active-context m-xs-top ng-isolate-scope"><!-- ngIf: vm.availability --><div data-ng-if="vm.availability" class="ng-scope">
<!-- ngIf: vm.availability.capacity --><div data-ng-if="vm.availability.capacity" class="m-0-top-bottom ng-scope">
<strong>Available</strong>
<span class="d-inline d-md-none ng-binding">- More than 30 hrs/week</span>
</div><!-- end ngIf: vm.availability.capacity -->
<!-- ngIf: vm.availability.capacity --><div data-ng-if="vm.availability.capacity" class="m-0-top-bottom d-none d-md-block ng-scope">
<span class="ng-binding">More than 30 hrs/week</span>
</div><!-- end ngIf: vm.availability.capacity -->
<!-- ngIf: !vm.availability.capacity -->
</div><!-- end ngIf: vm.availability -->
</up-dev-availability>
<!-- ngIf: $ctrl.vpd.isResponsiveStateShorten() && $ctrl.vpd.getResponsiveTimeFF() -->
<!-- ngIf: !$ctrl.vpd.isResponsiveStateShorten() && $ctrl.vpd.getResponsiveTime() -->
</div><!-- end ngIf: $ctrl.vpd.availability.uid -->
</cfe-availability>
<cfe-profile-url class="d-none d-lg-block ng-isolate-scope" vpd="vpd" settings="settings" selected-profile="cfe.selectedProfile"><!-- ngIf: $ctrl.profileUrl --><section ng-if="$ctrl.profileUrl" ng-class="{'up-active-container': $ctrl.profileQualifiesForCustomUrl &amp;&amp; !$ctrl.selectedProfile}" class="p-0-top-bottom ng-scope">
<hr class="m-lg-top-bottom d-none d-md-block">
<h4 class="up-active-context display-inline-block">
<span ng-click="$ctrl.onEdit()">
<span class="d-none d-md-block ng-binding"> </span>Profile link
</span>
<!-- ngIf: $ctrl.profileQualifiesForCustomUrl -->
</h4>
<input value="https://www.upwork.com/o/profiles/users/_~01bdab2160eb164725/" readonly="readonly" class="form-control" type="text">
<div class="m-sm-top">
<a href="javascript:" data-ng-click="$ctrl.copyProfileLink()">Copy link</a>
</div>
</section><!-- end ngIf: $ctrl.profileUrl -->
</cfe-profile-url>
<!-- ngIf: isShowMap() -->
<o-profile-languages items="vpd.languages" is-owner="actor.isOwner()" class="ng-isolate-scope"><!-- ngIf: vm.isOwner || vm.items.length --><div class="p-0-top-bottom up-active-container ng-scope" data-ng-if="vm.isOwner || vm.items.length">
<hr class="m-lg-top-bottom d-none d-md-block">
<h4 class="up-active-context p-lg-bottom m-0-bottom display-inline-block">
Languages
<span aria-hidden="true" ng-click="vm.openLanguageManageDialog(vm.items)" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-availability">
<span aria-hidden="true" class="fa fa-plus"></span>
</span>
</h4>
<!-- ngIf: vm.items --><ul class="list-unstyled ng-scope" data-ng-if="vm.items">
<!-- ngRepeat: item in vm.items | orderBy:vm.languageSorter --><li class="up-active-context up-active-context-language p-lg-bottom m-0-top-bottom clearfix ng-scope" data-ng-repeat="item in vm.items | orderBy:vm.languageSorter">
<div class="editable-lang">
<span ng-click="vm.openLanguageManageDialog(vm.items, item)" class="float-start ng-binding">
English:
<small class="text-muted ng-binding">Fluent</small>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language" ng-click="vm.openLanguageManageDialog(vm.items, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<!-- ngIf: item.language.iso639Code != 'en' -->
<!-- ngIf: item.verified -->
</div>
</li><!-- end ngRepeat: item in vm.items | orderBy:vm.languageSorter --><li class="up-active-context up-active-context-language p-lg-bottom m-0-top-bottom clearfix ng-scope" data-ng-repeat="item in vm.items | orderBy:vm.languageSorter">
<div class="editable-lang">
<span ng-click="vm.openLanguageManageDialog(vm.items, item)" class="float-start ng-binding">
Bengali:
<small class="text-muted ng-binding">Fluent</small>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language" ng-click="vm.openLanguageManageDialog(vm.items, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<!-- ngIf: item.language.iso639Code != 'en' --><span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language up-active-control-delete ng-scope" ng-if="item.language.iso639Code != 'en'" data-ng-click="vm.openLanguageDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span><!-- end ngIf: item.language.iso639Code != 'en' -->
<!-- ngIf: item.verified -->
</div>
</li><!-- end ngRepeat: item in vm.items | orderBy:vm.languageSorter --><li class="up-active-context up-active-context-language p-lg-bottom m-0-top-bottom clearfix ng-scope" data-ng-repeat="item in vm.items | orderBy:vm.languageSorter">
<div class="editable-lang">
<span ng-click="vm.openLanguageManageDialog(vm.items, item)" class="float-start ng-binding">
Hindi:
<small class="text-muted ng-binding">Fluent</small>
</span>
<span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language" ng-click="vm.openLanguageManageDialog(vm.items, item)">
<span aria-hidden="true" class="fa fa-pencil-alt"></span>
</span>
<!-- ngIf: item.language.iso639Code != 'en' --><span aria-hidden="true" class="btn btn-default btn-circle btn-sm up-active-control up-active-control-language up-active-control-delete ng-scope" ng-if="item.language.iso639Code != 'en'" data-ng-click="vm.openLanguageDeleteDialog(item)">
<span aria-hidden="true" class="fa fa-trash"></span>
</span><!-- end ngIf: item.language.iso639Code != 'en' -->
<!-- ngIf: item.verified -->
</div>
</li><!-- end ngRepeat: item in vm.items | orderBy:vm.languageSorter -->
</ul><!-- end ngIf: vm.items -->
</div><!-- end ngIf: vm.isOwner || vm.items.length -->
</o-profile-languages>
<!-- ngIf: vpd.profile.idVerified || vpd.profile.phoneVerified -->
<cfe-profile-verifications profile="vpd.profile" class="ng-isolate-scope"><!-- ngIf: $ctrl.profile.idVerified || $ctrl.profile.phoneVerified -->
</cfe-profile-verifications>
<!-- ngIf: vpd.agencies -->
<!-- ngIf: vpd.agencies -->
<!-- ngIf: vpd.clouds.length > 0 -->
<!-- ngIf: vpd.clouds.length > 0 -->
<!-- ngIf: vpd.groups -->
<!-- ngIf: actor.isVisitor() && vpd.relatedSkills.length -->
</div><!-- end ngIf: !isProfilePrivate -->
</div><!-- end ngIf: !settings.hideRightColumn -->
<!-- ngIf: blocked --><!-- ngIf: blocked --></div>
<div class="eo-block-none ng-scope" data-eo-block="false" data-message="Saving.." data-target="div#oProfilePage">
</div>
<div class="eo-block-none ng-scope" data-eo-block="false" data-message="Loading.." data-target="div#oProfilePage">
</div>
</div>
</div>
</fe-profile><!-- end ngIf: vm.isShowProfile() -->
    </div>
</div>
        </div>
</div>