/* ------------------------------------------------------------- 
	
	Author : Venkatesh bishu
	Date : 22.11.18
	Version : 1.0

/* ---------------------------------------------------------------------- */

function c_alert(title, content, type, button){
	if(button){
		$.alert({
		theme: 'bootstrap',
		closeIcon: true,
		animation: 'scale',
		type: type || 'green',
		title: title || 'Alert!',
		content: content || 'Simple alert!',
		buttons : button
	});
	}else{
		$.alert({
			theme: 'bootstrap',
			closeIcon: true,
			animation: 'scale',
			type: type || 'green',
			title: title || 'Alert!',
			content: content || 'Simple alert!',
		});
	}
	
};

/* ------------------------------------------------------------- 
	LOADER
 ------------------------------------------------------------- */
 
function generateLoader(size, speed){
	var default_size = 100;
	var default_speed = 1.5;
	size = size || default_size;
	speed = speed || default_speed;
	var html = '<svg width="'+size+'px"  height="'+size+'px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-double-ring" style="background: none;"><circle cx="50" cy="50" ng-attr-r="{{config.radius}}" ng-attr-stroke-width="{{config.width}}" ng-attr-stroke="{{config.c1}}" ng-attr-stroke-dasharray="{{config.dasharray}}" fill="none" stroke-linecap="round" r="40" stroke-width="4" stroke="#2a41e8" stroke-dasharray="62.83185307179586 62.83185307179586" transform="rotate(238.536 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="'+default_speed+'s" begin="0s" repeatCount="indefinite"></animateTransform></circle><circle cx="50" cy="50" ng-attr-r="{{config.radius2}}" ng-attr-stroke-width="{{config.width}}" ng-attr-stroke="{{config.c2}}" ng-attr-stroke-dasharray="{{config.dasharray2}}" ng-attr-stroke-dashoffset="{{config.dashoffset2}}" fill="none" stroke-linecap="round" r="35" stroke-width="4" stroke="#000" stroke-dasharray="54.97787143782138 54.97787143782138" stroke-dashoffset="54.97787143782138" transform="rotate(-238.536 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;-360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform></circle></svg>';

	return html;
}
 
function showLoader(container, type, container_h){
	var loader = generateLoader();
	container_h = container_h || 100;
	if(type == 'sm'){
		loader = generateLoader(25);
	}else if(type == 'lg'){
		loader = generateLoader(100);
	}else if(type == 'md'){
		loader = generateLoader(80);
	}else{
		loader = generateLoader(50);
	}
	
	$(container).html('<div class="loader" style="height:'+container_h+'px">'+loader+'</div>');
	
}
 
 
 /* ------------------------------------------------------------- 
	NEW WINDOW 
 ------------------------------------------------------------- */
function newWindow(url) {
    window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=100,width=700,height=400");
}
 
 
 /* ------------------------------------------------------------- 
	AJAX MODAL
 ------------------------------------------------------------- */
function load_ajax_modal(url){
	showLoader($('#ajaxModal').find('.modal-content'), '', 100);
	$('#ajaxModal').modal('show');
	setTimeout(function(){
		$.get(url, function(res){
			$('#ajaxModal').find('.modal-content').html(res);
		});
	}, 700);
};

 /* ------------------------------------------------------------- 
	STAR RATING
 ------------------------------------------------------------- */

function starRating(ratingElem) {
$(ratingElem).each(function() {
$(this).empty();
var dataRating = $(this).attr('data-rating');

// Rating Stars Output
function starsOutput(firstStar, secondStar, thirdStar, fourthStar, fifthStar) {
return(''+
'<span class="'+firstStar+'"></span>'+
'<span class="'+secondStar+'"></span>'+
'<span class="'+thirdStar+'"></span>'+
'<span class="'+fourthStar+'"></span>'+
'<span class="'+fifthStar+'"></span>');
}

var fiveStars = starsOutput('star','star','star','star','star');

var fourHalfStars = starsOutput('star','star','star','star','star half');
var fourStars = starsOutput('star','star','star','star','star empty');

var threeHalfStars = starsOutput('star','star','star','star half','star empty');
var threeStars = starsOutput('star','star','star','star empty','star empty');

var twoHalfStars = starsOutput('star','star','star half','star empty','star empty');
var twoStars = starsOutput('star','star','star empty','star empty','star empty');

var oneHalfStar = starsOutput('star','star half','star empty','star empty','star empty');
var oneStar = starsOutput('star','star empty','star empty','star empty','star empty');
var HalfStar = starsOutput('star half','star empty','star empty','star empty','star empty');
var zeroStar = starsOutput('star empty','star empty','star empty','star empty','star empty');

// Rules
        if (dataRating >= 4.75) {
            $(this).append(fiveStars);
        } else if (dataRating >= 4.25) {
            $(this).append(fourHalfStars);
        } else if (dataRating >= 3.75) {
            $(this).append(fourStars);
        } else if (dataRating >= 3.25) {
            $(this).append(threeHalfStars);
        } else if (dataRating >= 2.75) {
            $(this).append(threeStars);
        } else if (dataRating >= 2.25) {
            $(this).append(twoHalfStars);
        } else if (dataRating >= 1.75) {
            $(this).append(twoStars);
        } else if (dataRating >= 1.25) {
            $(this).append(oneHalfStar);
        } else if (dataRating > .75) {
            $(this).append(oneStar);
        } else if (dataRating > .25) {
            $(this).append(HalfStar);
        }else{
$(this).append(zeroStar);
}

});
}

function init_rating(){
	starRating('.star-rating');
}

 /* ------------------------------------------------------------- 
	AJAX FORM PLUGIN
 ------------------------------------------------------------- */
 
