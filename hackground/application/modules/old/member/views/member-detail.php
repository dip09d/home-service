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
	<div class="well box-header">
		<div style="margin-top: 20px;">
			<?php /*?><img src="<?php echo $detail['member_logo'];?>" width="150" class="img-rounded"/><?php */?>
					<div class="row">
                    <div class="col-md-4">
					<label class="form-label">Name</label>
					<p><?php echo $detail['member_name']; ?></p>
					</div>
                    <div class="col-md-4">
					<label class="form-label">Email</label>
					<p><?php echo $detail['member_email']; ?></p>
					</div>
                    <div class="col-md-4">
					<label class="form-label">Registered On</label>
					 <p><?php echo date('d M,Y h:i A', strtotime($detail['member_register_date'])); ?> </p>
                     </div>
				</div>
		</div>
	</div>
	
	
	<ul class="nav nav-tabs">
	  <li class="nav-item"><a class="nav-link <?php echo $page == 'member-basic-info' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/basic_info/'.$member_id); ?>">Basic Info</a></li>
	   
	   <?php if($detail['is_employer'] == '1'){ /*Skipping tabs for employer */ ?>
		<li class="nav-item"><a class="nav-link <?php echo $page == 'organization-location' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/organization_location/'.$member_id); ?>">Location</a></li>
		<li class="nav-item"><a class="nav-link <?php echo $page == 'organization-details' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/organization_details/'.$member_id); ?>">Company</a></li>
	   <?php }else{ ?>
		<li class="nav-item"><a class="nav-link <?php echo $page == 'member-location' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/location/'.$member_id); ?>">Location</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'member-profile-detail' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/profile_detail/'.$member_id); ?>">Detail</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'member-skills' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/skills/'.$member_id); ?>">Skills</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'member-language' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/language/'.$member_id); ?>">Language</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'member-employment' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/employment/'.$member_id); ?>">Employment History</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'member-education' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/education/'.$member_id); ?>">Education</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'member-portfolio' ? 'active' : ''; ?>" href="<?php echo base_url('member/view_edit/portfolio/'.$member_id); ?>">Portfolio</a></li>
	   <?php } ?>
	   <?php /*
	  <li class="<?php echo $page == 'member-professional-info' ? 'active' : ''; ?>"><a href="<?php echo base_url('member/view_edit/professional_info/'.$member_id); ?>">Professional Info</a></li>
	  <li class="<?php echo $page == 'member-resume' ? 'active' : ''; ?>"><a href="<?php echo base_url('member/view_edit/resume/'.$member_id); ?>">Resume</a></li>
	  <li class="<?php echo $page == 'member-industry' ? 'active' : ''; ?>"><a href="<?php echo base_url('member/view_edit/industry/'.$member_id); ?>">Industry</a></li>*/?>
	 
	</ul>
      <!-- Default box -->
      <div class="card">
        <?php /*?><div class="box-header with-border">
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
        </div><?php */?>
       
		<div class="card-body table-responsive" id="main_table">
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
