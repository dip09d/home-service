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
        <div class="card-header">
          <h3 class="card-title"><?php echo $title ? $title : '';?></h3>

          <div class="card-tools">
			
          </div>
        </div>
       
		<div class="card-body table-responsive p-0" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>					
                  <th>ID</th>
                  <th style="width:20%">Name</th>
                  <th style="width:8%">User</th>
                  <th style="width:20%">Email</th>
                  <th style="width:12%" class="text-center">Verification</th>
                  <th style="width:20%">Registered On</th>
                  <th style="width:5%">Status</th>
                  <th class="text-right" style="padding-right:15px;">Action</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $k => $v){ 
				$email_ver_txt = $phone_ver_txt = $email_ver_class = $phone_ver_class = $logo = '';
				$logo = getMemberLogo($v[$primary_key]);
				if($v['is_email_verified'] > 0){
					$email_ver_txt = 'Verified';
					$email_ver_class = 'green';
				}else{
					$email_ver_txt = 'Not Verified';
					$email_ver_class = '';
				}
				
				if($v['is_phone_verified'] > 0){
					$phone_ver_txt = 'Verified';
					$phone_ver_class = 'green';
				}else{
					$phone_ver_txt = 'Not Verified';
					$phone_ver_class = '';
				}
				
				if($v['is_doc_verified']  == '2'){
					$doc_ver_txt = 'Rejected';
					$doc_ver_class = 'red';
				}elseif($v['is_doc_verified']  == '1'){
					$doc_ver_txt = 'Verified';
					$doc_ver_class = 'green';
				}else{
					$doc_ver_txt = 'Not Verified';
					$doc_ver_class = '';
				}
				
				$user_tag = '';
				if($v['is_employer'] == '1'){
					$user_tag = '<span class="badge badge-dark">Employer</span>';
				}else{
					$user_tag = '<span class="badge badge-info">Freelancer</span>';
				}
				
				$status = '';
				if($v['login_status'] == 1){
					$status = '<a href="'.JS_VOID.'"  data-toggle="tooltip" title="Make inactive" onclick="changeStatus(0, '.$v[$primary_key].',this)"><span class="badge badge-success">Active</span></a>';
				}else if($v['login_status'] == 0){
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Make active"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="badge badge-danger">Inactive</span></a>';
				}else{
					$status = '<a href="'.JS_VOID.'" data-toggle="tooltip" title="Restore"  onclick="changeStatus(1, '.$v[$primary_key].', this)"><span class="badge badge-danger">Deleted</span></a>';
				}
				?>
				<tr>
					
                  <td><?php echo $v[$primary_key]; ?></td>
                  <td><img src="<?php echo $logo;?>" class="rounded-circle mr-2" alt="User Image" height="32" width="32" /> <?php echo $v['member_name']; ?></td>
                  <td><?php echo $user_tag; ?></td>
                  <td><?php echo $v['access_user_email']; ?></td>
                  <td class="text-center">
					<i class="icon-feather-mail <?php echo ICON_SIZE;?> <?php echo $email_ver_class; ?> mr-1" data-toggle="tooltip" title="<?php echo $email_ver_txt;?>"></i>
					<i class="icon-feather-phone <?php echo ICON_SIZE;?> <?php echo $phone_ver_class; ?> mr-1" data-toggle="tooltip" title="<?php echo $phone_ver_txt;?>"></i>
					<?php if($v['is_employer'] != '1'){ ?>
					<i class="icon-feather-file <?php echo ICON_SIZE;?> <?php echo $doc_ver_class; ?>" data-toggle="tooltip" title="<?php echo $doc_ver_txt;?>"></i>
					<?php } ?>
				  </td>
                  <td><?php echo date('d M, Y h:i A', strtotime($v['member_register_date'])); ?></td>
				   <td><?php echo $status; ?></td>
				   
                  <td class="text-right" style="padding-right:15px;">
                  	<div class="dropdown">
                      <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <?php if($v['is_employer'] == '1'){}else{?>
                        <a class="dropdown-item" href="<?php echo SITE_URL.'profileview/view/'.md5($v[$primary_key]); ?>" target="_blank">View Details</a>
                        <?php }?>
                        <a class="dropdown-item" href="<?php echo base_url('member/view_edit/basic_info/'.$v[$primary_key]); ?>">Edit Full Details</a>
                        <a class="dropdown-item" href="<?php echo JS_VOID;?>" onclick="edit('<?php echo $v[$primary_key]; ?>')">Edit Basic Info</a>                        
                        <a class="dropdown-item" href="<?php echo JS_VOID;?>" onclick="badge('<?php echo $v[$primary_key]; ?>')">Badges</a> 
                        <a class="dropdown-item" href="<?php echo base_url('wallet/list_record/?member_id='.$v[$primary_key]); ?>" target="_blank">View Wallet</a>                       
                      </div>
                    </div>
					
					
					<?php /*
					&nbsp;
					<a href="<?php echo base_url('proposal/list_record?member_id='.$v[$primary_key]); ?>" data-toggle="tooltip" title="View Projects"><i class="fa fa-list-alt <?php echo ICON_SIZE;?>"></i></a>
					&nbsp;
					<a href="<?php echo base_url('member/view_edit/basic_info/'.$v[$primary_key]); ?>" data-toggle="tooltip" title="Full Detail"><i class="fa fa-user-secret <?php echo ICON_SIZE;?> red"></i></a>*/?>
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

function badge(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page=user_badge');?>&id='+id;
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
