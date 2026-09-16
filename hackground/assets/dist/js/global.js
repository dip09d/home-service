

/* ------------------------------------------------------------- 
	
	Author : Venkatesh bishu
	Date : 30.11.18
	Version : 1.1

 ------------------------------------------------------------- */
 
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
	AJAX SUBMIT
 ------------------------------------------------------------- */
 
function ajaxSubmit(form, onSuccess, onError){
		
	var f_action = $(form).attr('action');
	var f_data = $(form).serialize();
	var submitBtn = $(form).find('[type="submit"]');
	
	$(form).find('.error').html('');
	$('.invalid').removeClass('invalid');
	$('.invalid_parent').removeClass('invalid_parent');
	
	
	$.ajax({
		url: f_action,
		type:'POST',
		beforeSend: function(){
			$(submitBtn).attr('disabled', 'disabled');
		},
		data: f_data,
		dataType: 'json',
		success: function(res){
			if(res.error_count > 0){
				
				for(var i in res.errors){
					$('#'+i+'Error').html(res.errors[i]);
					$('[name="'+i+'"]').addClass('invalid');
					$('[data-error-wrapper="'+i+'"]').addClass('invalid_parent');
				}
				
				if(typeof onError == 'function'){
					onError(res);
				}
				
			}else{
				
				if(typeof onSuccess == 'function'){
					onSuccess(res);
				}
				
			}
			
			$(submitBtn).removeAttr('disabled');
		},
	});	
}


 /* ------------------------------------------------------------- 
	PLUGIN INITILIZE
 ------------------------------------------------------------- */
 
function init_plugin(){
	//$('[data-toggle="tooltip"]').tooltip("hide");
	$('[data-toggle="tooltip"]').tooltip({boundary: 'scrollParent'});
	//$('.table').tooltip({ boundary: 'window' })	
	

}
/* $(document).ready(function () {
	$('[data-toggle="tooltip"]').tooltip({
		boundary: 'scrollParent'
	})
});
	 */
function starRating(ratingElem) {

	$(ratingElem).each(function() {

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
$(document).ready(function () {
starRating('.star-rating');
if ($(this).width() > 992) {
	var h=$(window).height()-$('footer').height()-$('.main-header').height();
	$('.content-wrapper').css({ height:h+"px"});
}
});
