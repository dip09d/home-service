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

        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				 <th style="width:10%">ID</th>
                  <th style="width:10%">Order Number</th>
                  <th style="width:15%" class="text-center">Order Total</th>
                  <th style="width:10%" class="text-center">Order Qty</th>
                  <th style="width:20%" class="text-center">Date</th>
                  <th style="width:15%">Status</th>
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php $currency = get_setting('site_currency'); if(count($list) > 0){foreach($list as $k => $v){ 
				$status = '-';
				if($v['order_status'] == ORDER_PENDING){
					$status = '<span class="badge badge-warning">Pending</span>';
					$status_txt = 'Pending';
				}else if($v['order_status'] == ORDER_PROCESSING){
					$status = '<span class="badge badge-default">Processing</span>';
					$status_txt = 'Processing';
				}else if($v['order_status'] == ORDER_REVISION){
					$status = '<span class="badge badge-primary">Revision</span>';
					$status_txt = 'Revision';
				}else if($v['order_status'] == ORDER_CANCELLATION){
					$status = '<span class="badge badge-warning">Cancellation</span>';
					$status_txt = 'Cancellation';
				}else if($v['order_status'] == ORDER_CANCELLED){
					$status = '<span class="badge badge-danger">Cancelled</span>';
					$status_txt = 'Cancelled';
				}else if($v['order_status'] == ORDER_DELIVERED){
					$status = '<span class="badge badge-default">Delivered</span>';
					$status_txt = 'Delivered';
				}else if($v['order_status'] == ORDER_COMPLETED){
					$status = '<span class="badge badge-success">Completed</span>';
					$status_txt = 'Completed';
				}else{
					$status = '<span class="badge badge-danger">Not Paid</span>';
					$status_txt = 'Not Paid';
				}

				 
				?>
				<tr>
					
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td># <?php echo $v['order_number']; ?></td>
                  <td class="text-center"><?php echo $currency.' '.$v['order_price']; ?></td>
                  <td class="text-center"><?php echo $v['order_qty']; ?></td>
                  <td class="text-center"><?php echo format_date_time($v['order_date']); ?></td>
                  <td><?php echo $status; ?></td>
                  <td class="text-right" style="padding-right:20px;">
					<div class="btn-group">
					  <button type="button" class="btn btn-default"><?php echo $status_txt; ?></button>
					 
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
					  	<li><a href="<?php echo base_url('orders/order_detail/'.$v['order_id']); ?>"><i class="fa fa-info-circle"></i> Order details</a></li>
						<?php if($v['order_status'] == ORDER_PENDING || $v['order_status'] == ORDER_PROCESSING || $v['order_status'] == ORDER_REVISION || $v['order_status'] == ORDER_CANCELLATION || $v['order_status'] == ORDER_DELIVERED){ ?>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('<?php echo ORDER_CANCELLED; ?>', '<?php echo $v[$primary_key]; ?>')"> <i class="fa fa-ban"></i> Cancel Order</a></li>
						<?php }?>
						
						
						
					  </ul>
					</div>
				  </td>
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

function add(){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$add_command);?>';
	load_ajax_modal(url);
}

function edit(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$edit_command);?>&id='+id;
	load_ajax_modal(url);
}

function deleteRecord(id, permanent){
	permanent = permanent || false;
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		console.log('ok');
		var url = '<?php echo base_url($curr_controller.'delete_record');?>/'+id;
		if(permanent){
			url += '?cmd=remove';
		}
		$.getJSON(url, function(res){
			if(res.cmd && res.cmd == 'reload'){
				location.reload();
			}
		});
	}else{
		return false;
	}
}

function changeStatus(sts, id, ele){
	var url = '<?php echo base_url($curr_controller.'change_status');?>';
	$.ajax({
		url : url,
		data: {ID: id, status: sts},
		type: 'POST',
		dataType: 'json',
		success: function(res){
			if(res.cmd){
				if(res.cmd == 'reload'){
					location.reload();
				}else if(res.cmd == 'replace'){
					if(typeof ele !== 'undefined'){
						$('[data-toggle="tooltip"]').tooltip("dispose");
						$(ele).replaceWith(res.data.html);
						init_plugin();
					}
				}
			}
			
		}
	});
	return false;
}

function changeStatusAll(sts){
	var data = $('#main_table').find('input').serialize();
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		data += '&status=' + sts;
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'change_status');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}else if(res.cmd == 'replace'){
						if(typeof ele !== 'undefined'){
							$('[data-toggle="tooltip"]').tooltip("dispose");
							$(ele).replaceWith(res.data.html);
							init_plugin();
						}
					}
				}
				
			}
		});
	}
	return false;
}

function deleteSelected(){
	var c = confirm('Are you sure to delete selected record ?');
	if(c){
		var data = $('#main_table').find('input').serialize();
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'delete_record');?>';
		$.ajax({
			url : url,
			data: data,
			type: 'POST',
			dataType: 'json',
			success: function(res){
				if(res.cmd){
					if(res.cmd == 'reload'){
						location.reload();
					}
				}
				
			}
		});
	}
	
	return false;
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
