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

          <div class="box-tools pull-right">
			<a href="<?php echo base_url($curr_controller.'csv')?>?type=withdrawn" target="_blank" class="btn btn-success">Download CSV</a>
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				  <th style="width:5%">Trn ID</th>
                  <th style="width:15%">Name</th>
                  <th style="width:10%">Curr. Balance</th>
                  <th style="width:7%">Amount</th>
                  <th style="width:20%">Details</th>
                  <th style="width:15%">Created Date</th>
                  <th style="width:10%">Status</th>
                 
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
					//print_r($v);
				$status = '';
				if($v['status'] == '1'){
					$status = '<span class="badge badge-success">Approved</span>';
					$status_txt = 'Approved';
				}else if($v['status'] == '0'){
					$status = '<span class="badge badge-warning">Pending</span>';
					$status_txt = 'Pending';
				}else if($v['status'] == '2'){
					$status = '<span class="badge badge-danger">Rejected</span>';
					$status_txt = 'Rejected';
				}
				
				?>
				<tr>
					
                  <td><?php echo $v['wallet_transaction_id']; ?></td>
                  <td><?php echo $v['member_name']; ?></td>
                  <td><?php echo $v['balance']; ?></td>
                  <td><?php echo $v['amount']; ?></td>
                  <td>
                  <b>Method:</b> <?php echo str_replace('_',' ',$v['description_tkey']); ?>
                  <br>
                  <?php
                  $relational_data=json_decode($v['relational_data']);
                  if(is_object($relational_data)){
                  	unset($relational_data->method);
                  	if($v['description_tkey']=='Bank_Transfer'){
						?>
						<p><b>Account Number</b>: <?php echo $relational_data->to; ?><br>
						<b>Bank Name</b>: <?php echo $relational_data->name; ?><br>
						<b>Swift Code</b>: <?php echo $relational_data->swift; ?><br>
						<b>IBAN</b>: <?php echo $relational_data->iban; ?></p>
						<?php
					}else{	
				  	foreach($relational_data as $r=>$rval){
					?>
					<p class="mb-0"><b><?php echo ucwords(str_replace('_',' ',$r))?></b>: <?php echo $rval; ?></p>
					<?php	
					}
					}
				  }
                  ?>
                  </td>
                  <td><?php echo format_date_time($v['created_date']); ?></td>
                  <td><?php echo $status; ?></td>
                 
                   <td class="text-right" style="padding-right:20px;">
					<div class="btn-group">
					  <button type="button" class="btn btn-default"><?php echo $status_txt; ?></button>
					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu" role="menu">
						<?php if($v['status'] == 0){ ?>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('1', '<?php echo $v[$primary_key]; ?>')">Approve</a></li>
						<li><a href="<?php echo JS_VOID;?>" onclick="changeStatus('2', '<?php echo $v[$primary_key]; ?>')">Reject</a></li>
						<?php }else{  ?>
						<li>No Action </li>
						<?php } ?>
						
						
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

function view_txn_detail(txn_id){
	Modal.openURL({
		title : 'Transaction Detail of '+txn_id,
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=single_txn_detail');?>&id='+txn_id
	});
}


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
	if(sts==1){
		var c = confirm('Are you sure to approve the transaction ?');
	}else if(sts==2){
		var c = confirm('Are you sure to reject the transaction ?');
	}
	if(c){
		var url = '<?php echo base_url($curr_controller.'change_status_withdrawn');?>';
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
	}
	return false;
}

function changeStatusAll(sts){
	var data = $('#main_table').find('input').serialize();
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
		data += '&status=' + sts;
		data += '&action_type=multiple';
		var url = '<?php echo base_url($curr_controller.'change_status_withdrawn');?>';
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
