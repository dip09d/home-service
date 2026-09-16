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
			<div class="dashboard-box margin-top-0">
				<div class="headline">
                    <h3><i class="icon-material-outline-rate-review" style="vertical-align: -3px;"></i> <?php echo __('review_reviews','Reviews');?> </h3>
				</div>
				
				<div class="content">
                    <ul class="dashboard-box-list if-button" id="ajax_table">
<?php
if($list){
	foreach($list as $l=>$review){
		$project_url=get_link('myProjectDetailsURL').'/'.$review->project_url;
?>
<li>
	<div class="job-listing">
		<!-- Content -->
		<div class="job-listing-details">						
			<div class="job-listing-description">				
                <h4 class="job-listing-title mb-1"><a href="<?php echo $project_url;?>" target="_blank"><?php echo $review->project_title;?></a></h4>
                <div class="star-rating" data-rating="<?php echo $review->average_review;?>"></div>
                <p class="mb-2"><sup class="icon-line-awesome-quote-left"></sup> <?php echo nl2br($review->review_comments);?> <sub class="icon-line-awesome-quote-right"></sub></p>
				<div class="job-listing-footer if-button">
                    <ul>
                        <li><i class="icon-material-outline-account-circle"></i> <?php echo $review->member_name;?></li>
                        <li><i class="icon-material-outline-date-range"></i> <?php echo dateFormat($review->review_date,'M d, Y');?></li>
                    </ul>
                </div>
            </div>
		</div>
	</div>
    <div class="buttons-to-end single-right-button always-visible">	
				<a href="<?php echo VZ;?>" class="btn btn-sm btn-outline-site ico viewreview" data-id="<?php echo md5($review->review_id);?>" data-tippy-placement="top" data-tippy="" title="View">
					<i class="icon-feather-eye"></i>
				</a>
			</div>
	
</li>
<?php
	}
}else{
?>
<li><p></i><?php echo __('review_no_record','No record');?></p></li>
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
<div id="myModal" class="modal fade" tabindex="-1" role="dialog"  style="z-index: 10000"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
    <!-- Modal content-->
    <div class="modal-content mycustom-modal">
      <div class="text-center padding-top-50 padding-bottom-50">
        <?php load_view('inc/spinner',array('size'=>30));?>
      </div>
    </div>
  </div>
</div>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';

var main=function(){
$('.viewreview').on('click',  function(e){
	e.preventDefault();
	$( "#myModal .mycustom-modal").html( '<div class="text-center padding-top-50 padding-bottom-50">'+SPINNER+'<div>' );
	$('#myModal').modal('show');
	var _self = $(this);
	var data = {
		rid: _self.data('id'),
	};
	$.get( "<?php echo get_link('Reviewdetails')?>",data, function( data ) {
		setTimeout(function(){ $( "#myModal .mycustom-modal").html( data );starRating('.star-rating');},1000)
	});	

});
}
</script>