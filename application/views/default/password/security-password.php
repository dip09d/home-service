<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="dashboard-container"> <?php echo $left_panel;?> 
  <!-- Dashboard Content
	================================================== -->
  <div class="dashboard-content-container" >
    <div class="dashboard-content-inner" > 
      
      <!-- Dashboard Headline -->
      <div class="dashboard-headline">
        <h3><?php echo __('password_security_password','Password');?> &amp; <?php echo __('password_security','Security');?> </h3>
      </div>
            
      <!-- Dashboard Box -->
      <div class="dashboard-box margin-top-0"> 
        
        <!-- Headline -->
        <div class="headline">
          <h3><?php echo __('password_security_password','Password');?> <a href="<?php echo VZ;?>" class="btn-circle ico edit_password" data-tippy-placement="top"  title="Edit"><i class="icon-feather-edit-2"></i></a></h3>
        </div>
        <div class="content with-padding" id="passwordLoad">
          <p><i class="icon-material-outline-check-circle text-success"></i> <?php echo __('password_securit_password_set','Password has been set');?> </p>
          <p class="text-muted"><i><?php echo __('password_securit_choose_strong','Choose a strong, unique password that’s at least 6 characters long.');?></i></p>
        </div>
        <div class="content with-padding" id="passwordLoadForm" style="display: none"></div>
      </div>
      <div class="dashboard-box d-none"> 
        
        <!-- Headline -->
        <div class="headline">
          <h3><i class="icon-material-outline-account-circle"></i> <?php echo __('password_securit_two_step','Two-step verification');?>  </h3>
        </div>
        <div class="content with-padding">
          <div   id="verifyLoad">
            <h5><?php echo __('password_securit_question','Security question');?></h5>
            <p><i class="icon-material-outline-check-circle text-success"></i> <?php echo __('password_securit_question_enabled','Security question has been enabled');?> </p>
            <p><?php echo __('password_securit_confirm_identity','Confirm your identity with a question only you know the answer to.');?></p>
          </div>
        </div>
        <div class="content with-padding padding-bottom-0" id="verifyForm" style="display: none"></div>
      </div>
      
      <!-- Footer -->
      <div class="dashboard-footer-spacer"></div>
      
      <!-- Footer / End --> 
      
    </div>
  </div>
  <!-- Dashboard Content / End --> 
  
</div>
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs"> 
  
  <!--Tabs -->
  <div class="sign-in-form">
    <ul class="popup-tabs-nav">
      <li><a href="#tab1"><?php echo __('password_securit_C_new_account','Create New Account');?></a></li>
    </ul>
    <div class="popup-tabs-container"> 
      
      <!-- Tab -->
      <div class="popup-tab-content" id="tab"> 
        
        <!-- Welcome Text -->
        <div class=""> <a href="" class="btn padding-left-0"><?php echo __('password_securit_new_client','+ New Client Account');?></a>
          <p><?php echo __('password_securit_differnt_ways','Creating a new account allows you to use Upwork in different ways, while still having just one login. Learn more ');?></p>
          <a href="" class="btn padding-left-0 margin-top-10"><?php echo __('password_securit_new_freelancer','+ New Freelancer Agency Account');?></a>
          <p><?php echo __('password_securit_manage_pay','Hire, manage and pay as a different company. Each client company has its own freelancers, payment methods and reports');?></p>
        </div>
        
        <!-- Button --> 
        
      </div>
    </div>
  </div>
</div>
<div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs"> 
  
  <!--Tabs -->
  <div class="sign-in-form">
    <ul class="popup-tabs-nav">
      <li><a href="#tab1"><?php echo __('password_securit_accept_offer','Accept Offer');?></a></li>
    </ul>
    <div class="popup-tabs-container"> 
      
      <!-- Tab -->
      <div class="popup-tab-content" id="tab"> 
        
        <!-- Welcome Text -->
        <div class="welcome-text">
          <h3><?php echo __('password_securit_accept_offer_F','Accept Offer From David');?></h3>
          <div class="bid-acceptance margin-top-15"> $3200 </div>
        </div>
        <form id="terms">
          <div class="radio">
            <input id="radio-1" name="radio" type="radio" required>
            <label for="radio-1"><span class="radio-label"></span><?php echo __('password_securit_read_agree','I have read and agree to the Terms and Conditions');?> </label>
          </div>
        </form>
        
        <!-- Button -->
        <button class="margin-top-15 button full-width button-sliding-icon" type="submit" form="terms"><?php echo __('password_securit_accept','Accept');?> <i class="icon-material-outline-arrow-right-alt"></i></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
	function load_password(){
		$( "#passwordLoadForm").hide();
		 $( "#passwordLoad").show();$('.edit_password').show();
	}
	function load_password_form(){
		$( "#passwordLoad").hide();
		$( "#passwordLoadForm").html('<div class="text-center" style="min-height: 100px">'+SPINNER+'<div>').show();
		$.get( "<?php D(get_link('settingchangepasswordFormAJAXURL'))?>", function( data ) {
			setTimeout(function(){ $( "#passwordLoadForm").html( data );},2000)
		});
	}
	function updatePassword(){
		var buttonsection=$('.passwordUpdateBTN');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="changepasswordform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('settingchangepasswordFormCheckAJAXURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$( "#passwordLoadForm").html('<div class="text-center" style="min-height: 100px">'+SPINNER+'<div>').hide();
					load_password();
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
	}
var main=function(){

	$('.edit_password').on('click',function(){
		$(this).hide();
		load_password_form();
	})
	$('body').on('click','#cancel_password',function(){
		$( "#passwordLoadForm").hide();
		$( "#passwordLoad").show();
		$('.edit_password').show();
	});
}
	
</script>