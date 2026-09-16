<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script>
var VPATH = '<?php echo SITE_URL;?>';
</script>

<script src="<?php echo JS;?>mycustom.js" type="text/javascript"></script>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	<div class="row">
      <div class="col-sm-6 col-12">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
		 <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
	  </div>
      <div class="col-sm-6 col-12"><?php echo $breadcrumb ? $breadcrumb : '';?></div>
	</div>
    </section>

	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>
	
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      
	<div class="card">
        <div class="card-header text-center">
          <h3 class="card-title w-100"><?php echo $title ? $title : '';?></h3>
          <div class="card-tools">
			
          </div>
        </div>
       
		<div class="card-body table-responsive" id="main_table">
             <div class="general-form">
				<div class="general-body">
			   <form action="" method="post" accept-charset="utf-8" id="Register_form" class="form-horizontal" role="form" name="regform" onsubmit="return false;"> 
			   <input type="hidden" name="step" value="2" id="step"/>
				<div id="agree_termsError" class="error-msg5 error alert-error alert alert-danger" style="display:none"></div>
				<div id="step_1">			   			   
				<div class="input-with-icon-left">
					<i class="icon-feather-user"></i>    	         	
					<input type="text" class="form-control" value="" name="name" id="name" placeholder="Enter Name">
					<span id="nameError" class="rerror"></span>
				</div>
				<div class="input-with-icon-left">
					<i class="icon-feather-mail"></i>      	          	
					<input type="text" class="form-control" value="" name="email" id="email" placeholder="Enter Email Address">
					<span id="emailError" class="rerror"></span>
				</div>
				
				<!--<button class="btn btn-site btn-block mb-3 signUpBTN">Next</button>-->
				
			   </div>
				<div id="step_2">
				
				 <div class="m-lg-3 d-none d-sm-block"> </div>
				 <p id="select_email" class="text-center"></p>
					<div class="account-type">
						<div>
							<input type="radio" name="user_type" id="freelancer-radio" class="account-type-radio" value="F" checked  onclick="$('.for_individual').show()">
							<label for="freelancer-radio" class="ripple-effect-dark"><i class="icon-material-outline-account-circle"></i> Freelancer</label>
						</div>

						<div>
							<input type="radio" name="user_type" id="employer-radio" class="account-type-radio" value="E" onclick="$('.for_individual').hide()">
							<label for="employer-radio" class="ripple-effect-dark"><i class="icon-material-outline-business-center"></i> Employer</label>
						</div>
					</div>
					<div class="input-with-icon-left">
						<i class="icon-feather-map-pin"></i>       	                  
						<select name="country" id="country" class="form-control selectpicker" title="Select Country" data-live-search="true">
							<option value="">-Select Country-</option>
							<?php print_select_option($country, 'country_code', 'country_name'); ?>
						</select>          	

						<span id="countryError" class="rerror"></span>
					</div>
					<div class="input-with-icon-left for_individual"> 
						<i class="icon-feather-user"></i>       	    	
						<input type="text" class="form-control" value="" name="username" id="username" placeholder="Enter Username">
						<span id="usernameError" class="rerror"></span>
					</div>
					<div class="input-with-icon-left">  
						<i class="icon-feather-lock"></i>      	           	
						<input type="password" class="form-control" value="" name="password" id="password" placeholder="Enter Password">
						<span id="passwordError" class="rerror"></span>
					</div>
					 <button class="btn btn-site btn-block signUpBTN">Submit</button>
				</div>
				</form>
				</div>
				
			   </div>
        </div>
		 <!-- /.box-body -->
		
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>

<script>
/* function submitForm(form, evt){

	evt.preventDefault();

	ajaxSubmit($(form), onsuccess);

}



function onsuccess(res){

	if(res.cmd){

		if(res.cmd == 'reload'){

			location.reload();

		}else if(res.cmd == 'reset_form'){

			var form = $('#add_form');

			form.find('.reset_field').val('');

		}		
	}
} */
</script>

<script type="text/javascript">

function FormPost(e,formType,calback) {
	var url=VPATH;
	var formID="invalid";
	var buttonsection="invalid";
	var formdata=[];
	if(formType=='Login_form'){
		var url=url+"ajax/login-check";
		formID=$(e).closest('form').attr('id');
		formdata=$('#'+formID).serialize();
		buttonsection=$(e);
		buttonval = $(e).html();
	}else if(formType=='Register_form'){
		var url=url+"ajax/signup-check";
		formID=$(e).closest('form').attr('id');
		formdata=$('#'+formID).serialize();
		buttonsection=$(e);
		buttonval = $(e).html();
	}
	buttonsection.html('<svg class="MYspinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>').attr('disabled','disabled');
    $.ajax({
        type: "POST",
        url: url,
        data:formdata,
        dataType: "json",
        cache: false,
		success: function(msg) {
			buttonsection.html(buttonval).removeAttr('disabled');
			if (msg['status'] == 'OK') {
				if(msg['redirect']){
					$.alert({
						title: 'Success!',
						content: 'Account successfully created.',
						buttons: {
							ok: {
								text: 'Go to member list',
								btnClass: 'btn-primary',
								action: function(){
									location.href  = '<?php echo base_url('member/list_record'); ?>';
								}
							}
						}
					});
				}
				if(msg['calback']){
					var fnName = msg['calback'];
					var param=[];
					if(msg['calbackdata']){
						var params = msg['calbackdata'];
					}
					window[fnName](params);
					
				}else{
					$('#'+formnameid).trigger('reset');
					$('#'+formnameid).hide();
				}
				
				clearErrors();
				
			} else if (msg['status'] == 'FAIL') {
				registerFormPostResponse(formID,msg['errors']);
			}
		},
        error: function(msg) {
            buttonsection.html(buttonval).removeAttr('disabled');
        }
    });
    return false;
}

var main = function(){
	$('.signUpBTN').click(function(){
		FormPost(this,'Register_form');
	});	
}
function nextstep(res){
	var formD=$('#Register_form');
	//console.log(res);
	formD.find('.step_2 input').removeClass('is-valid');
	$('#select_email').html(res.email);
	formD.find('#step_1').hide();
	formD.find('#step_2').show();
	formD.find('#step').val(2);
}

main();
</script>