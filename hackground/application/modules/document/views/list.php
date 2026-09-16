<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

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
        <div class="box-header">
          <h3 class="box-title"><?php echo $title ? $title : '';?></h3>

          <div class="box-tools pull-right">
			<?php if(ALLOW_TRASH_VIEW){ ?>
			<?php if(get('show') && get('show') == 'trash'){ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method);?>" type="button" class="btn btn-box-tool"><i class="fa fa-check-circle-o <?php echo ICON_SIZE;?>"></i> Show Main</a>&nbsp;&nbsp;
			<?php }else{ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method.'?show=trash');?>" type="button" class="btn btn-box-tool"><i class="icon-feather-trash <?php echo ICON_SIZE;?>"></i> Show Trash</a>&nbsp;&nbsp;
			<?php } ?>
			<?php } ?>
		   
		   <?php if(!get('show')){ ?>
			<div class="btn-group" id="global_action_btn" style="display:none">
			  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete selected" onclick="deleteSelected()"><i class="icon-feather-trash"></i></button>
			  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Make active" onclick="changeStatusAll(1)"><i class="icon-feather-thumbs-up"></i></button>
			   <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Make inactive" onclick="changeStatusAll(0)"><i class="fa  fa-thumbs-o-down"></i></button>
			</div>
			&nbsp;
			<?php } ?>
          
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
                  <td>ID</th>
                  <th style="width:20%">Member Name</th>
                  <th style="width:35%">Document</th>
                  <th style="width:20%">Request On</th>
                  <th style="width:10%">Status</th>
                  <th class="text-right" style="padding-right:15px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){
					$logo = getMemberLogo($v[$primary_key]); 
				$status =$status_txt= '';
				if($v['document_status'] == 1){
					$status = '<span class="badge badge-success">Active</span>';
					$status_txt = 'Active';
				}else if($v['document_status'] == 0){
					$status = '<span class="badge badge-warning">Pending</span>';
					$status_txt = 'Pending';
				}else if($v['document_status'] == 2){
					$status = '<span class="badge badge-danger">Rejected</span>';
					$status_txt = 'Rejected';
				}else{
					$status = '<span class="badge badge-danger">Error</span>';
					$status_txt = 'Error';
				}
				?>
				<tr>
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td><a target="_blank" href="<?php echo base_url('member/list_record?member_id='.$v['member_id']); ?>"><img src="<?php echo $logo;?>" class="rounded-circle mr-2" alt="User Image" height="32" width="32" /> <?php echo $v['member_name']; ?></a></td>
                  <td><?php 
                  $document_data=json_decode($v['document_data']);
                  if($document_data){
				  	foreach($document_data as $kd=>$vald){
						?>
					<p class="mb-1"><b><?php echo $vald->title;?>:</b> <a href="<?php echo UPLOAD_HTTP_PATH.'verification-documents/'.$vald->file;?>" target="_blank" title="<?php echo $vald->type;?>"><i class="icon-feather-file green <?php echo ICON_SIZE;?>"></i> (<?php echo $vald->type;?>)</a></p>	
						<?php
					}
				  }
                  
                  ?></td>
                  
                  <td><?php echo date('d M, Y h:i A', strtotime($v['document_date'])); ?></td>
                  <td><?php echo $status; ?></td>
                  <td class="text-right" style="padding-right:15px;">
                  	<div class="dropdown">
					  <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					  <?php echo $status_txt; ?>
                      </button>
					  
					  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					  	<a class="dropdown-item" href="<?php echo SITE_URL;?>profileview/view/<?php echo md5($v['member_id']);?>" target="_blank">View Profile</a>
					  <?php if($v['document_status'] == 1){?>
					  	<a class="dropdown-item" href="<?php echo JS_VOID;?>" onclick="addReason('2', '<?php echo $v[$primary_key]; ?>')">Reject</a>
					  <?php }elseif($v['document_status'] == 2){?>					  
					  <?php }else{?>
					  	<a class="dropdown-item" href="<?php echo JS_VOID;?>" onclick="changeStatus('1', '<?php echo $v[$primary_key]; ?>')">Approve</a>
					  	<a class="dropdown-item" href="<?php echo JS_VOID;?>" onclick="addReason('2', '<?php echo $v[$primary_key]; ?>')">Reject</a>
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
function addReason(sts, id){
	$.confirm({
		title: 'Reason !',
		content: '<p>Enter your reason</p> <textarea id="action_reason" class="form-control"></textarea>',
		buttons: {
			confirm: function () {
				var reason = this.$content.find('#action_reason').val();
				if(reason.trim() == ''){
					this.$content.find('#action_reason').addClass('invalid');
					return false;
				}
				
				var url = '<?php echo base_url($curr_controller.'change_status');?>';
				$.ajax({
					url : url,
					data: {ID: id, status: sts, note: reason},
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
				return false;
				
			},
			cancel: function () {
				this.$content.find('#action_reason').val('');
			}
		}
	});
}
function changeStatus(sts, id, ele){
	var status = [1, 0];

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
