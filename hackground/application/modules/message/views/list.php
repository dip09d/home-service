  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
  		<div class="row">
            <div class="col-sm-6 col-12">
                <h1>
                    <?php echo $main_title ? $main_title : ''; ?>
                    <small><?php echo $second_title ? $second_title : ''; ?></small>
                </h1>
            </div>
            <div class="col-sm-6 col-12">
                <?php echo $breadcrumb ? $breadcrumb : ''; ?>
            </div>
        </div>

      
    </section>

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
				 <th style="width:5%">ID</th>
                  <th style="width:20%">Booking ID</th>
                  <th style="width:30%">Customer</th>
                  <th style="width:30%">Worker</th>
                  <!-- <th style="width:20%">Last Updated</th> -->
                  <th align="right">Action</th>

                </tr>
				<?php  if(count($list) > 0){foreach($list as $k => $v){ 
				
				?>
				<tr>
				  <td><?php echo $v[$primary_key]; ?></td>
                  <td><a href="<?php echo base_url('booking/list_record'); ?>?booking_id=<?php echo $v['booking_id'];?>" target="_blank"><?php echo $v['booking_id']; ?></a></td>
				  <td>
  					<a href="<?php echo base_url('member/list_record') . '?member_id=' . $v['chat_member_id']; ?>" target="_blank">
						<img src="<?php echo getMemberLogo($v['chat_member_id']); ?>" 
							class="rounded-circle mr-2" 
							alt="User Image" 
							height="32" 
							width="32" />
						<?php echo $v['member_name']; ?>
					</a>
				  </td>
				  <td>
  					<a href="<?php echo base_url('worker/list_record') . '?worker_id=' . $v['chat_worker_id']; ?>" target="_blank">
						<img src="<?php echo getWorkerLogo($v['chat_worker_id']); ?>" 
							class="rounded-circle mr-2" 
							alt="User Image" 
							height="32" 
							width="32" />
						<?php echo $v['worker_name']; ?>
					</a>
				  </td>
                  
                  <td align="right">
				  <a href="<?php echo base_url('message/show_conversion/'.$v['conversations_id']); ?>" data-toggle="tooltip" title="View"><i class="icon-feather-eye green fa-lg"></i></a>
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
