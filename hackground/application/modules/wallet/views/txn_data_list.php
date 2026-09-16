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
         
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				  <th style="width:10%">ID</th>
                  <th style="width:30%">Payment Type</th>
                  <th style="width:20%">Reference</th>
                  <th style="width:20%" class="text-center">Request Data</th>
                  <th style="width:10%" class="text-center">Response Data</th>
                
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
				
				?>
				<tr>
					
                  <td><?php echo $v['online_id']; ?></td>
                  <td><?php echo $v['payment_type']; ?></td>
                  <td><?php echo $v['content_key']; ?></td>
                  <td class="text-center"><a href="<?php echo JS_VOID;?>" onclick="view_request_data('<?php echo $v['online_id'];?>')">View</a></td>
                  <td class="text-center"><a href="<?php echo JS_VOID;?>" onclick="view_response_data('<?php echo $v['online_id'];?>')">View</a></td>
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

      </div>
      <!-- /.box -->
	  <?php if($links){?>
		<nav>
			<ul class="pagination justify-content-center">
			<?php echo $links;?>
			</ul>
		</nav>
		 <?php }?>
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

function view_txn_detail(txn_id){
	Modal.openURL({
		title : 'Transaction Detail of '+txn_id,
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=single_txn_detail');?>&id='+txn_id
	});
}

function view_request_data(id){
	Modal.openURL({
		title : 'Requested Data',
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=online_txn_data&type=request_data');?>&id='+id,
	});
}

function view_response_data(id){
	Modal.openURL({
		title : 'Response Data',
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=online_txn_data&type=response_data');?>&id='+id,
	});
}


function init_event(){
	
	var item  = $('.check_all_main').data('target');
	
	$(item).on('change', function(){
		checkSelected();
	});
	
	$('.check_all_main').on('change', function(){
		var is_checked = $(this).is(':checked');
		var target = $(this).data('target');
		if(is_checked){
			$(target).prop('checked', true);
		}else{
			$(target).prop('checked', false);
		}
		$(target).triggerHandler('change');
	});
	
	function checkSelected(){
		var target  = $('.check_all_main').data('target');
		var l = $(target + ':checked').length;
		if(l == 0){
			$('#global_action_btn').find('button').attr('disabled', 'disabled');
			$('#global_action_btn').hide();
		}else{
			$('#global_action_btn').find('button').removeAttr('disabled');
			$('#global_action_btn').show();
		}
	} 
}

$(function(){
	
	init_plugin(); /* global.js */
	init_event();
	
	
});
</script>
<script src="<?php echo ADMIN_COMPONENT;?>moment/moment.js"></script>
<script src="<?php echo ADMIN_COMPONENT;?>bootstrap-daterangepicker/daterangepicker.js"></script>
  <link rel="stylesheet" href="<?php echo ADMIN_COMPONENT;?>bootstrap-daterangepicker/daterangepicker.css">
  <script>
  	$('.datepicker').daterangepicker({
  		"startDate": "<?php echo date('Y/01/01');?>",
    	"endDate": "<?php echo date('Y/m/d');?>",
  		locale: {
            format: 'YYYY-MM-DD'
        }
  		
  	});
  </script>