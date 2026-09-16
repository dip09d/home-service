<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?php echo get_setting('site_title')?> | Log in</title>

  <!-- Tell the browser to be responsive to screen width -->

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.7 -->

  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->

  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>font-awesome/css/font-awesome.min.css">

  <!-- Ionicons -->

  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>Ionicons/css/ionicons.min.css">

  <!-- Theme style -->

  <link rel="stylesheet" href="<?php echo ADMIN_CSS;?>AdminLTE.min.css">

  <!-- iCheck -->

  <link rel="stylesheet" href="<?php echo ADMIN_EXTRA;?>plugins/iCheck/square/blue.css">



  <script src="<?php echo ADMIN_COMPONENT; ?>jquery/dist/jquery.min.js"></script>





  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->



  <!-- Google Font -->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  

  <style>

	.invalid{

		border: 1px solid red;

	}

	

	.error{

		color: red;

	}

  </style>

</head>

<body class="hold-transition login-page">

<div class="login-box">

  <div class="login-logo">

    <a href="#"><b><?php echo get_setting('site_title')?></b></a>

  </div>

  <!-- /.login-logo -->

  

  <div id="ajax_status"></div>
  <div id="tokenError" class="error_msg text-red"></div>


   <div class="login-box-body" id="forget_password_wrapper">

   <p class="login-box-msg">Reset your password</p>

	<form onsubmit="forgot_password.resetPassword(this, event)">
		<input type="hidden" name="token" value="<?php echo $reset_token; ?>"/>
      <div class="form-group has-feedback">

        <input type="password" class="form-control" placeholder="New Password" name="password">

        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

      </div>
	  
	   <div class="form-group has-feedback">

        <input type="password" class="form-control" placeholder="Confirm Password" name="c_password">

        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

      </div>
	  
      <div class="row">

		 

		<!-- /.col -->

        <div class="col-xs-12">

          <button type="submit" class="btn btn-primary btn-block">Reset Password</button>

        </div>

        <!-- /.col -->

      </div>

	  

    </form>



  </div>

  

  <!-- /.login-box-body -->

</div>

<!-- /.login-box -->



<!-- jQuery 3 -->

<!-- Bootstrap 3.3.7 -->

<script src="<?php echo ADMIN_COMPONENT; ?>bootstrap/dist/js/bootstrap.min.js"></script>

<!-- iCheck -->

<script src="<?php echo ADMIN_EXTRA; ?>plugins/iCheck/icheck.min.js"></script>

<script>

  $(function () {

    $('input').iCheck({

      checkboxClass: 'icheckbox_square-blue',

      radioClass: 'iradio_square-blue',

      increaseArea: '20%' // optional

    });

  });

</script>



<script>



(function($){

	

	var $ajax_status = $('#ajax_status');

	var forgot_password = {};

	
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
	
	forgot_password.resetPassword = function(form, evt){

		$ajax_status.empty();

		evt.preventDefault();

		

		var fdata = $(form).serialize();

		$('.invalid').removeClass('invalid');
		$('.error_msg').removeClass('error_msg');

		$.ajax({

			url: '<?php echo base_url('login/reset_password_ajax'); ?>',

			data: fdata,

			dataType: 'json',

			type: 'POST',

			success: function(res){

				if(res.status == 0){

					

					var errors = res.errors;

					for(var i in errors){

						

						$('#'+i+'Error').html(errors[i]);
						
						$('#'+i+'Error').addClass('error_msg');
						
						$('[name="'+i+'"]').addClass('invalid');

						

					}

					

				}

				if(res.msg){

					$ajax_status.html(res.msg);

				}
				
				if(res.next){

					location.href = res.next;

				}

			}

		});

		

	};

	window.forgot_password = forgot_password;

	

})(jQuery);





</script>





</body>

</html>

