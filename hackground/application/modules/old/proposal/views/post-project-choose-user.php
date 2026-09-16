<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
         <?php echo $main_title ? $main_title : '';?>
        <small><?php echo $second_title ? $second_title : '';?></small>
      </h1>
     <?php echo $breadcrumb ? $breadcrumb : '';?>
    </section>

	 <!-- Content Filter -->
	<?php $this->layout->load_filter(); ?>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
			<?php if(ALLOW_TRASH_VIEW){ ?>
			<?php if(get('show') && get('show') == 'trash'){ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method);?>" type="button" class="btn btn-box-tool"><i class="fa fa-check-circle-o <?php echo ICON_SIZE;?>"></i> Show Main</a>&nbsp;&nbsp;
			<?php }else{ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method.'?show=trash');?>" type="button" class="btn btn-box-tool"><i class="icon-feather-trash <?php echo ICON_SIZE;?>"></i> Show Trash</a>&nbsp;&nbsp;
			<?php } ?>
			<?php } ?>
		   
          </div>
        </div>
       
		<div class="box-body table-responsive" id="main_table">
			<div class="row">
				<div class="col-sm-6">
					<div class="input-group form-group">
						<input class="form-control" placeholder="Enter employer name or email" name="user_email" value="">

						<div class="input-group-append">
						  <button type="submit" class="btn btn-success" id="search_employer"><i class="fa fa-search"></i></button>
						</div>
					</div>
		  
					<div class="list-group" id="results">
					</div>  
				</div>
			</div>
        </div>
		 <!-- /.box-body -->
		
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>

<script>
$('#search_employer').click(function(){
	var email = $('[name="user_email"]').val();
	$.get('<?php echo base_url('proposal/search_employer');?>', {email: email}, function(res){
		$('#results').html(res);
	});
})
</script>
