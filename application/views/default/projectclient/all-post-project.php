<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="dashboard-container">
<?php echo $left_panel;?>
<!-- Dashboard Content
	================================================== -->
	<div class="dashboard-content-container">
		<div class="dashboard-content-inner">
			
			<!-- Dashboard Headline
			<div class="dashboard-headline">
				<h3>Job postings</h3>				
			</div> -->
	
			<!-- Dashboard Box -->
        	<div class="dashboard-box margin-top-0">

                <!-- Headline -->
                <div class="headline">
                    <h3><?php echo __('projectclient_proposa_posted_project','My Posted Projects');?></h3>
                </div>
                <div class="content">
                    <ul class="dashboard-box-list" id="ajax_table"></ul>
                    <div class="text-center" id="loader" style="display: none"><?php load_view('inc/spinner',array('size'=>30));?></div>
                </div>
                <div class="text-center">
                    <button class="btn btn-primary mb-3" id="load_more" data-page = "0"><?php echo __('projectclient_proposa_load_more','Load more..');?></button>
                </div>

            </div>

			<!-- Footer -->
			<div class="dashboard-footer-spacer"></div>
			
			<!-- Footer / End -->

		</div>
	</div>
	<!-- Dashboard Content / End -->

</div>

<script>
var main=function(){

	$("#load_more").click(function(e){
		e.preventDefault();
		var page = $(this).data('page');
		getprojects(page);
	});
getprojects(1);	
}
var getprojects = function(from){
	$("#loader").show();
	$.ajax({
		url:"<?php D(get_link('myProjectClientAJAXURL'))?>",
		type:'post',
		dataType:'json',
		data: {page:from}
	}).done(function(response){
		var newpage= parseInt(from)+1;
		if(response){
			$("#ajax_table").append(response.list);
			$("#loader").hide();
			$('#load_more').data('page', newpage);
			if(response.total_page>=newpage){
				$('#load_more').show();
			}else{
				$('#load_more').hide();
			}
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