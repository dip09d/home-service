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
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				 <th style="width:10%">ID</th>
                  <th style="width:10%">Number</th>
                  <th style="width:10%" class="text-center">Total</th>
                  <th style="width:25%">Info</th>
                  <th style="width:10%" class="text-center">Date</th>
                  <th style="width:15%">Status</th>
                  <th align="right">Action</th>
                </tr>
				<?php $currency = get_setting('site_currency'); 
				if(count($list) > 0){foreach($list as $k => $v){ 
					$token=md5(date('Y-m-d').'-ORGUP');
					if($v['invoice_type_id'] == 3){
  						$invoice_url=SITE_URL.'/invoice/invoice_details/'.md5($v['invoice_id']).'?auth='.$token;
					}else{
  						$invoice_url=SITE_URL.'/invoice/details/'.md5($v['invoice_id']).'?auth='.$token;
					}
					
					
				$status = '-';
				if($v['invoice_status'] == -1){
					$status = '<span class="badge badge-danger">Deleted</span>';
					$status_txt = 'Deleted';
				}elseif($v['invoice_status'] == 1){
					$status = '<span class="badge badge-success">Paid</span>
					<a href="'.VZ.'" data-toggle="tooltip" title="'.$v['change_reason'].'"> <i class="icon-feather-info"></i> </a>';
					if($v['attachment']){
                        $attachment=UPLOAD_HTTP_PATH.'invoice/attachment/'.$v['attachment'];
                        $status .= '<a data-toggle="tooltip" title="Attachment" href="'.$attachment.'" target="_blank"><i class="icon-feather-file text-success fa-lg"></i></a>';  
                    }
					$status_txt = 'Paid';
				}elseif($v['invoice_status'] == 2){
					$status = '<span class="badge badge-danger">Rejected</span>
					<a href="'.VZ.'" data-toggle="tooltip" title="'.$v['change_reason'].'"> <i class="icon-feather-info"></i> </a>';
					$status_txt = 'Rejected';
				}
				else{
					$status = '<span class="badge badge-warning">Pending</span>';
					$status_txt = 'Pending';
				}
				$sender=unserialize($v['issuer_information']);
				//print_r($sender);
				$receiver=unserialize($v['recipient_information']);
				//print_r($receiver);
				$info=array();
				$info[]='<p class="mb-0"><b>Sender:</b> <a href="'.base_url('member/list_record').'?member_id='.$v['issuer_member_id'].'" target="_blank">'.$sender['I_name'].'</a></p>';
				$info[]='<p><b>Receiver:</b> <a href="'.base_url('member/list_record').'?member_id='.$v['recipient_member_id'].'" target="_blank">'.$receiver['R_name'].'</a></p>';
				?>
				<tr>
					
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td># <?php echo $v['invoice_number']; ?></td>
                  <td class="text-center"><?php echo $currency.''.round($v['round_up_amount'],2); ?></td>
                  <td><?php echo implode('',$info); ?></td>
                  <td class="text-center"><?php echo format_date_time($v['invoice_date']); ?></td>
                  <td><?php echo $status; ?></td>
                  <td align="right">
					<div class="dropdown">
					  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $status_txt; ?></button>					 					  <div class="dropdown-menu" role="menu">
					  	<a class="dropdown-item" target="_blank" href="<?php echo $invoice_url; ?>&is_download=1">Download</a>												
					  	<!-- <a class="dropdown-item" target="_blank" href="<?php echo $invoice_url; ?>">Invoice details</a>	 -->	
						<?php if($v['invoice_status'] == 0){?>
						  <a class="dropdown-item"  href="<?php echo JS_VOID; ?>" onclick="return deleteRecord('<?php echo $v['invoice_id']; ?>')">Delete</a>										
						<a class="dropdown-item"  href="<?php echo JS_VOID; ?>" onclick="markaspaid('<?php echo $v['invoice_id']; ?>')">Mark As Paid</a>	
						<?php }?>									
					  </div>
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
function markaspaid(id){
	var url = '<?php echo base_url('invoice/load_ajax_page?page=markaspaid');?>&id='+id;
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
