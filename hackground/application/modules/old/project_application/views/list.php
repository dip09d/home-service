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
       
		<div class="card-body table-responsive p-0" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
                  <th style="width:45%">Project</th>
                  <th style="width:25%">Bidder</th>
                  <th style="width:15%">Bid Amount</th>
                  <th style="width:20%">Date</th>
                  <th>Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
					$logo = getMemberLogo($v[$primary_key]);
				$status = '';
				/* if($v['status'] == ACTIVE_STATUS){
					$status = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$v[$primary_key].',this)"><span class="badge badge-success">Active</span></a>';
				}else if($v['status'] == INACTIVE_STATUS){
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="badge badge-danger">Inactive</span></a>';
				}else{
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Restore"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="badge badge-danger">Deleted</span></a>';
				} */
				
				?>
				<tr>
                  <td><a href="<?php echo base_url('proposal/list_record?project_id='.$v['project_id']); ?>"><?php echo $v['project_title']; ?></a></td>
                  <td><a href="<?php echo base_url('member/list_record?member_id='.$v['member_id']); ?>"><img src="<?php echo $logo;?>" class="rounded-circle mr-2" alt="User Image" height="32" width="32" /> <?php echo $v['member_name']; ?></a></td>
                  <td><?php echo get_setting('site_currency').$v['bid_amount']; ?></td>
                  <td><?php echo date('d M, Y', strtotime($v['bid_date'])); ?></td>
                 
				 
                  <td class="text-right" style="padding-right:20px;">
					<div class="dropdown">
					  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Action
					  </button>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="<?php echo base_url('project_application/application_detail/'.$v['project_id'].'/'.$v['bid_id']); ?>">View Detail</a>      
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
