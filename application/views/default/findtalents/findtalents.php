<section class="section findtalentpage">
<div class="container">
	<div class="dashboard-headline">
    	<h1><?php echo __('findtalents_page_header','Professionals');?></h1>
    </div>
	<?php //print_r($all_location);?>
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-12">
        	<a href="javascript:void(0)" class="d-lg-none" id="filter" title="Filter"><i class="icon-feather-filter f20"></i></a>
			<div class="sidebar-container">	
			<form id="filterForm">			
				<!-- Location -->
				<div class="sidebar-widget">
					<h5><?php echo __('findtalents_page_location','Location');?></h5>
					<select name="country" class="selectpicker default"  title="All locations"  data-live-search="true">
						<option value=""><?php echo __('findtalents_page_all','All');?></option>
						<?php if($all_location){
							foreach($all_location as $l=>$location){
								?>
							<option value="<?php echo ($location->country_code);?>" <?php if($searchdata && array_key_exists('country',$searchdata) && $searchdata['country']==($location->country_code)){echo 'selected';}?> ><?php echo $location->country_name;?></option>
								<?php
							}
						}?>
					</select>
				</div>

			

				<!-- Keywords -->
				<div class="sidebar-widget" hidden>
					<h5><?php echo __('findtalents_page_keyword','Keywords');?></h5>
					<div class="keywords-container">
						<div class="keyword-input-container">
							<input type="text" class="form-control keyword-input" placeholder="e.g. task title"/>
							<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
						</div>
						<div class="keywords-list"><!-- keywords go here --></div>
						<div class="clearfix"></div>
					</div>
				</div>

				

				<!-- Tags -->
				<div class="sidebar-widget">
					<h5><?php echo __('findtalents_page_skills','Skills');?></h5>

					<div class="tags-container skillContaintag">
						<?php
						if($searchdata && array_key_exists('byskillsname',$searchdata)){
							if($searchdata['pre_skills']){
								foreach($searchdata['pre_skills'] as $k=>$preskills){
						?>
						<div class="tag skill_set_<?php echo $preskills->skill_id;?>" onclick="filterForm()">
							<input type="checkbox" id="tag_<?php echo $preskills->skill_id;?>" name="byskillsname[]" value="<?php echo $preskills->skill_key;?>" checked>
							<label for="tag_<?php echo $preskills->skill_id;?>"><?php echo $preskills->skill_name;?></label>
						</div>
						<?php
								}
							}
						}
						?>
					</div>
					<div class="clearfix"></div>

					<!-- More Skills -->
					<div class="submit-field">
					<input type="text"  class="form-control input-text with-border tagsinput_skill" placeholder="<?php echo __('findtalents_page_skills_placeholder','skills');?>"/>
					</div>
				</div>
				<!-- Hourly Rate -->
				<div class="sidebar-widget">
					<h5><?php echo __('findtalents_page_hourly','Hourly Rate');?></h5>
					<div class="mt-4"></div>

					<!-- Range Slider -->
					<input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="0" data-slider-max="<?php echo $max_houry_rate;?>" data-slider-step="5" data-slider-value="[0,<?php echo $max_houry_rate;?>]"/>
					<input type="hidden" name="min" id="min">
					<input type="hidden" name="max" id="max">
				</div>
				<div class="clearfix"></div>
			</form>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-12">

		<h3 class="page-title"><?php echo __('findtalents_page_search_result','Search Results');?></h3>
        <div class="row">
    		<div class="col-md-8 col-12">
            <div class="search-box input-group">
				<input type="text" class="form-control" value="<?php if($searchdata && array_key_exists('term',$searchdata)){echo $searchdata['term'];}?>" name="term" placeholder="<?php echo __('findtalents_page_talent_by_name','Find talents by name');?>" form="filterForm"/>
                <button type="button" class="btn btn-primary" onclick="filterForm()"><?php echo __('findtalents_page_search','Search');?></button>
			</div>
		</div>
    	<div class="col-md-4 col-12">
            <div class="sort-by">
            	<div class="sort-by">
                <span><?php echo __('findtalents_page_sort_by','Sort by:');?></span>
				<select class="selectpicker hide-tick" name="order_by" form="filterForm" onchange="filterForm()">
						<option value="default" <?php if($searchdata && array_key_exists('order_by',$searchdata) && $searchdata['order_by']=='default'){echo 'selected';}?>><?php echo __('findtalents_page_relevance','Relevance');?></option>
                        <option value="default" <?php if($searchdata && array_key_exists('order_by',$searchdata) && $searchdata['order_by']=='rating'){echo 'selected';}?>><?php echo __('findtalents_page_rating','Rating');?></option>
                        <option value="latest" <?php if($searchdata && array_key_exists('order_by',$searchdata) && $searchdata['order_by']=='latest'){echo 'selected';}?>><?php echo __('findtalents_page_newest','Newest');?></option>
                        <option value="old" <?php if($searchdata && array_key_exists('order_by',$searchdata) && $searchdata['order_by']=='old'){echo 'selected';}?>><?php echo __('findtalents_page_oldest','Oldest');?></option>
                        <!--<option value="random">Random</option>-->
                    </select>
                </div>
            </div>
		</div>
        </div>
			
            

			
			<!-- Freelancers List Container -->		
            <div class="listings-container mt-4" id="talent_list">
				
				
			</div>
			<!-- Freelancers List Container / End -->
			
			<div class="text-center"><button class="btn btn-primary" id="load_more_btn"><?php echo __('findtalents_page_load_more','Load More');?></button></div>
			
			<!-- Pagination -->
					<div class="pagination-container margin-top-20" hidden>
						<nav class="pagination">
							<ul>
								<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
								<li><a href="#" class="ripple-effect">1</a></li>
								<li><a href="#" class="current-page ripple-effect">2</a></li>
								<li><a href="#" class="ripple-effect">3</a></li>
								<li><a href="#" class="ripple-effect">4</a></li>
								<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
							</ul>
						</nav>
					</div>			
			<!-- Pagination / End -->

		</div>
	</div>
