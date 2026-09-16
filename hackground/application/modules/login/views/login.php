<!DOCTYPE html>

<html>
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo get_setting('site_title')?>| Log in</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo ADMIN_THEME_CSS;?>adminlte.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo ADMIN_PLUGINS;?>font-awesome/font-awesome.min.css" type="text/css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo ADMIN_PLUGINS;?>ionicons/ionicons.min.css" type="text/css"> 
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo ADMIN_CSS;?>style.css">
  <link rel="stylesheet" href="<?php echo ADMIN_CSS;?>magic-check.min.css">
  <script src="<?php echo ADMIN_JS; ?>jquery.min.js"></script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->
<style>
.error {
	color: red;
}
</style>
  </head>

  <body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
		<?php /*?><b><?php echo get_setting('site_title')?></b><?php */?>
        <img src="<?php echo ADMIN_IMAGES;?>logo.png" alt="<?php echo get_setting('site_title');?>" height="64" />
    </div>
    
    <!-- /.login-logo -->
    
    <div id="ajax_status"></div>
    <div class="login-box-body" id="login_form_wrapper">
    <p class="login-box-msg">Sign in to start your session</p>
    <?php

	$login_str = get_cookie('l_info');

	if($login_str){

		$login_info = unserialize($login_str);

	}else{

		$login_info = array();

	}

	?>
    <form onsubmit="login.checkLogin(this, event)">
        <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo !empty($login_info['uname']) ? $login_info['uname'] : '';?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span> </div>
        <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" value="<?php echo !empty($login_info['pwd']) ? $login_info['pwd'] : '';?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <div id="loginError" class="error"></div>
      </div>
        	<input type="checkbox" name="remember_me" id="remember_me" class="magic-checkbox" value="1" <?php echo !empty($login_info['uname']) ? 'checked' : '';?>>
            <label for="remember_me">Remember Me</label>
      		<button type="submit" class="btn btn-site btn-block mb-3">Sign In</button>
      </form>
      
    <div class="social-auth-links text-center hidden">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in usingFacebook</a>
        <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a> </div>    
    <!-- /.social-auth-links --> 
  
    <p class="text-center mb-0"><a href="javascript:void(0)" class="text-secondary" onclick="forgot_password.open();">I forgot my password</a></p>
  </div>
    <div class="login-box-body" id="forget_password_wrapper" style="display:none;">
    <p class="login-box-msg">Enter your email id below to get reset link</p>
    <form onsubmit="forgot_password.checkEmail(this, event)">
        <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Email" name="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span> </div>
        
        <button type="submit" class="btn btn-site btn-block mb-3">Send Reset Link</button>
      </form>
      <p class="text-center mb-0"><a href="javascript:void(0)" class="text-secondary" onclick="login.open();">Back to Login</a></p>
  </div>
    
    <!-- /.login-box-body --> 
    
  </div>

<!-- /.login-box --> 

<!-- jQuery 3 --> 

<script src="<?php echo ADMIN_COMPONENT; ?>bootstrap/js/bootstrap.min.js"></script> 
 
<script>



(function($){

	

	var $ajax_status = $('#ajax_status');

	var login  = {};

	var forgot_password = {};

	

	login.open = function(){

		$ajax_status.empty();

		$('#login_form_wrapper').show();

		$('#forget_password_wrapper').hide();

		

	};

	

	login.checkLogin = function(form, evt){

		

		evt.preventDefault();

		

		var fdata = $(form).serialize();

		$('.invalid').removeClass('invalid');

		$.ajax({

			url: '<?php echo base_url('login/login_ajax')?>',

			data: fdata,

			dataType: 'json',

			type: 'POST',

			success: function(res){

				if(res.status == 0){

					

					var errors = res.errors;

					for(var i in errors){

						

						$('#'+i+'Error').html(errors[i]);

						$('[name="'+i+'"]').addClass('invalid');

						

					}

					

				}else{

					location.href = res.next;

				}

			}

		});

		

	};

	

	forgot_password.checkEmail = function(form, evt){

		$ajax_status.empty();

		evt.preventDefault();

		

		var fdata = $(form).serialize();

		$('.invalid').removeClass('invalid');

		$.ajax({

			url: '<?php echo base_url('login/forgot_password_ajax'); ?>',

			data: fdata,

			dataType: 'json',

			type: 'POST',

			success: function(res){

				if(res.status == 0){

					

					var errors = res.errors;

					for(var i in errors){

						

						$('#'+i+'Error').html(errors[i]);

						$('[name="'+i+'"]').addClass('invalid');

						

					}

					

				}

				if(res.msg){

					$ajax_status.html(res.msg);

				}

			}

		});

		

	};

	

	forgot_password.open = function(){

		$ajax_status.empty();

		$('#login_form_wrapper').hide();

		$('#forget_password_wrapper').show();

	};

	

	window.login = login;

	window.forgot_password = forgot_password;

	

})(jQuery);





</script>
</body>
</html>
