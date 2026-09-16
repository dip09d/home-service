<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if($is_valid){?>


<section class="margin-top-65 margin-bottom-65">
	<div class="container"> <!-- Container starts -->
    <div class="general-form">
    	<div class="general-body">
        <h1 class="text-center mb-4"><?php D(__('rest_password_heading',"Reset Your Password"));?></h1>
    	<form action="" method="post" accept-charset="utf-8" id="restpasswordform" class="form-horizontal col-md-8" role="form" name="restpasswordform" onsubmit="saveResetpassword(this);return false;"> 
        <input  type="hidden" name="verifycode" value="<?php D($verifycode);?>"/>
            <div class="form-group">
                <label><?php D(__('rest_password_password',"Enter New Password"));?></label>
                <div class="input-group">
                    <span class="input-group-prepend">
                        <i class="fa fa-check tick1 text-success"></i>
                        <i class="fa fa-times cross1 text-danger"></i>
                    </span>
                    <input type="password" name="new_pass" id="new_pass" class="form-control">
                    <span class="input-group-text" id="meter">
                        <div id="meter_wrapper">
                            <span id="pass_type"><span class="move-up-js text-dark"><?php echo __('user_page_resetpass_strength','Strength');?></span></span>
                            <!--<div id="meter"></div>-->
                        </div>
                    </span>
                </div>	
                <span id="new_passError" class="rerror"></span>
            </div>
            <div class="form-group">
                <label><?php D(__('rest_password_confirm_password',"Confirm New Password"));?></label>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-check tick2 text-success"></i>
                        <i class="fa fa-times cross2 text-danger"></i>
                    </span>
                    <input type="password" name="new_pass_again" id="new_pass_again" class="form-control" >
                </div>	
                <span id="new_pass_againError" class="rerror"></span>
            </div>
            <div class="text-center">
                <button class="btn btn-success saveBTN" type="submit" name="submit">
                    <i class="fa fa-pencil-square-o"></i> <?php D(__('rest_password_button_submit',"Change Password"));?>
                </button>
            </div>
        </form>
        </div>    
    </div>
	</div> <!-- Container ends -->
</section>

<script type="text/javascript">
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var main=function(){
	$('.tick1').hide();
	$('.cross1').hide();
	$('.tick2').hide();
	$('.cross2').hide();
	$("#new_pass").keyup(function(){
		check_pass();
	});
	$('#new_pass_again').focusout(function(){
		var password = $('#new_pass').val();
		var confirmPassword = $('#new_pass_again').val();
		if(password == confirmPassword){
			$('.tick1').show();
			$('.cross1').hide();
			$('.tick2').show();
			$('.cross2').hide();
		}else{
			$('.tick1').hide();
			$('.cross1').show();
			$('.tick2').hide();
			$('.cross2').show();
		}
	});
};
function check_pass() {
	var val = document.getElementById("new_pass").value;
	var meter = document.getElementById("meter");
	var no=0;
	if(val!=""){
		// If the password length is less than or equal to 6
		if(val.length<=6)no=1;
		// If the password length is greater than 6 and contain any lowercase alphabet or any number or any special character
		if(val.length>6 && (val.match(/[a-z]/) || val.match(/\d+/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))no=2;
		// If the password length is greater than 6 and contain alphabet,number,special character respectively
		if(val.length>6 && ((val.match(/[a-z]/) && val.match(/\d+/)) || (val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) || (val.match(/[a-z]/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))))no=3;
		// If the password length is greater than 6 and must contain alphabets,numbers and special characters
		if(val.length>6 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))no=4;
		if(no==1){
			$("#meter").animate({width:'100px'},300);
			meter.style.backgroundColor="red";
			meter.style.borderColor="red";
			document.getElementById("pass_type").innerHTML="<span class='move-up-js text-white'><?php D(__('rest_password_Very_Weak','Very Weak'));?> </span>";
		}
		if(no==2){
			$("#meter").animate({width:'62px'},300);
			meter.style.backgroundColor="#F5BCA9";
			meter.style.borderColor="#F5BCA9";
			document.getElementById("pass_type").innerHTML="<span class='move-up-js text-white'> <?php D(__('rest_password_Weak','Weak'));?> </span>";
		}
		if(no==3){
			$("#meter").animate({width:'62px'},300);
			meter.style.backgroundColor="#FF8000";
			meter.style.borderColor="#FF8000";
			document.getElementById("pass_type").innerHTML="<span class='move-up-js text-white'> <?php D(__('rest_password_Good','Good'));?> </span>";
		}
		if(no==4){
			$("#meter").animate({width:'70px'},300);
			meter.style.backgroundColor="#00c853";
			meter.style.borderColor="#00c853";
			document.getElementById("pass_type").innerHTML="<span class='move-up-js text-white'><?php D(__('rest_password_Strong','Strong'));?></span>";
		}
	}else{
		meter.style.backgroundColor="";
		document.getElementById("pass_type").innerHTML="<span class='move-up-js text-dark'><?php D(__('rest_password_Strength','Strength'));?></span>";
	}
}
function saveResetpassword(ev){
	var formID="restpasswordform";
	var buttonsection=$('#'+formID).find('.saveBTN');
	var buttonval = buttonsection.html();
	buttonsection.html(SPINNER).attr('disabled','disabled');
	
	$.ajax({
        type: "POST",
        url: "<?php D(get_link('resetURLAJAX'))?>/",
        data:$('#'+formID).serialize(),
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			clearErrors();
			if (msg['status'] == 'OK') {
					bootbox.alert({
						title:'Reset Password',
						message: '<?php D(__('rest_password_success_message','Your password has been updated successfully. Redirecting you to login page...'));?>',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							if(msg['redirect']){
									window.location.href=msg['redirect'];
								return false;
							}
					    }
					});

			} else if (msg['status'] == 'FAIL') {
				bootbox.alert({
						title:'Reset Password',
						message: '<?php D(__('rest_password_error_message',"Opps! Your passwords don\'t match. Please try again."));?>',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						}
					});
				
				registerFormPostResponse(formID,msg['errors']);
			}
		}
	})	
}
</script>

<?php }else{?>
<section style="background-color: #2c3e50;min-height: 200px">
	
</section>
<script type="text/javascript">
var main=function(){
bootbox.alert({
	title:'Reset Password',
	message: '<?php D(__('rest_password_invalid_link',"Your Change-Password Link Is Invalid."));?>',
	buttons: {
	'ok': {
		label: 'Ok',
		className: 'btn-primary float-end'
		}
	},
	callback: function () {
		window.location.href='<?php D(get_link('homeURL'))?>';
    }
});
}
</script>
<?php }?>
