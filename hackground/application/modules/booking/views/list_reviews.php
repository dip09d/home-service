<style>
.comment-ellipsis {
    display: inline-block;
    max-width: 80%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
}


.rating-wrapper {
    position: relative;
    display: inline-block;
    font-size: 18px;
    color: #ddd;
}

.rating-wrapper::before {
    content: "★★★★★";
}

.rating-fill {
    position: absolute;
    top: 0;
    left: 0;
    white-space: nowrap;
    overflow: hidden;
    color: #ffc107;
}

.rating-fill::before {
    content: "★★★★★";
}
</style>
 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
	<div class="row">
      <div class="col-sm-6 col-12">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
		 <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
	  </div>
      <div class="col-sm-6 col-12"><?php echo $breadcrumb ? $breadcrumb : '';?></div>
	</div>
    </section>
	
	
	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header border-bottom-0">
          <h3 class="card-title"><?php echo $title ? $title : '';?></h3>

          <div class="card-tools">
			
		   
		   
            
          </div>
        </div>
       
		<div class="card-body table-responsive p-0" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				  
                  <th style="width:5%">ID</th>
                  <th style="width:20%">Review By</th>
                  <th style="width:20%">Review To</th>
                  <th style="width:35%">Comments</th>
                  <th style="width:30%">Quality</th>
                  <th style="width:30%">Deadlines</th>
                  <th style="width:30%">Communication</th>
                  <!-- <th style="width:30%">Average Review</th> -->
                  <!-- <th style="width:30%">Date</th> -->
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
				
				
				?>
				<tr>
					
                  <td><?php echo $v['review_id']; ?></td>
                  <td><?php echo $v['member_name']; ?></td>
                  <td><?php echo $v['worker_name']; ?></td>
                  <!-- <td><?php //echo $v['review_comments']; ?></td> -->
				    <td style="max-width:200px;">
						<?php
						$comment = trim($v['review_comments']);

						if ($comment == '') {
							echo 'N/A';
						} else {
							?>
							<span 
								class="comment-ellipsis" 
								data-bs-toggle="tooltip" 
								title="<?php echo htmlspecialchars($comment); ?>">
								<?php echo htmlspecialchars($comment); ?>
							</span>
						<?php } ?>
					</td>
					<td>
						<?php if ($v['for_quality'] > 0) { ?>
							<div class="rating-wrapper">
								<div class="rating-fill" 
									style="width: <?php echo ($v['for_quality'] / 5) * 100; ?>%">
								</div>
							</div>
						<?php } else { ?>
							N/A
						<?php } ?>
				   </td>
				   <td>
						<?php if ($v['for_deadlines'] > 0) { ?>
							<div class="rating-wrapper">
								<div class="rating-fill" 
									style="width: <?php echo ($v['for_deadlines'] / 5) * 100; ?>%">
								</div>
							</div>
						<?php } else { ?>
							N/A
						<?php } ?>
				   </td>
				   <td>
						<?php if ($v['for_communication'] > 0) { ?>
							<div class="rating-wrapper">
								<div class="rating-fill" 
									style="width: <?php echo ($v['for_communication'] / 5) * 100; ?>%">
								</div>
							</div>
						<?php } else { ?>
							N/A
						<?php } ?>
				   </td>
                  <?php /* ?><td>
				  	<?php if ($v['average_review'] > 0) { ?>
						<div class="rating-wrapper">
							<div class="rating-fill" 
								style="width: <?php echo ($v['average_review'] / 5) * 100; ?>%">
							</div>
						</div>
					<?php } else { ?>
						N/A
					<?php } ?>
				  </td>
                  <td><?php //echo date('d M Y', strtotime($v['review_date'])); ?></td><?php */ ?>
                
                  
                </tr>
				<?php } }else{  ?>
				<tr>
                  <td colspan="10"><?php echo NO_RECORD; ?></td>
                 </tr>
				<?php } ?>
                
               </tbody>
			  </table>
        </div>
		 <!-- /.box-body -->
		 <?php if($links){?>
			<div class="card-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
               <?php echo $links;?>
              </ul>
            </div>
		 <?php }?>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>
<link rel="stylesheet" href="<?php echo ADMIN_PLUGINS;?>tagsinput/tagsinput.css">
<script src="<?php echo ADMIN_PLUGINS;?>tagsinput/tagsinput.js" type="text/javascript"></script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<script>
$(document).ready(function() {
    $('.star-rating').each(function() {
        var rating = parseFloat($(this).data('rating')) || 0;
        var percentage = (rating / 5) * 100;
        $(this).css('--rating-percent', percentage + '%');
        $(this).find('::after');
        $(this)[0].style.setProperty('--width', percentage + '%');
        $(this).css('position','relative');
        $(this).find('::after');
        $(this).append('<style>.star-rating::after{width:'+percentage+'%;}</style>');
    });
});
</script>
