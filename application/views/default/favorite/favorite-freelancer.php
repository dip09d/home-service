<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$favorite_url=get_link('FavoriteURL');
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
                    <ul class="dashboard-box-list" id="ajax_table">
<?php
if($list){
	foreach($list as $l=>$favorite){
		$favorite_url=get_link('viewprofileURL').'/'.md5($favorite->member_id);
		$logo=getMemberLogo($favorite->member_id);
		$badges=getData(array(
			'select'=>'b.icon_image,b_n.name,b_n.description',
			'table'=>'member_badges as m',
			'join'=>array(array('table'=>'badges as b','on'=>'m.badge_id=b.badge_id','position'=>'left'),array('table'=>'badges_names as b_n','on'=>"(b.badge_id=b_n.badge_id and b_n.lang='".get_active_lang()."')",'position'=>'left')),
			'where'=>array('m.member_id'=>$favorite->member_id,'b.status'=>1),
			'order'=>array(array('b.display_order','asc')),
		));
?>
<li>
<div class="job-listing width-adjustment">
	<div class="job-listing-company-logo">
	<img src="<?php echo $logo;?>" alt="">
	</div>
	<div class="job-listing-details">
		<div class="job-listing-description">
			<h4 class="job-listing-title">
				<a href="<?php echo $favorite_url;?>" target="_blank"><?php echo $favorite->member_name;?></a>
				<?php if($badges){
				foreach($badges as $b=>$badge){
					$badge_icon=UPLOAD_HTTP_PATH.'badge-icons/'.$badge->icon_image;
				?>
					<img src="<?php echo $badge_icon;?>" alt="<?php echo $badge->name;?>" height="26" data-tippy-placement="top" title="<?php echo $badge->name;?>"  />
				<?php	
				}
				}
			  ?>
			</h4>
			<p class="mb-0"><?php echo $favorite->member_heading;?></p>
			<div class="freelancer-rating">
				<div class="star-rating" data-rating="<?php echo round($favorite->avg_rating,1);?>"></div>
			</div>
			<div class="job-listing-footer">
				<ul>
					<li><b><?php echo __('favorite_freelancer_H_rate','Hourly Rate:');?></b> <?php echo $favorite->member_hourly_rate > 0 ? $currency.  priceFormat($favorite->member_hourly_rate) . ' / hr' : ' - ';?></li>
					<li><b><?php echo __('favorite_freelancer_earning','Earnings:');?></b><?php D($currency.displayamount($favorite->total_earning,2));?></li>
					<li><i class="icon-feather-map-pin"></i> <?php echo $favorite->country_name;?></li>
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
		<a href="<?php echo VZ;?>" data-id="<?php echo md5($favorite->member_id);?>" data-action="remove" class="btn btn-outline-danger btn-sm ico mark_fav" data-tippy-placement="top" data-tippy="" title="Remove">
			<i class="icon-feather-trash-2"></i>
		</a>
	</div>

</li>
<?php
	}
}else{
?>
<li><p><?php echo __('favorite_freelancer_no_record','No record');?></p></li>
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
			mid: _self.data('id'),
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
					$.post('<?php echo get_link('actionfavorite_freelancer'); ?>', data, function(res){
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