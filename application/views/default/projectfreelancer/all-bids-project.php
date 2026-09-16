<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="dashboard-container"> <?php echo $left_panel;?> 
  <!-- Dashboard Content
	================================================== -->
  <div class="dashboard-content-container" >
    <div class="dashboard-content-inner" > 
      
      <!-- Dashboard Box -->
      <div class="dashboard-box margin-top-0"> 
        
        <!-- Headline -->
        <div class="headline">
          <h3><?php echo __('projectfreelancer_all_bid_my_proposal','My Proposals');?></h3>
        </div>
        <div class="content">
          <ul class="dashboard-box-list" id="ajax_table">
          </ul>
          <div class="text-center" id="loader" style="display: none">
            <?php load_view('inc/spinner',array('size'=>30));?>
          </div>
        </div>
        <div class="text-center padding-bottom-20">
          <button class="btn btn-primary" id="load_more" data-val = "0" style="display:none"><?php echo __('projectfreelancer_all_bid_load_more','Load more..');?></button>
        </div>
      </div>
    </div>
  </div>
  <!-- Dashboard Content / End --> 
  
</div>
<script>
var main=function(){
getprojects(0);
	$("#load_more").click(function(e){
		e.preventDefault();
		var page = $(this).data('val');
		getprojects(page);
	});
	
}
var max_page=<?php echo $max_page;?>;
var getprojects = function(page){
  if(max_page>page+1){
    $('#load_more').show();
  }else{
    $('#load_more').hide();
  }
	$("#loader").show();
	$.ajax({
		url:"<?php D(get_link('myBidsAJAXURL'))?>",
		type:'post',
		data: {page:page}
	}).done(function(response){
		if(response){
			$("#ajax_table").append(response);
			$("#loader").hide();
			$('#load_more').data('val', ($('#load_more').data('val')+1));
			loadtooltip();
			//scroll();
		}else{
			$("#loader").hide();
			$('#load_more').hide();
		}

	});
};
var scroll  = function(){
/*$('html, body').animate({
scrollTop: $('#load_more').offset().top
}, 1000);*/
};
</script>