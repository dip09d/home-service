<section class="section">
<div class="container">
<!--<div class="row">
    <div class="col-xl-3 col-lg-4 col-12">
        <div class="sidebar-container">
        </div>
    </div>
    <div class="col-xl-9 col-lg-8 col-12">
    </div>
</div>-->
    <div class="dashboard-headline">
    	<h1><?php echo __('job_findjobs_projects','Projects');?></h1>
    </div>
	<h3 class="page-title"><ec class="total_count">0</ec> <?php echo __('job_findjobs_jobs_found','jobs found');?> </h3>
    <form id="filterForm">
    <div class="row row-10">
    	<div class="col-md-6">
            <div class="search-box input-group">
                <input type="text" value="<?php if($searchdata && array_key_exists('term',$searchdata)){echo $searchdata['term'];}?>" class="form-control" placeholder="<?php echo __('job_findjobs_by_title','Find jobs by title');?>" form="filterForm" name="term"/>
                <button type="button" class="btn btn-primary" onclick="filterForm()"><i class="icon-feather-search"></i><?php echo __('job_findjobs_search','Search');?> </button>
            </div>
        </div>
        <div class="col-md-6">
        	<div class="d-flex">
                <a href="javascript:void(0)" class="btn btn-outline-site me-3" onclick="$('#filterAdvance').toggle();"><i class="icon-feather-filter"></i><?php echo __('job_findjobs_filter','Filter');?> </a>                
                <a href="javascript:void(0)" class="btn btn-outline-site me-3" hidden><i class="icon-feather-heart"></i><?php echo __('job_findjobs_save_search','Save Search');?> </a>                                 
                <div class="sort-by ms-auto">
                <div class="sort-by">
                    <span><?php echo __('job_findjobs_sort_by','Sort by:');?></span>
                    <select class="selectpicker hide-tick" name="order_by"  onchange="filterForm()">
                        <option value="default" <?php if($searchdata && array_key_exists('order_by',$searchdata) && $searchdata['order_by']=='default'){echo 'selected';}?>><?php echo __('job_findjobs_relevance','Relevance');?></option>
                        <option value="latest" <?php if($searchdata && array_key_exists('order_by',$searchdata) && $searchdata['order_by']=='latest'){echo 'selected';}?>><?php echo __('job_findjobs_newest','Newest');?></option>
                        <option value="old" <?php if($searchdata && array_key_exists('order_by',$searchdata) && $searchdata['order_by']=='old'){echo 'selected';}?>><?php echo __('job_findjobs_oldest','Oldest');?></option>
                        <!--<option value="random">Random</option>-->
                    </select>
                </div>
                <!--    
                <div class="sort-group">
                    <span>View by:</span>
                    <a href="#" class="btn btn-outline-site"><i class="icon-feather-grid"></i></a> &nbsp;
                    <a href="#" class="btn btn-outline-site active"><i class="icon-feather-list"></i></a>
                </div>-->
			</div>       
            </div>    
        </div>
    </div>    
    

    <div id="filterAdvance" style="display:none" class="mt-3">
    
    	<div class="row">
        <!-- Category -->
        <div class="col-md-4">
        <div class="sidebar-widget">
            <h5><?php echo __('job_findjobs_category','Category');?></h5>
            <select name="category" class="selectpicker default"  title="All Categories"  data-live-search="true">
                <option value=""><?php echo __('job_findjobs_all','All');?></option>
                <?php print_select_option($category, 'category_id', 'category_name',(($searchdata && array_key_exists('category',$searchdata)) ? $searchdata['category']:'' )); ?>
            </select>
        </div>
        </div>

        <!-- Category -->
        <div class="col-md-4">
        <div class="sidebar-widget" id="sub_category_wrapper">
            <h5><?php echo __('job_findjobs_speciality','Speciality');?></h5>
            <select name="sub_category" id="sub_category" class="selectpicker default" title="Speciality"  data-live-search="true" onchange="filterForm()">
            	<option value=""><?php echo __('job_findjobs_all','All');?></option>
				<?php 
				if($searchdata && array_key_exists('category',$searchdata)){
				?>
				<?php print_select_option($sub_category, 'category_subchild_id', 'category_subchild_name',(($searchdata && array_key_exists('sub_category',$searchdata)) ? $searchdata['sub_category']:'' )); ?>
        		<?php }?>
			</select>
        </div>
        </div>
        <div class="col-md-4">
        <div class="sidebar-widget">
            <h5><?php echo __('job_findjobs_payment_type','Payment Type');?></h5>
            <select name="is_hourly" class="selectpicker default"  title="Payment Type" onchange="filterForm()">
                <option value=""><?php echo __('job_findjobs_all','All');?></option>
                <option value="0" <?php if($searchdata && array_key_exists('is_hourly',$searchdata) && $searchdata['is_hourly']=='0'){echo 'selected';}?>><?php echo __('job_findjobs_fixed','Fixed');?></option>
                <option value="1" <?php if($searchdata && array_key_exists('is_hourly',$searchdata) && $searchdata['is_hourly']=='1'){echo 'selected';}?>><?php echo __('job_findjobs_hourly','Hourly');?></option>
            </select>
        </div>
        </div>

        
        </div>
        
        <div class="row">
		<div class="col-md-4">
        <div class="sidebar-widget">
            <h5><?php echo __('job_findjobs_experience_level','Experience Level');?></h5>
            <select name="experience_level" class="selectpicker default" title="Experience Level"  data-live-search="true" onchange="filterForm()">
                <option value=""><?php echo __('job_findjobs_all','All');?></option>
                <?php print_select_option($experience_level, 'experience_level_id', 'experience_level_name',(($searchdata && array_key_exists('experience_level',$searchdata)) ? $searchdata['experience_level']:'' )); ?>
            </select>
        </div>
        </div>
        <div class="col-md-4">
        <div class="sidebar-widget">
            <h5><?php echo __('job_findjobs_job_type','Job Type');?></h5>
            <select name="job_type" class="selectpicker default"  title="Job Type" onchange="filterForm()">
                <option value=""><?php echo __('job_findjobs_all','All');?></option>
                <option value="OneTime" <?php if($searchdata && array_key_exists('job_type',$searchdata) && $searchdata['job_type']=='OneTime'){echo 'selected';}?>><?php echo __('job_findjobs_one_time_project','One Time Poject');?></option>
                <option value="Ongoing" <?php if($searchdata && array_key_exists('job_type',$searchdata) && $searchdata['job_type']=='Ongoing'){echo 'selected';}?>><?php echo __('job_findjobs_ongoing_project','Ongoing Project');?></option>
                <option value="NotSure" <?php if($searchdata && array_key_exists('job_type',$searchdata) && $searchdata['job_type']=='NotSure'){echo 'selected';}?>><?php echo __('job_findjobs_not_sure','Not Sure');?></option>
            </select>
        </div>
        </div>
        <!-- Salary -->
        <div class="col-md-4">
        <div class="sidebar-widget">
            <h5><?php echo __('job_findjobs_budget','Budget');?></h5>
            <div class="">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Min" name="min" value="<?php if($searchdata && array_key_exists('min',$searchdata)){echo $searchdata['min'];}?>">
					<input type="text" class="form-control" placeholder="Max" name="max" value="<?php if($searchdata && array_key_exists('max',$searchdata)){echo $searchdata['max'];}?>">
					<button type="button" class="btn btn-primary" onclick="filterForm()"><i class="icon-feather-search"></i><?php echo __('job_findjobs_search','Search');?> </button> 
				</div>
            </div>
        </div>
        </div>
        <!--<div class="col-md-4">
        <div class="sidebar-widget">
        <h5>Sort by</h5>
        <select class="selectpicker hide-tick" name="order_by" form="filterForm" onchange="filterForm()">
            <option value="default">Relevance</option>
            <option value="latest">Newest</option>
            <option value="old">Oldest</option>
        </select>
		</div>
        </div>-->
        </div>
	</div>
	</form>
    

	<!-- Tasks Container -->
    <div class="tasks-list-container mt-4" id="job_list">
    	<div class="text-center" style="margin: 100px"><?php load_view('inc/spinner',array('size'=>30));?></div>
        <!-- Pagination -->
        <div class="pagination-container margin-top-30 margin-bottom-60" hidden>
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
	<!-- Tasks Container / End -->
			
	<div class="text-center"><button class="btn btn-primary" id="load_more_btn"><?php echo __('job_findjobs_load_more','Load More');?></button></div>

		
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
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var main = function(){	
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
		
		$('#job_list').html('');
		var form = $('#filterForm').serialize();
		
		findJobLoadMore.config({
			url : '<?php echo base_url('job/job_list_ajax');?>?'+form,
			target : '#job_list',
			load_more : '#load_more_btn',
			autoload: {
				autoload: false,
				target: 'window'
			},
			onResult: function(res){
				history.pushState({}, null,  '<?php echo URL::get_link('search_job'); ?>?'+form);
			$('.total_count').html(res.job_list_count);
			}
		});
	
		findJobLoadMore.start();
		
	}
	
	window.filterForm = filterForm;
	
	filterForm();
	
	// get sub category
	$('[name="category"]').change(function(){
		
		var val = $('[name="category"] :selected').val();
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
		});
	});
	$('#job_list').on('click', '.action_favorite',function(e){
		e.preventDefault();
		var _self=$(this);
		var data = {
			pid: _self.data('pid'),
		};
		$.post('<?php echo get_link('actionfavorite_job'); ?>', data, function(res){
			if(res['status'] == 'OK'){
				if(res['cmd']== 'add'){
					_self.addClass('active');
					bootbox.alert({
						title:'<?php echo __('job_findjobs_make_favorite','Make Favorite');?>',
						message: '<?php echo __('job_findjobs_successfully_save','Successfully Saved');?>',
						buttons: {
						'ok': {
							label: '<?php echo __('job_findjobs_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
				}else{
					_self.removeClass('active');
					bootbox.alert({
						title:'<?php echo __('job_findjobs_remove_favorite','Remove Favorite');?>',
						message: '<?php echo __('job_findjobs_successfully_remove','Successfully Removed');?>',
						buttons: {
						'ok': {
							label: '<?php echo __('job_findjobs_ok','Ok');?>',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							
					    }
					});
					
				}
			}else if(res['popup'] == 'login'){
				bootbox.confirm({
					title:'<?php echo __('job_findjobs_login_error','Login Error!');?>',
					message: '<?php echo __('job_findjobs_login_error_msg','You are not Logged In. Please login first.');?>',
					buttons: {
					'confirm': {
						label: '<?php echo __('job_findjobs_login_btn','Login');?>',
						className: 'btn-primary float-end'
						},
					'cancel': {
						label: '<?php echo __('job_findjobs_cancel_btn','Cancel');?>',
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
		
	});
$('#job_list').on('click','.action_report',  function(e){
	e.preventDefault();
	var _self = $(this);
	var report_project = function(){
		
		var data = {
			project_id: _self.data('pid'),
			cmd: 'add',
		};
		
		if(data.cmd == 'add'){
			
			$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
			$('#myModal').modal('show');
			
			$.get("<?php echo get_link('reportJobFormAjaxURL'); ?>",data, function( data ) {
				setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );},1000)
			});
			
		}else{
			
		}
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
	
	
	check_login(report_project, login_error);
	
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


</script>