$.fn.ajaxSubmit = function(options){
	
	var default_setting = {
		validate : true,
		url : {
			validate: VPATH + 'ajax/validate_input',
			submit: null,
		},
		submitBtn: null,
		formType: null,
		success: successHandler
	};
	
	var setting = $.extend(true, default_setting, options);
	var loader  = generateLoader(35);
	var submit_btn_txt = null; 
	
	var submitData = function(data){
		$.ajax({
			url : setting.url.submit,
			data : data,
			type : 'POST',
			dataType: 'JSON',
			success: function(res){
				
				if(res.error_length > 0){
					
					var errors = res.errors;
					for(var i in errors){
						$('[name="'+i+'"]').addClass('incorrect');
						$('[data-error-wrapper="'+i+'"]').addClass('incorrect_parent');
						if(i.indexOf('[') === -1){
							$('#'+i+'Error').html(errors[i]);
						}
					}
					
				}else{
					
					setting.success(res);
				}
				
				if(setting.submitBtn){
					$(setting.submitBtn).removeAttr('disabled');
					$(setting.submitBtn).html(submit_btn_txt);
				}
				
			},
			error: function(){
				if(setting.submitBtn){
					$(setting.submitBtn).removeAttr('disabled');
					$(setting.submitBtn).html(submit_btn_txt);
				}
			}
		});
	};
	
	function successHandler(res){
		API.Execute(res.cmd, res.cmd_params);
	}

	$(this).on('submit', handleSubmit);

	function handleSubmit(e){
		
		e.preventDefault();
		var form_data = $(this).serialize();
		
		if(setting.submitBtn){
			submit_btn_txt = $(setting.submitBtn).html();
			$(setting.submitBtn).attr('disabled', 'disabled');
		}
		
		if(setting.submitBtn){
			
			$(setting.submitBtn).html(loader);
		}
		
		$('.incorrect').removeClass('incorrect');
		$('.incorrect_parent').removeClass('incorrect_parent');
		$('.invalid').empty();
		
		if(setting.validate){
			$.ajax({
				url : setting.url.validate + '?type=' + setting.formType,
				data : form_data,
				type : 'POST',
				dataType: 'JSON',
				success: function(res){
					
					if(res.error_length > 0){
						
						var errors = res.errors;
						for(var i in errors){
							$('[name="'+i+'"]').addClass('incorrect');
							$('[data-error-wrapper="'+i+'"]').addClass('incorrect_parent');
							if(i.indexOf('[') === -1){
								$('#'+i+'Error').html(errors[i]);
								$('#'+i+'Error').addClass('invalid');
							}
						}
						
					}else{
						
						submitData(form_data);
						
					}
					
					if(setting.submitBtn){
						$(setting.submitBtn).removeAttr('disabled');
						$(setting.submitBtn).html(submit_btn_txt);
					}
				},
				error: function(){
					if(setting.submitBtn){
						$(setting.submitBtn).removeAttr('disabled');
						$(setting.submitBtn).html(submit_btn_txt);
					}
				}
			});
			
		}else{
			submitData(form_data);
		}
	
	}
	
	
};


 /* ------------------------------------------------------------- 
	API Handler
 ------------------------------------------------------------- */
 
 var API = (function($, global){
	
	var command_list = {
		
		redirectToDashboard: function(){
			location.href = VPATH + 'dashboard';
		},
		
		redirect: function(param){
			location.href = param.url;
		},
		
		reloadPage: function(){
			location.reload();
		},
		
		reload: function(){
			location.reload();
		},
		
		redirectToLogin: function(){
			location.href = VPATH + 'login';
		},
		
	};
	
	
	var Execute = function(cmd, param){
		
		if(cmd){
			var command = cmd;
			var command_param = param;
			
			var cmd_fn = command_list[command] || function(p){ console.log('CMD_NOT_FOUND : '+ cmd) };
			cmd_fn(command_param);
		}
		
	};
	
	return {
		Execute : Execute
	}
	
 })(jQuery, window);
 
 /* ------------------------------------------------------------- 
	get Category Function ( Dependency : Promise.js)
 ------------------------------------------------------------- */
 
function getCategory(parent){
	var parent_id = parent || 0;
	
	return new Promise(function(res){
		$.getJSON(VPATH + 'ajax/getCategory?parent_id='+parent_id, function(data){
			res(data);
		});
	});
	
}

function getState(country){
	var country_id = country || null;
	
	return new Promise(function(res){
		$.getJSON(VPATH + 'ajax/location?type=state&country_id='+country_id, function(data){
			res(data);
		});
	});
	
}

function getCity(state){
	var state_id = state || null;
	
	return new Promise(function(res){
		$.getJSON(VPATH + 'ajax/location?type=city&state_id='+state_id, function(data){
			res(data);
		});
	});
	
}
function getCountry(){
	return new Promise(function(res){
		$.getJSON(VPATH + 'ajax/location?type=country', function(data){
			res(data);
		});
	});
	
}

function print_select_option(parent, options, key, val, selected){
	var html = '<option value="">Select</option>';
	selected = selected || '';
	for(var i in options){
		if(options[i][key] == selected){
			html += '<option value="'+options[i][key]+'" selected>'+options[i][val]+'</option>';
		}else{
			html += '<option value="'+options[i][key]+'">'+options[i][val]+'</option>';
		}
	}
	$(parent).html(html);
}



function set_lang(lang){
	var url = VPATH+'ajax/set_lang';
	$.ajax({
		url : url,
		data : {lang : lang},
		type: 'POST',
		dataType: 'JSON',
		success: function(res){
			location.reload();
		}
	});
}

function is_image(file){
	var image_files = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
	if(image_files.indexOf(file.type) == -1){
		return false;
	}else{
		return true;
	}
	
}






















 