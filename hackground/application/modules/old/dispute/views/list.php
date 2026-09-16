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

          <div class="box-tools pull-right">
			
		   <?php if(!get('show')){ ?>
			<div class="btn-group" id="global_action_btn" style="display:none">
			  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete selected" onclick="deleteSelected()"><i class="icon-feather-trash"></i></button>
			</div>
			&nbsp;
			<?php } ?>
           
          </div>
        </div>
       
		<div class="box-body table-responsive no-padding" id="main_table">
              <table class="table table-hover">
                <tbody>
				<tr>
					<!-- <th style="width:3%">
						
						  <input type="checkbox" class="check_all_main magic-checkbox" data-target=".check_all" id="all_item">
							<label for="all_item"></label>
					</th> -->
					<th style="width:5%">ID</th>
                  <th style="width:20%">Project</th>
                  <th style="width:25%">Contract/Milestone</th>
                  <th style="width:10%">Amount</th>
                  <th style="width:10%" class="text-center">Date</th>
                  <th style="width:10%" class="text-center">Status</th>
                  <th class="text-right" style="padding-right:20px;">Action</th>
                </tr>
				<?php 
				$currency = get_setting('site_currency');
				if(count($list) > 0){foreach($list as $k => $v){ 
				$status = '';
				if($v['dispute_status'] == 1){
					$status = '<span class="badge badge-success">Closed</span>';
				}else{
					$status = '<span class="badge badge-warning">Pending</span>';
				}
				?>
				<tr>
					<!-- <td>
						
						<input type="checkbox" class="check_all magic-checkbox" name="ID[]" value="<?php echo $v['project_contract_dispute_id']; ?>" id="item_<?php echo $v['project_contract_dispute_id'];?>">
						<label for="item_<?php echo $v['project_contract_dispute_id'];?>"></label>
						
					</td> -->
				  <td><?php echo $v['project_contract_dispute_id']; ?></td>
                  <td>
				  <?php echo $v['project_title']; ?> <a href="<?php echo base_url('proposal/list_record')?>?project_id=<?php echo $v['project_id']?>" target="_blank"><i class="icon-feather-external-link"></i></a>
				  </td>
                  <td>
				  <?php echo $v['contract_title']; ?> <a href="<?php echo base_url('offers/contracts')?>?contract_id=<?php echo $v['contract_id']?>" target="_blank"><i class="icon-feather-external-link"></i></a>
				  <br><?php echo $v['milestone_title']; ?>
				  </td>
				  <td class="text-center"><?php echo $currency.$v['disputed_amount']; ?></td>
				  <td class="text-center"><?php echo format_date_time($v['dispute_date']); ?></td>
                
				 <td class="text-center"><?php echo $status; ?></td>
                  <td class="text-right" style="padding-right:20px;">
					<a href="<?php echo base_url('dispute/details/'.$v['contract_milestone_id']); ?>" data-toggle="tooltip" title="Details"><i class="icon-feather-eye green <?php echo ICON_SIZE;?>"></i></a>
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
function view(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page=view');?>&id='+id;
	load_ajax_modal(url);
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
