<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ul class="boxed-list-ul">
<?php
$cntloop=0;
	if($memberInfo){
	$start=3;
	
	foreach($memberInfo as $review){
		$start++;
	if($start%4==0){
		$cntloop++;
	}
?>
					<li class="review-contain review-contain-sec-<?php D($cntloop);?>" <?php if($cntloop>1){?> style="display:none"<?php }?>>
						<div class="boxed-list-item">
							<!-- Content -->
							<div class="item-content">
								<h4><?php echo $review->project_title;?></h4>
								<div class="item-details margin-top-10">
									<div class="star-rating" data-rating="<?php echo $review->average_review;?>"></div>
									<div class="detail-item"><i class="icon-material-outline-date-range"></i> <?php echo dateFormat($review->review_date,'M d, Y');?></div>
								</div>
								<div class="item-description">
									<p><?php echo nl2br($review->review_comments);?> </p>
								</div>
							</div>
						</div>
					</li>
<?php }
	}else{
		?>
		<li>No record found</li>
		<?php
	}
?>
</ul>
          
          
          <?php
            if($cntloop>1){
                ?>
          <!-- Pagination -->
          <div class="pagination-container">			
            <nav class="pagination" id="paggination-review">
                <ul>
                <li class="pagination-arrow"><a href="<?php D(VZ);?>" class="disabled prevbtn"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
                <?php
                for($i=1;$i<=$cntloop;$i++){
                    ?>
                    <li><a href="<?php D(VZ);?>" data-id="<?php D($i);?>" class="pagibtn <?php if($i==1){?>current-page<?php }?>"><?php D($i);?></a></li>
                    <?php
                }
                ?>
                <li class="pagination-arrow"><a href="<?php D(VZ);?>" class="nextbtn"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
                </ul>
            </nav>
                
            <div class="clearfix"></div>
          </div>
          <?php
            }
            ?>
<script type="text/javascript">
	$('.prevbtn').on('click',function(){
		var prev=$('#paggination-review').find('a.current-page').attr('data-id');
		var next=parseInt(prev)-1;
		showhidereview(prev,next);
		$('#paggination-review').find('a.current-page').removeClass('current-page');
		$('#paggination-review').find('a[data-id="'+next+'"]').addClass('current-page');
		if(next==1){
			$('#paggination-review').find('a.prevbtn').addClass('disabled');
		}else{
			$('#paggination-review').find('a.prevbtn').removeClass('disabled');
		}
		if(next==$('#paggination-review a.pagibtn').length){
			$('#paggination-review').find('a.nextbtn').addClass('disabled');
		}else{
			$('#paggination-review').find('a.nextbtn').removeClass('disabled');
		}
	})
	$('.nextbtn').on('click',function(){
		var prev=$('#paggination-review').find('a.current-page').attr('data-id');
		var next=parseInt(prev)+1;
		showhidereview(prev,next);
		$('#paggination-review').find('a.current-page').removeClass('current-page');
		$('#paggination-review').find('a[data-id="'+next+'"]').addClass('current-page');
		if(next==1){
			$('#paggination-review').find('a.prevbtn').addClass('disabled');
		}else{
			$('#paggination-review').find('a.prevbtn').removeClass('disabled');
		}
		if(next==$('#paggination-review a.pagibtn').length){
			$('#paggination-review').find('a.nextbtn').addClass('disabled');
		}else{
			$('#paggination-review').find('a.nextbtn').removeClass('disabled');
		}
	})
	$('.pagibtn').on('click',function(){
		var prev=$('#paggination-review').find('a.current-page').attr('data-id');
		$('#paggination-review').find('a.current-page').removeClass('current-page');
		$(this).addClass('current-page');
		if($(this).attr('data-id')==1){
			$('#paggination-review').find('a.prevbtn').addClass('disabled');
		}else{
			$('#paggination-review').find('a.prevbtn').removeClass('disabled');
		}
		if($(this).attr('data-id')==$('#paggination-review a.pagibtn').length){
			$('#paggination-review').find('a.nextbtn').addClass('disabled');
		}else{
			$('#paggination-review').find('a.nextbtn').removeClass('disabled');
		}
		showhidereview(prev,$(this).attr('data-id'));
	})
	function showhidereview(prev,id){
		$('.review-contain-sec-'+prev).slideUp('slow');
		$('.review-contain-sec-'+id).slideDown('slow');
	}
	starRating('.star-rating');
	loadtooltip();
</script>