<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">			
<?php $cntloop=0;
	if($memberInfo){
	$start=3;
	
	foreach($memberInfo as $portfolio){
		$start++;
	if($start%4==0){
		$cntloop++;
	}
	$image=NO_IMAGE;
	if($portfolio->portfolio_image){
		$portfolio_image=json_decode($portfolio->portfolio_image);
		if(file_exists(UPLOAD_PATH."member-portfolio/".$portfolio_image->file)){
			$image=UPLOAD_HTTP_PATH."member-portfolio/".$portfolio_image->file;
		}
	}
		
	?>
<div class="col-md-4">
<div class="portfolio-contain portfolio-contain-sec-<?php D($cntloop);?>" <?php if($cntloop>1){?> style="display:none"<?php }?>>

	<!-- Photo Box -->
	<div href="javascript:void(0)" class="photo-box-portfolio" style="background-image:url(<?php echo $image;?>);background-position: center center;">
	
		<h3><?php D(ucfirst($portfolio->portfolio_title));?></h3>
		<!--<p><?php // D(ucfirst($portfolio->portfolio_description));?></p>-->
        <div class="portfolio-action-btn">       	
        	<a href="javascript:void(0)" class="edit_account_btn btn btn-primary btn-circle" data-popup="portfolio_view" data-popup-id="<?php D($portfolio->portfolio_id)?>" data-tippy-placement="top" title="Preview"><i class="icon-feather-eye"></i></a>
            <?php if($is_editable){?>                
                    <a href="javascript:void(0)" class="edit_account_btn btn btn-primary btn-circle" data-popup="portfolio" data-popup-id="<?php D($portfolio->portfolio_id)?>" data-tippy-placement="top" title="Edit"><i class="icon-feather-edit-2"></i></a> 
                    <a href="javascript:void(0)" class=" ripple-effect delete_account_btn btn btn-danger btn-circle" data-popup="portfolio" data-popup-id="<?php D($portfolio->portfolio_id)?>" data-tippy-placement="top" title="Delete"><i class="icon-feather-trash"></i></a> 
                                 
            <?php }?>
            </div>
		<div class="photo-box-portfolio-content">         	
			<h4 class="mb-0"><?php D(ucfirst($portfolio->category_subchild_name));?></h4>
			<?php if($portfolio->portfolio_complete_date){?>
            <span><?php D(dateFormat($portfolio->portfolio_complete_date,'M d, Y'));?></span>
			<?php }else{?>
            <div style="height:24px;margin-top: 5px;">&nbsp;</div>
			<?php }?>
		</div>
	</div>
</div>
</div>
<?php }
	}else{
		?>
		<!--<p class="text-center">No record</p>-->
		<?php
	}
?>
	
</div>
<?php
if($cntloop>1){
	?>
<nav class="pagination" id="paggination-portfolio">
	<ul>
	<li class="pagination-arrow"><a href="<?php D(VZ);?>" class="ripple-effect btn disabled prevbtn"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
	<?php
	for($i=1;$i<=$cntloop;$i++){
		?>
		<li><a href="<?php D(VZ);?>" data-id="<?php D($i);?>" class="ripple-effect pagibtn <?php if($i==1){?>current-page<?php }?>"><?php D($i);?></a></li>
		<?php
	}
	?>
	<li class="pagination-arrow"><a href="<?php D(VZ);?>" class="ripple-effect btn nextbtn"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
	</ul>
</nav>
	<?php
}
?>
<script type="text/javascript">
	$('.prevbtn').on('click',function(){
		var prev=$('#paggination-portfolio').find('a.current-page').attr('data-id');
		var next=parseInt(prev)-1;
		showhideportfolio(prev,next);
		$('#paggination-portfolio').find('a.current-page').removeClass('current-page');
		$('#paggination-portfolio').find('a[data-id="'+next+'"]').addClass('current-page');
		if(next==1){
			$('#paggination-portfolio').find('a.prevbtn').addClass('disabled');
		}else{
			$('#paggination-portfolio').find('a.prevbtn').removeClass('disabled');
		}
		if(next==$('#paggination-portfolio a.pagibtn').length){
			$('#paggination-portfolio').find('a.nextbtn').addClass('disabled');
		}else{
			$('#paggination-portfolio').find('a.nextbtn').removeClass('disabled');
		}
	})
	$('.nextbtn').on('click',function(){
		var prev=$('#paggination-portfolio').find('a.current-page').attr('data-id');
		var next=parseInt(prev)+1;
		showhideportfolio(prev,next);
		$('#paggination-portfolio').find('a.current-page').removeClass('current-page');
		$('#paggination-portfolio').find('a[data-id="'+next+'"]').addClass('current-page');
		if(next==1){
			$('#paggination-portfolio').find('a.prevbtn').addClass('disabled');
		}else{
			$('#paggination-portfolio').find('a.prevbtn').removeClass('disabled');
		}
		if(next==$('#paggination-portfolio a.pagibtn').length){
			$('#paggination-portfolio').find('a.nextbtn').addClass('disabled');
		}else{
			$('#paggination-portfolio').find('a.nextbtn').removeClass('disabled');
		}
	})
	$('.pagibtn').on('click',function(){
		var prev=$('#paggination-portfolio').find('a.current-page').attr('data-id');
		$('#paggination-portfolio').find('a.current-page').removeClass('current-page');
		$(this).addClass('current-page');
		if($(this).attr('data-id')==1){
			$('#paggination-portfolio').find('a.prevbtn').addClass('disabled');
		}else{
			$('#paggination-portfolio').find('a.prevbtn').removeClass('disabled');
		}
		if($(this).attr('data-id')==$('#paggination-portfolio a.pagibtn').length){
			$('#paggination-portfolio').find('a.nextbtn').addClass('disabled');
		}else{
			$('#paggination-portfolio').find('a.nextbtn').removeClass('disabled');
		}
		showhideportfolio(prev,$(this).attr('data-id'));
	})
	function showhideportfolio(prev,id){
		$('.portfolio-contain-sec-'+prev).slideUp('slow');
		$('.portfolio-contain-sec-'+id).slideDown('slow');
	}
	starRating('.star-rating');
	loadtooltip();
</script>