<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="row">
      <div class="col-sm-6 col-12">
        <h1> <?php echo $main_title ? $main_title : '';?> <small><?php echo $second_title ? $second_title : '';?></small> </h1>
      </div>
      <div class="col-sm-6 col-12"> <?php echo $breadcrumb ? $breadcrumb : '';?> </div>
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
              <th style="width:40%">Project</th>
              <th style="width:15%">Contractor</th>
              <th style="width:15%">Credit</th>
              <th style="width:15%">Debit</th>
              <th style="width:20%">Status</th>
              <!--<th class="text-right" style="padding-right:20px;">Action</th>--> 
            </tr>
            <?php if(count($list) > 0){foreach($list as $k => $v){ 
				$status = '';
				if($v['status'] == '1'){
					$status = '<span class="badge badge-success">Active</span>';
				}else if($v['status'] == '0'){
					$status = '<span class="badge badge-warning">Pending</span>';
				}
				
				?>
            <tr>
              <td><a href="<?php echo base_url('proposal/list_record?project_id='.$v['project_id']); ?>"><?php echo $v['project_title']; ?></a></td>
              <td><a href="<?php echo base_url('member/list_record?member_id='.$v['bidder_info']['member_id']); ?>"><?php echo $v['bidder_info']['member_name']; ?></a></td>
              <td><span class="text-green"><?php echo get_setting('site_currency').$v['credit']; ?></span></td>
              <td><span class="text-red"><?php echo get_setting('site_currency').$v['debit']; ?></span></td>
              <td><?php echo $status; ?></td>
              <!--
                  <td class="text-right" style="padding-right:20px;">
					<?php if($v['status'] != DELETE_STATUS){ ?>
					<a href="<?php echo JS_VOID; ?>" onclick="edit('<?php echo $v[$primary_key]; ?>')" data-toggle="tooltip" title="Edit"><i class="fa fa-edit green <?php echo ICON_SIZE;?>"></i></a>
					&nbsp;
					<a href="<?php echo JS_VOID; ?>" onclick="return deleteRecord('<?php echo $v[$primary_key]; ?>')"data-toggle="tooltip" title="Delete"><i class="icon-feather-trash red <?php echo ICON_SIZE;?>"></i></a>
					<?php }elseif(ALLOW_PERMANENT_DELETE){ ?>
					<a href="<?php echo JS_VOID; ?>" onclick="return deleteRecord('<?php echo $v[$primary_key]; ?>', true)"data-toggle="tooltip" title="Delete Permanently"><i class="icon-feather-trash red <?php echo ICON_SIZE;?>"></i></a>
					<?php } ?>
					
				  </td>--> 
            </tr>
            <?php } }else{  ?>
            <tr>
              <td colspan="10"><?php echo NO_RECORD; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php if($show_total){ ?>
        <table class="table">
          <tr>
            <th>Total Escrowed Amount: <span class="text-green"><?php echo get_setting('site_currency').number_format($credit_total, 2); ?></span></th>
            <th>Total Released Amount: <span class="text-red"><?php echo get_setting('site_currency').number_format($debit_total, 2); ?></span></th>
            <th>Total Remaining Amount: <span class="text-blue"><?php echo get_setting('site_currency').number_format($remaining_total, 2); ?></span></th>
          </tr>
        </table>
        <?php } ?>
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
    <div class="modal-content"> </div>
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
