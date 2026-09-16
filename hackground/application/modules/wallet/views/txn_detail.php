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

          <div class="box-tools pull-right hide">
			<a href="<?php echo base_url($curr_controller.'csv')?>?type=transaction_details&wallet_id=<?php echo $wallet_id;?>" target="_blank" class="btn btn-success">Download CSV</a>
          </div>
        </div>
       
		<div class="card-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				  <th style="width:10%">ID</th>
                  <th style="width:30%">Detail</th>
                  <th style="width:20%">Transaction Date</th>
                  <th style="width:10%">Status</th>
                  <th style="width:10%" class="text-center">Debit (<?php echo get_setting('site_currency');?>)</th>
                  <th style="width:10%" class="text-center">Credit (<?php echo get_setting('site_currency');?>)</th>
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
				$status = '';
				if($v['status'] == '1'){
					$status = '<span class="badge badge-success">Active</span>';
				}else if($v['status'] == '0'){
					$status = '<span class="badge badge-warning">Pending</span>';
				}else if($v['status'] == '2'){
					$status = '<span class="badge badge-danger">Deleted</span>';
				}
				?>
				<tr>
					
                  <td><?php echo $v['wallet_transaction_id']; ?></td>
                  <td><?php echo $v['type_description_tkey']; ?></td>
                  <td><?php echo format_date_time($v['transaction_date']); ?></td>
                  <td><?php echo $status; ?></td>
                  <td class="text-center text-red"><?php echo format_money($v['debit']); ?></td>
                  <td class="text-center text-green"><?php echo format_money($v['credit']); ?></td>
                 
                  <td class="text-right" style="padding-right:20px;">
					<a href="<?php echo JS_VOID; ?>" data-toggle="tooltip" title="View Detail" data-placement="left" onclick="view_txn_detail('<?php echo $v['wallet_transaction_id']; ?>')"><i class="icon-feather-info <?php echo ICON_SIZE;?>"></i></a>
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
	  <?php if($links){?>
		<nav>
			<ul class="pagination justify-content-center">
			<?php echo $links;?>
			</ul>
		</nav>
		 <?php }?>
	  
	  <table class="table v-middle">
		<tr>
			<td>Total Debit ($) <span class="hightlight"><?php echo format_money($debit_total);?></span></td>
			<td>Total Credit ($) <span class="hightlight"><?php echo format_money($credit_total);?></span></td>
			<td>Origional Balance ($) <span class="hightlight"><?php $org_val = (string) ($credit_total - $debit_total); echo format_money($org_val); ?></span></td>
			<td>Wallet Balance  ($) <span class="hightlight"><?php $wallet_val = get_wallet_balance($wallet_id); echo format_money($wallet_val); ?></span></td>
			<td>
			<?php if($org_val != $wallet_val){ ?>
            <button class="btn btn-default" onclick="updateWalletBalance('<?php echo $wallet_id?>'); ">Update</button>
            <?php  } ?>
			</td>
		</tr>
	  </table>
	  
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
<style>

.v-middle tr td{
	vertical-align: middle !important;
}

.v-middle tr td span.hightlight{
	    font-weight: bold;
  
}
</style>  
  
<div class="modal fade" id="ajaxModal">
	  <div class="modal-dialog">
		<div class="modal-content">
		 
		</div>
	  </div>
</div>

<script>

function updateWalletBalance(wallet_id){
	if(wallet_id){
		$.ajax({
			url : '<?php echo base_url('wallet/update_wallet');?>',
			data: {wallet_id: wallet_id, cmd: 'update_origional'},
			type: 'POST',
			dataType: 'JSON',
			success: function(res){
				if(res.status == 1){
					location.reload();
				}
			}
		});
	}
}

function view_txn_detail(txn_id){
	Modal.openURL({
		title : 'Transaction Detail of '+txn_id,
		url: '<?php echo base_url($curr_controller.'load_ajax_page?page=single_txn_detail');?>&id='+txn_id+'&wallet_id=<?php echo $wallet_id;?>'
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
	var status = [1, 0];
	if(status.indexOf(sts) !== -1){
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
	}
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
