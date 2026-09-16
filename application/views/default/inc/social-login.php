<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$facebook_app_id=get_setting('fb_app_id');
$google_app_id=get_setting('google_apiKey');
$google_client_id=get_setting('google_client_id');
?>
<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};HandleGoogleApiLibrary()" onreadystatechange="if (this.readyState === 'complete') this.onload()"></script>
<script type="text/javascript">
var facebook_app_id="<?php echo $facebook_app_id;?>";
var google_app_id="<?php echo $google_app_id;?>";
var google_client_id="<?php echo $google_client_id;?>";
function facebook_login(){
	var fb_scope = 'public_profile,email'; 
	FB.login(function(response){
	  if(response.status == 'connected'){
			FB.api('/me', {fields: 'last_name,birthday,email,first_name,gender'}, function(response) {
				var dob = response.birthday ? response.birthday : '';
				var gender = response.gender ? response.gender : '';
				var fb_data = {
					id: response.id,
					name: response.first_name + ' '+response.last_name,
					dob: dob,
					gender: response.gender == gender,
					email: response.email,
					ref:'<?php D(get('ref'));?>',
					refer:'<?php D(get('refer'));?>'
				};
				$.ajax({
					url: '<?php D(get_link('AppiLogin').'/facebook');?>',
					type: 'POST',
					data: fb_data,
					dataType: 'JSON',
					success: function(res){
						if(res.status == 'OK'){
							location.href = res.redirect;
						}else{
							bootbox.alert({
								title:'<?php D(__('sign_up_page_Sign_up_error','Sign up error'));?>',
								message: res.errors[0].message,
								buttons: {
								'ok': {
									label: '<?php D(__('sign_up_page_error_login_failed_ok','Ok'));?>',
									className: 'btn-primary float-end'
									}
								},
								callback: function () {
									window.location.reload();
							    }
							});
						}
					}
				});
			});
	  }else{

	  }
	  //console.log(response);
	}, {scope: fb_scope, auth_type: 'rerequest', return_scopes: true});
}
// Called when Google Javascript API Javascript is loaded
function HandleGoogleApiLibrary() {
	// Load "client" & "auth2" libraries
	gapi.load('client:auth2', {
		callback: function() {
			// Initialize client library
			// clientId & scope is provided => automatically initializes auth2 library
			gapi.client.init({
		    	apiKey: '<?php D($google_app_id);?>',
		    	clientId: '<?php D($google_client_id);?>',
		    	scope: 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me'
			}).then(
				// On success
				function(success) {
			  		// After library is successfully loaded then enable the login button
			  		$("#login-button").removeAttr('disabled');
					$("#login-button").show();
				}, 
				// On error
				function(error) {
					/* alert('Error : Failed to Load Library'); */
			  	}
			);
		},
		onerror: function() {
			// Failed to load libraries
		}
	});
}
var mainpart = function(){
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/all.js#version=v5.0&appId=<?php D($facebook_app_id);?>&status=true&cookie=true&xfbml=true";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    	
    if(facebook_app_id){
        $(".fb_login_btn").on('click', function() {
        facebook_login();
	    }) 
    }else{
        $(".fb_login_btn").hide();
    }
    if(google_app_id && google_client_id){
        $(".google_login_btn").on('click', function() {
        $(".google_login_btn").attr('disabled', 'disabled');	
        // API call for Google login
        gapi.auth2.getAuthInstance().signIn().then(
            // On success
            function(success) {
                var profile = gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile();
                var google_data = {
                    id: profile.getId(),
                    name: profile.getName(),
                    email: profile.getEmail(),
                    ref:'<?php D(get('ref'));?>',
                    refer:'<?php D(get('refer'));?>'
                };
                $.ajax({
                    url : '<?php D(get_link('AppiLogin').'/google');?>',
                    data: google_data,
                    type: 'POST',
                    dataType: 'json',
                    success: function(res){
                        if(res.status == 'OK'){
                            location.href = res.redirect;
                        }
                        
                    }
                });
                $(".google_login_btn").hide();
            },
            // On error
            function(error) {
                $(".google_login_btn").removeAttr('disabled');
                bootbox.alert({
                    title:'<?php D(__('sign_up_page_Sign_up_error','Sign up error'));?>',
                    message:'<?php D(__('sign_up_page_error_login_failed','Error : Login Failed'));?>',
                    buttons: {
                    'ok': {
                        label: '<?php D(__('sign_up_page_error_login_failed_ok','Ok'));?>',
                        className: 'btn-primary float-end'
                        }
                    },
                    callback: function () {
                        window.location.reload();
                    }
                });
                
            }
        );
    });
    }else{
        $(".google_login_btn").hide();
    }
	}
</script>
