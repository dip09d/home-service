<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
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
          <div class="card-tools">
			<?php if(ALLOW_TRASH_VIEW){ ?>
			<?php if(get('show') && get('show') == 'trash'){ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method);?>" type="button" class="btn btn-box-tool"><i class="fa fa-check-circle-o <?php echo ICON_SIZE;?>"></i> Show Main</a>&nbsp;&nbsp;
			<?php }else{ ?>
			<a href="<?php echo base_url($curr_controller.$curr_method.'?show=trash');?>" type="button" class="btn btn-box-tool"><i class="icon-feather-trash <?php echo ICON_SIZE;?>"></i> Show Trash</a>&nbsp;&nbsp;
			<?php } ?>
			<?php } ?>
		   
          </div>
        </div>
       
		<div class="card-body table-responsive p-0" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
				  <th style="width:5%">ID</th>
                  <th style="width:25%">Name</th>
                  <th style="width:10%">Type</th>
                  <th style="width:30%">User</th>
                  <th style="width:5%">Status</th>
                  <th align="right">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
					$logo = getMemberLogo($v[$primary_key]);
				$project_tag = '';
				if($v['is_hourly'] == '1'){
					$project_tag = '<span class="badge badge-hourly">Hourly</span>';
				}else if($v['is_fixed'] == '1'){
					$project_tag = '<span class="badge badge-fixed">Fixed</span>';
				}
				
				$status = $status_txt = '';
				if($v['project_status'] == PROJECT_DRAFT){
					$status = '<span class="badge badge-light">Draft</span>';
					$status_txt = 'Draft';
				}else if($v['project_status'] == PROJECT_OPEN){
					$status = '<span class="badge badge-info">Open</span>';
					$status_txt = 'Open';
				}else if($v['project_status'] == PROJECT_HIRED){
					$status = '<span class="badge badge-success">Hired</span>';
					$status_txt = 'Hired';
				}else if($v['project_status'] == PROJECT_CLOSED){
					$status = '<span class="badge badge-dark">Closed</span>';
					$status_txt = 'Closed';
				}else if($v['project_status'] == PROJECT_DELETED){
					$status = '<span class="badge badge-danger">Deleted</span>';
					$status_txt = 'Deleted';
				}else{
					$status = '<span class="badge badge-danger">Error</span>';
					$status_txt = 'Error';
				}
				
				?>
				<tr>
					
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td><?php echo $v['project_title']; ?></td>
                  <td><?php echo $project_tag; ?></td>
                  <td><a href="<?php echo base_url('member/list_record?member_id='.$v['member_id']); ?>"><img src="<?php echo $logo;?>" class="rounded-circle mr-2" alt="User Image" height="32" width="32" /> <?php echo $v['member_name']; ?></a> </td>
                  <td><?php echo $status; ?></td>
                  <td align="right">
                  <div class="dropdown">
                      <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $status_txt; ?>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="<?php echo SITE_URL;?>p/<?php echo $v['project_url']; ?>" target="_blank">View Details</a>
                        <a class="dropdown-item" href="<?php echo JS_VOID;?>" onclick="editProject('<?php echo $v[$primary_key]; ?>')">Edit Project Details</a> 
                        <a class="dropdown-item" href="<?php echo base_url('project_application/list_record/?project_id='.$v[$primary_key]); ?>" target="_blank">View Applications</a>
                        <a class="dropdown-item" href="<?php echo base_url('offers/list_record?project_id='.$v[$primary_key]); ?>">View Offers</a>      
                        <a class="dropdown-item" href="<?php echo base_url('offers/contracts?project_id='.$v[$primary_key]); ?>">View Contracts</a>      
                        <a class="dropdown-item" href="<?php echo base_url('offers/milestone?project_id='.$v[$primary_key]); ?>">View Milestone</a>      
                        <a class="dropdown-item" href="<?php echo base_url('project_escrow/list_record?project_id='.$v[$primary_key]); ?>">Escrow</a>                                             
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
	  <nav>
<ul class="pagination justify-content-center">
<?php echo $links;?>
</ul>
</nav>
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
	
	location.href = '<?php echo base_url('proposal/view_edit/basic_info'); ?>/'+id;
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
