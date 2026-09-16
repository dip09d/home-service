var error_icon='<span class=" icon-line-awesome-exclamation-circle" aria-hidden="true"></span>';
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
	}else if(formType=='Register_form_agency'){
		var url=url+"ajax/signup-check-agency";
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
					window.location.replace( msg['redirect']);
					return false;
				}
				if(msg['calback']){
					var fnName = msg['calback'];
					var param=[];
					if(msg['calbackdata']){
						var params = msg['calbackdata'];
					}
					window[fnName](params);
					//var fn = window[msg['calback']];

				    // is object a function?
				    /*if (typeof fn === "function"){
				    	 fn.apply(null, msg['calbackdata']);
				    	 fn();
				    };*/

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
 function registerFormPostResponse(formnameid,errors) {
    clearErrors();
    $('#'+formnameid+' input[type="text"] , #'+formnameid+' input[type="password"], #'+formnameid+' input[type="date"], #'+formnameid+' input[type="number"] , #'+formnameid+' textarea').removeClass('is-invalid').addClass('is-valid');
    if (errors.length > 0) {
        for (i = 0; i < errors.length; i++) {
            showError(formnameid,errors[i].id, errors[i].message);
        }
    }
	/*var error_ele = $('.rerror').not(':empty').offset();
	if(error_ele){
		$(window).scrollTop(error_ele.top - 150);
	}*/
}
function clearErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.rerror').hide();
    $('.invalid-feedback').removeClass('invalid-feedback');
}
function showError(formnameid,field,message) {
	$('#'+formnameid+' #'+field).addClass('is-invalid');
	$('#'+formnameid+' #'+field+'Error').addClass('invalid-feedback').html(error_icon+' '+message).show();
	if($("#"+formnameid+" input[name=recaptcha_response_field]").length){
		Recaptcha.reload();
	}
}
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
   /* console.log(charCode);*/
    if(charCode=='46'){
		 return true;
	}else if (charCode > 31 && (charCode < 48 || charCode > 57)){
       return false;
    }
    else{
      return true;
    }
}
function loadtooltip(){
	tippy('[data-tippy-placement]', {
		delay: 100,
		arrow: true,
		arrowType: 'sharp',
		size: 'regular',
		duration: 200,
		// 'shift-toward', 'fade', 'scale', 'perspective'
		animation: 'shift-away',
		animateFill: true,
		theme: 'dark',
		// How far the tooltip is from its reference element in pixels 
		distance: 10,
	});
}
