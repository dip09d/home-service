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
			<?php /*?><img src="<?php echo $detail['worker_logo'];?>" width="150" class="img-rounded"/><?php */?>
					<div class="row">
                    <div class="col-md-4">
					<label class="form-label">Name</label>
					<p><?php echo $detail['worker_name']; ?></p>
					</div>
                    <div class="col-md-4">
					<label class="form-label">Email</label>
					<p><?php echo $detail['worker_email']; ?></p>
					</div>
					<div class="col-md-4">
					<label class="form-label">Phone</label>
					<p><?php echo $detail['worker_phone']; ?></p>
					</div>
					<div class="col-md-4">
					<label class="form-label">Alternate Mobile</label>
					<p><?php echo $detail['worker_alt_phone']; ?></p>
					</div>
					<div class="col-md-4">
					<label class="form-label">WhatsApp Number</label>
					<p><?php echo $detail['worker_whatsapp']; ?></p>
					</div>
                    <div class="col-md-4">
					<label class="form-label">Registered On</label>
					 <!-- <p><?php //echo date('d M,Y h:i A', strtotime($detail['worker_register_date'])); ?> </p> -->
					  <p><?php echo date("d M Y h:i A", strtotime($detail['worker_register_date'] . ' UTC')); ?> </p>
                     </div>
					 <div class="col-md-4">
					 <a class="btn btn-site" href="<?php echo JS_VOID; ?>"  onclick="generateicard()">Generate I-card</a>
					 <?php if($detail['icard']){
						 $token=md5(date('Y-m-d').'-ORGUP');
						 $invoice_url=SITE_URL.'/invoice/icarddetails/'.md5($detail['icard']['icard_id']).'?auth='.$token.'&html=1';
						 
						?>
					 <a class="btn btn-success" target="_blank" href="<?php echo $invoice_url; ?>">View I-card</a>
					 <?php }?>
					 
				</div>
		</div>
	</div>
	
	
	<ul class="nav nav-tabs">
	  <li class="nav-item"><a class="nav-link <?php echo $page == 'worker-basic-info' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/basic_info/'.$worker_id); ?>">Basic Info</a></li>
	   
	  
		<li class="nav-item"><a class="nav-link <?php echo $page == 'worker-location' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/location/'.$worker_id); ?>">Location</a></li>
	   
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'worker-service' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/service/'.$worker_id); ?>">Service</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'worker-kyc' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/kyc/'.$worker_id); ?>">KYC</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'worker-bank' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/bank/'.$worker_id); ?>">Bank</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo ($page == 'worker-invoice' || $page == 'worker-invoice-add' ? 'active' : ''); ?>" href="<?php echo base_url('worker/view_edit/invoice/'.$worker_id); ?>">Invoice</a></li>
	   <?php /*<li class="nav-item"><a class="nav-link <?php echo $page == 'worker-language' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/language/'.$worker_id); ?>">Language</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'worker-employment' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/employment/'.$worker_id); ?>">Employment History</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'worker-education' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/education/'.$worker_id); ?>">Education</a></li>
	   <li class="nav-item"><a class="nav-link <?php echo $page == 'worker-portfolio' ? 'active' : ''; ?>" href="<?php echo base_url('worker/view_edit/portfolio/'.$worker_id); ?>">Portfolio</a></li>
	  
	  
	  <li class="<?php echo $page == 'worker-professional-info' ? 'active' : ''; ?>"><a href="<?php echo base_url('worker/view_edit/professional_info/'.$worker_id); ?>">Professional Info</a></li>
	  <li class="<?php echo $page == 'worker-resume' ? 'active' : ''; ?>"><a href="<?php echo base_url('worker/view_edit/resume/'.$worker_id); ?>">Resume</a></li>
	  <li class="<?php echo $page == 'worker-industry' ? 'active' : ''; ?>"><a href="<?php echo base_url('worker/view_edit/industry/'.$worker_id); ?>">Industry</a></li>*/?>
	 
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
function generateicard(){
	var url = '<?php echo base_url('worker/load_ajax_page?page=generateicard&id='.$worker_id);?>';
	load_ajax_modal(url);
}

function init_event(){
	
}

$(function(){
	init_event();
	
	
});
</script>
