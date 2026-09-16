/*
	Author 	: 	Venkatesh bishu

	-----DOCUMENTATION ------------
	--------------------------------
	form_validation data attributes : 
	data-rules : The ruels for validation field.
				 Currently available rules (required,min_length[length],max_length[length],exact_length[length],is_numeric)
	data-error-el : A reference to the error element of of selected field.
	Example : <input type="text" name="f2" id="f2" data-rules="required|min_length[5]|max_length[10]" data-error-el="#f2_error"/><span id="f2_error"></span>
	data-field = The field name displayed in error message
	---------------------------------------------------------------
	To perform a from validation action on input field
	First set the Field or array of fields by using form_validation.setField(fields) functions . (i.e : form_validation.setField($('input')); )
	if you want to perform a check on form then use form_validation.checkField() function. This function check all the fields and if any fields are empty then the submit button will be disabled otherwise enabled

	For form validation use form_validation.validateForm() on submit button. It will return either true or false.
	
*/


	var form_validation = {};
	form_validation.field = null;
	form_validation.submitbutton = '[type="submit"]';
	form_validation.setField = function(field){
		form_validation.field = field;
	}
	form_validation.checkField = function(){
		// fetching all fields
		form_validation.disabledsubmit = false;
		for(var i=0; i < form_validation.field.length; i++){
			if($(form_validation.field[i]).val().trim() == ''){
				form_validation.disabledsubmit = true; // button disable set to true
			}
		}
		if(form_validation.disabledsubmit){
			$(form_validation.submitbutton).attr('disabled' , 'disabled'); // disable submit button
		}else{
			$(form_validation.submitbutton).removeAttr('disabled'); // remove disabled submit button
		}
	}

	form_validation.validationRules = {
		required : function(str){
			if(str.trim() == ''){
				form_validation.form_error = true;
				return false;
			}
			return true;
		},

		min_length: function(val , l){
				l = parseInt(l);
				if(val.length < l){
					form_validation.form_error = true;
					return false;
				}
				return true;
		},

		max_length: function(val , l){
				l = parseInt(l);
				if(val.length > l){
					form_validation.form_error = true;
					return false;
				}
				return true;
		},
		exact_length: function(val , l){
				l = parseInt(l);
				if(val.length != l){
					form_validation.form_error = true;
					return false;
				}
				return true;
		},
		is_numeric: function(val){
				if(isNaN(val)){
					form_validation.form_error = true;
					return false;
				}
				return true;
		},
		match: function(val , field){
				field_val = $('[name="'+field+'"]').val();
				if(field_val != val){
					form_validation.form_error = true;
					return false;
				}
				return true;
		},
		valid_email: function(val){
			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if(re.test(val) == false){
				form_validation.form_error = true;
				return false;
			}
			return true;
		}
	};

	form_validation.validationErrors = {
		required : 'Required field',
		min_length : 'The {field} must be greater than {length}',
		max_length : 'The {field} must be less than {length}',
		exact_length : 'The {field} must be {length} in length',
		is_numeric : 'The {field} must be numeric',
		valid_email : 'Invalid email',
		match : 'The {field} do not match',
	}

	form_validation.validateForm = function(){
		form_validation.form_error = false;
		for(var i=0; i < form_validation.field.length; i++){
			var rules = $(form_validation.field[i]).attr('data-rules'); // fetching fields rules
			if(typeof rules == 'undefined'){
				continue;
			}
			form_validation.rules = rules.split('|'); 
			if(form_validation.rules.length > 0){
				for(var j=0; j<form_validation.rules.length; j++){
					var form_val =  $(form_validation.field[i]).val(); // collect value;
					if(form_validation.rules[j].indexOf('[') != -1){
						var fn_nm = form_validation.rules[j].substring(0 , form_validation.rules[j].indexOf('['));
					    var fn_param = form_validation.rules[j].substring(form_validation.rules[j].indexOf('[')+1 , form_validation.rules[j].indexOf(']'));
					    var c = eval("form_validation.validationRules."+fn_nm+"('"+form_val+"' , '"+fn_param+"')");
						var err_f = $(form_validation.field[i]).attr('data-error-el');
						if(!c){
							var field_nm =  $(form_validation.field[i]).attr('data-field');
							if(typeof field_nm == 'undefined'){
								field_nm = $(form_validation.field[i]).attr('name');
							}
							var error_msg = form_validation.validationErrors[fn_nm].replace("{field}" , field_nm);
							error_msg = error_msg.replace("{length}" , fn_param);
							$(err_f).html(error_msg);
							break;
						}else{
							$(err_f).html('');
						}
					}else{
						var c = eval("form_validation.validationRules."+form_validation.rules[j]+"('"+form_val+"')");
						var err_f = $(form_validation.field[i]).attr('data-error-el');
						if(!c){
							var field_nm =  $(form_validation.field[i]).attr('data-field');
							if(typeof field_nm == 'undefined'){
								field_nm = $(form_validation.field[i]).attr('name');
							}
							var error_msg = form_validation.validationErrors[form_validation.rules[j]].replace("{field}" , field_nm);
							$(err_f).html(error_msg);
							break;
						}else{
							$(err_f).html('');
						}
					}
					
				}
			}
		}

		// check form error
		if(form_validation.form_error){
			return false;
		}
		return true;
	}
