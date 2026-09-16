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
			<?php /*?><img src="<?php echo $detail['agency_logo'];?>" width="150" class="img-rounded"/><?php */?>
					<div class="row">
                    <div class="col-md-4">
					<label class="form-label">Name</label>
					<p><?php echo $detail['agency_name']; ?></p>
					</div>
                    <div class="col-md-4">
					<label class="form-label">Email</label>
					<p><?php echo $detail['agency_email']; ?></p>
					</div>
					<div class="col-md-4">
					<label class="form-label">Phone</label>
					<p><?php echo $detail['agency_phone']; ?></p>
					</div>
					
					<div class="col-md-4">
					<label class="form-label">WhatsApp Number</label>
					<p><?php echo $detail['agency_whatsapp']; ?></p>
					</div>
                    <div class="col-md-4">
					<label class="form-label">Registered On</label>
					 <p><?php echo date('d M,Y h:i A', strtotime($detail['agency_register_date'])); ?> </p>
                     </div>
				</div>
		</div>
	</div>
	
	
	<ul class="nav nav-tabs">
	  <li class="nav-item"><a class="nav-link <?php echo $page == 'agency-basic-info' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/basic_info/'.$agency_id); ?>">Basic Info</a></li>
	   
	  
		<li class="nav-item"><a class="nav-link <?php echo $page == 'agency-location' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/location/'.$agency_id); ?>">Location</a></li>
	   
	   <?php /*<li class="nav-item"><a class="nav-link <?php echo $page == 'agency-service' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/service/'.$agency_id); ?>">Service</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'agency-kyc' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/kyc/'.$agency_id); ?>">KYC</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'agency-bank' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/bank/'.$agency_id); ?>">Bank</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'agency-language' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/language/'.$agency_id); ?>">Language</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'agency-employment' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/employment/'.$agency_id); ?>">Employment History</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'agency-education' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/education/'.$agency_id); ?>">Education</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'agency-portfolio' ? 'active' : ''; ?>" href="<?php echo base_url('agency/view_edit/portfolio/'.$agency_id); ?>">Portfolio</a></li>
	  
	  
	  <li class="<?php echo $page == 'agency-professional-info' ? 'active' : ''; ?>"><a href="<?php echo base_url('agency/view_edit/professional_info/'.$agency_id); ?>">Professional Info</a></li>
	  <li class="<?php echo $page == 'agency-resume' ? 'active' : ''; ?>"><a href="<?php echo base_url('agency/view_edit/resume/'.$agency_id); ?>">Resume</a></li>
	  <li class="<?php echo $page == 'agency-industry' ? 'active' : ''; ?>"><a href="<?php echo base_url('agency/view_edit/industry/'.$agency_id); ?>">Industry</a></li>*/?>
	 
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
