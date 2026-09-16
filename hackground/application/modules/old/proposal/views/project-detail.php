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
	 <section class="content">
	<div class="well box-header">
		<div class="box-title">Owner</div>
		<div style="margin-top: 20px;">
	
				<div class="row">
					<div class="col-md-4">
                    	<label class="form-label">Name</label>
						<p><?php echo $detail['owner']['member_name']; ?></p>
                    </div>	
                    <div class="col-md-4">			
						<label class="form-label">Email</label>
						<p><?php echo $detail['owner']['member_email']; ?></p>	
                    </div>
                   <!-- <div class="col-md-4">			
						<label class="form-label">Registered On</label>
						<p><?php echo date('d M,Y h:i A', strtotime($detail['owner']['member_register_date'])); ?></p>
                    </div>-->
                    <div class="col-md-4">
                    <div class="form-group">
                      <label for="name" class="form-label">Posted On: </label>
                      <p><?php echo date('d M,Y h:i A', strtotime($detail['project_posted_date'])); ?></p>
                    </div>
                  </div>
                </div>
               <!-- <div class="row">
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="name" class="form-label">Expire On: </label>
                      <p><?php echo $detail['project_expired_date']; ?></p>
                    </div>
                  </div>
                </div>-->
			
		</div>
	</div>
		
    <!-- Main content -->
   
	
	<ul class="nav nav-tabs">
	  <li class="nav-item"><a class="nav-link <?php echo $page == 'project-title' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/title/'.$project_id); ?>">Title</a></li>
	  <li class="nav-item"><a class="nav-link <?php echo $page == 'project-basic-info' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/basic_info/'.$project_id); ?>">Description</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'project-files' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/files/'.$project_id); ?>">Attachments</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'project-basic-detail' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/basic_detail/'.$project_id); ?>">Detail</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'project-skills' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/skills/'.$project_id); ?>">Expertise</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'project-visiblity' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/visiblity/'.$project_id); ?>">Visiblity</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'project-budget' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/budget/'.$project_id); ?>">Budget</a></li>
	   
	  <li class="nav-item hide"><a class="nav-link <?php echo $page == 'project-category' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/category/'.$project_id); ?>">Category</a></li>
	 
	   <li class="nav-item hide"><a class="nav-link <?php echo $page == 'project-settings' ? 'active' : ''; ?>" href="<?php echo base_url('proposal/view_edit/settings/'.$project_id); ?>">Settings</a></li>
	</ul>
      <!-- Default box -->
      <div class="box">
        <?php /*?><div class="box-header with-border">
          <h3 class="box-title"><?php // echo $title ? $title : '';?></h3>
			
          <div class="box-tools pull-right">
			<?php if(ALLOW_TRASH_VIEW){ ?>
			<?php if(get('show') && get('show') == 'trash'){ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method);?>" type="button" class="btn btn-box-tool"><i class="fa fa-check-circle-o <?php echo ICON_SIZE;?>"></i> Show Main</a>&nbsp;&nbsp;
			<?php }else{ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method.'?show=trash');?>" type="button" class="btn btn-box-tool"><i class="icon-feather-trash <?php echo ICON_SIZE;?>"></i> Show Trash</a>&nbsp;&nbsp;
			<?php } ?>
			<?php } ?>
		   
          </div>
        </div><?php */?>
       
		<div class="box-body" id="main_table">
			<?php $this->load->view($page); ?>
        </div>
		
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

function editProject(id){
	if(!id){
		return false;
	}
	
	location.href = '<?php echo base_url('proposal/view_edit'); ?>/'+id;
}


function init_event(){
	
}

$(function(){
	init_event();
	
	
});
</script>
