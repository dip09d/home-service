<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$currency=priceSymbol();
?>
<!-- Dashboard Container -->
<div class="dashboard-container">
	<?php echo $left_panel;?>
	<!-- Dashboard Content -->
	<div class="dashboard-content-container" >
		<div class="dashboard-content-inner">
			<!-- Dashboard Headline -->
			<div class="dashboard-box">
				<div class="headline">
                    <h3><i class="icon-material-outline-assignment"></i> <?php if($bookmark_type=='freelancer'){?><?php echo __('favorite_freelancer','My favorite freelancer');?><?php }else{?><?php echo __('favorite_freelancer_project','My favorite projects');?><?php }?></h3>
				</div>
				
				<div class="content">
                    <ul class="dashboard-box-list if-double-button" id="ajax_table">
<?php
if($list){
	foreach($list as $l=>$favorite){
		$favorite_url=get_link('myProjectDetailsURL').'/'.$favorite->project_url;
		$budget = $favorite->budget;
?>
<li>
<div class="job-listing">
	<div class="job-listing-details">
		<div class="job-listing-description">
			<h3 class="job-listing-title">
				<a href="<?php echo $favorite_url;?>" target="_blank"><?php echo ucfirst($favorite->project_title);?></a>
				<span class="text-muted small"> - <?php D($favorite->experience_level_name);?> (<i class="icon-feather-<?php D($favorite->experience_level_key);?>"></i>)</span>
				
			</h3>
			<p class="mb-2"><?php echo $favorite->project_short_info;?></p>
			
			<div class="job-listing-footer mb-3">
				<ul>
					<?php if($favorite->is_fixed==1){
					?>
					
					<li><b><?php echo __('favorite_freelancer_F_price','Fixed Price:');?></b> <?php if($budget){echo $currency.priceFormat($budget);}?></li>
					<?php }?>
					<li><b><?php echo __('favorite_freelancer_industry','Industry:');?></b> <?php D($favorite->category_name);?></li>
					<li><i class="icon-feather-clock text-site"></i> <?php D(get_time_ago($favorite->project_posted_date));?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
	<!-- Buttons -->
	<div class="buttons-to-end single-right-button always-visible">
		
		<a href="<?php echo $favorite_url;?>" target="_blank" class="btn btn-sm btn-outline-site ico" data-tippy-placement="top" data-tippy="" title="View">
			<i class="icon-feather-eye"></i>
		</a>
		<a href="<?php echo VZ;?>" data-id="<?php echo md5($favorite->project_id);?>" data-action="remove" class="btn btn-outline-danger btn-sm ico mark_fav" data-tippy-placement="top" data-tippy="" title="Remove">
			<i class="icon-feather-trash"></i>
		</a>
	</div>

</li>
<?php
	}
}else{
?>
<li class="justify-content-center"><?php echo __('favorite_freelancer_no_record','No record');?></li>
<?php
}
?>



					</ul>
                   
                </div>
				
			</div>
			<?php echo $links; ?>
		</div>
	</div>
</div>

<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
function check_login(succ, fail){
	$.get('<?php echo base_url('projectview/check_login'); ?>', function(res){
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
var main=function(){
$('.mark_fav').on('click',  function(e){
	e.preventDefault();
	var _self = $(this);
		
		var data = {
			pid: _self.data('id'),
		};
		
		bootbox.confirm({
			title: '<?php D(__('favorite_page_Delete_Favorite','Delete Favorite'));?>',
			message: 'Are you sure to delete this favorite ?',
			size: 'small',
			buttons: {
				confirm: {
					label: "Yes",
					className: 'btn-primary float-end'
				},
				cancel: {
					label: "No",
					className: 'btn-dark float-start'
				}
			},
			
			callback: function(result){
				if(result==true){
					$.post('<?php echo get_link('actionfavorite_job'); ?>', data, function(res){
						if(res.status == 'OK'){
							location.reload();
						}
					},'JSON');
				}
			}

		});

});
}
</script>