</div>
</section>
<div id="myModal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
    <div class="text-center padding-top-50 padding-bottom-50"><?php load_view('inc/spinner',array('size'=>30));?></div>
    </div>

  </div>
</div>
<script>
var all_skills=<?php D(json_encode($all_skills));?>;
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
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

function check_login(succ, fail){
	$.get('<?php echo get_link('IsLoginURL'); ?>', function(res){
		if(res == 1){
			if(typeof succ == 'function'){
				succ();
			}
		}else{
			if(typeof fail == 'function'){
				fail();
			}
		}
	});
}
var main = function(){
	$( ".range-slider" ).on( "slideStop", function( event ) {
		var data=event.value;
		$('#min').val(data[0]);
		$('#max').val(data[1]);
		filterForm();
	} );

	var bhtn = new Bloodhound({
		local:all_skills,
	        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('skill_name'),
	  		queryTokenizer: Bloodhound.tokenizers.whitespace,
	});
	var elts = $('.tagsinput_skill');
	elts.tagsinput({
	  itemValue: 'skill_id',
	  itemText: 'skill_name',
	  typeaheadjs: {
	  	limit: 25,
	  	displayKey: 'skill_name',
	    hint: false,
	    highlight: true,
	    minLength: 1,
	    source: bhtn.ttAdapter(),
	    templates: {
	      notFound: [
	        "<div class=empty-message>",
	        "<?php D('No match found')?>",
	        "</div>"
	      ].join("\n"),
	      suggestion: function(e) {  var test_regexp = new RegExp('('+e._query+')' , "gi"); return ('<div>'+ e.skill_name.replace(test_regexp,'<b>$1</b>')  + '</div>'); }
	    }
	  }
	});
	elts.on('beforeItemAdd', function(event) {
		var itemdata=event.item;
		console.log(itemdata);
		var key=itemdata.skill_id;
		var name=key;
		if($(".skill_set_"+key).length>0){
			var html='<input type="checkbox" id="tag_'+key+'"  name="byskillsname[]" value="'+itemdata.skill_key+'" checked/><label for="tag_'+key+'">'+itemdata.skill_name+'</label>';
			$(".skillContaintag skill_set_"+key).html(html);
		}else{
			var html='<div class="tag skill_set_'+key+'" onclick="filterForm()"><input type="checkbox" id="tag_'+key+'"  name="byskillsname[]" value="'+itemdata.skill_key+'" checked/><label for="tag_'+key+'">'+itemdata.skill_name+'</label></div>';
			$(".skillContaintag").append(html);
		}
		filterForm();
		//console.log(event.item);
		event.cancel = true;
	})

	var findJobLoadMore = LoadMore.getInstance();
	
	/* findJobLoadMore.config({
		url : '<?php echo base_url('job/job_list_ajax');?>',
		target : '#job_list',
		load_more : '#load_more_btn',
		autoload: {
			autoload: false,
			target: 'window'
		},
		onResult: function(res){
		
		}
	});

	findJobLoadMore.start(); */
	
	function filterForm(){
		setTimeout(() => {
			
		
		$('#talent_list').html('');
		var form = $('#filterForm').serialize();
		
		findJobLoadMore.config({
			url : '<?php echo base_url('findtalents/talent_list_ajax');?>?'+form,
			target : '#talent_list',
			load_more : '#load_more_btn',
			autoload: {
				autoload: false,
				target: 'window'
			},
			onResult: function(res){
				starRating('.star-rating');
				loadtooltip();
				history.pushState({}, null,  '<?php echo URL::get_link('search_freelancer'); ?>?'+form);
			}
		});
	
		findJobLoadMore.start();
	}, 100);
	}
	
	window.filterForm = filterForm;
	filterForm();
	
	// get sub category
	$('[name="country"]').change(function(){
		filterForm();
		/*var val = $('[name="country"] :selected').val();
		 $.ajax({
			url : '<?php echo base_url('job/get_sub_category')?>',
			data: {category_id: val},
			dataType: 'JSON',
			success: function(res){
				if(res.error_length > 0){
					
				}else{
					var category = res.data.category;
					var category_html = category.map(function(item){
						return '<option value="'+item.category_subchild_id+'">'+item.category_subchild_name+'</option>';
					});
					$('#sub_category_wrapper').show();
					$('#sub_category').html('<option value="">All</option>' + category_html.join(''));
					$('#sub_category').selectpicker('refresh');
				}
				filterForm();
			}
		}); */
	});
	$('#talent_list').on('click', '.action_favorite',function(e){
		e.preventDefault();
		var _self=$(this);
		var data = {
			mid: _self.data('mid'),
		};
		$.post('<?php echo get_link('actionfavorite_freelancer'); ?>', data, function(res){
			if(res['status'] == 'OK'){
				if(res['cmd']== 'add'){
					_self.addClass('active');
					bootbox.alert({
						title:'Make Favorite',
						message: 'Successfully Saved',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
				}else{
					_self.removeClass('active');
					bootbox.alert({
						title:'Remove Favorite',
						message: 'Successfully Removed',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
					
				}
			}else if(res['popup'] == 'login'){
				bootbox.confirm({
					title:'<?php echo __('findtalents_login_error','Login Error!');?>',
					message: '<?php echo __('findtalents_login_error_msg','You are not Logged In. Please login first.');?>',
					buttons: {
					'confirm': {
						label: 'Login',
						className: 'btn-primary float-end'
						},
					'cancel': {
						label: 'Cancel',
						className: 'btn-dark float-start'
						}
					},
					callback: function (result) {
						if(result){
							var base_url = '<?php echo base_url();?>';
							var refer = window.location.href.replace(base_url, '');
							location.href = '<?php echo base_url('login?refer='); ?>'+refer;
						}
					}
				});
			}
		},'JSON');
		
	})
	$('#talent_list').on('click', '.hire-member',function(e){
		e.preventDefault();
		var _self=$(this);
		var data = {
			mid: _self.data('mid'),
			formtype:'hire'
		};
		var hiremember = function(){
				$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
				$('#myModal').modal('show');
				
				$.get("<?php echo get_link('HireInviteFreelanceFormURL'); ?>",data, function( data ) {
					setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );$('.selectpicker').selectpicker('refresh');},1000)
				});
		};
		var login_error = function(){
			bootbox.confirm({
				title:'<?php D(__('project_view_Save_Search_login_error','Login Error!'));?>',
				message: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
				buttons: {
				'confirm': {
					label: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
					className: 'btn-primary float-end'
					},
				'cancel': {
					label: '<?php D(__('project_view_save_search_button_cancel','Cancel'));?>',
					className: 'btn-dark float-start'
					}
				},
				callback: function (result) {
					if(result){
						var base_url = '<?php echo base_url();?>';
						var refer = window.location.href.replace(base_url, '');
						location.href = '<?php echo base_url('login?refer='); ?>'+refer;
					}
				}
			});
		};

		check_login(hiremember, login_error);
		
	})
	$('#talent_list').on('click', '.invite-member',function(e){
		e.preventDefault();
		var _self=$(this);
		var data = {
			mid: _self.data('mid'),
			formtype:'invite'
		};
		var invitemember = function(){
				$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
				$('#myModal').modal('show');
				
				$.get("<?php echo get_link('HireInviteFreelanceFormURL'); ?>",data, function( data ) {
					setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );$('.selectpicker').selectpicker('refresh');},1000)
				});
		};
		var login_error = function(){
			bootbox.confirm({
				title:'<?php D(__('project_view_Save_Search_login_error','Login Error!'));?>',
				message: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
				buttons: {
				'confirm': {
					label: '<?php D(__('project_view_Save_Search_you_are_not_logged_in','You are not Logged In. Please login first.'));?>',
					className: 'btn-primary float-end'
					},
				'cancel': {
					label: '<?php D(__('project_view_save_search_button_cancel','Cancel'));?>',
					className: 'btn-dark float-start'
					}
				},
				callback: function (result) {
					if(result){
						var base_url = '<?php echo base_url();?>';
						var refer = window.location.href.replace(base_url, '');
						location.href = '<?php echo base_url('login?refer='); ?>'+refer;
					}
				}
			});
		};

		check_login(invitemember, login_error);
		
	})
	
}


</script>
