<style>
.check-inline{

}

.check-inline label{
	display: inline-block;
} 
</style>
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

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      
<div class="card">
        <div class="card-header border-bottom-0">
          <h3 class="card-title"><?php echo $title ? $title : '';?></h3>
          <div class="card-tools">
			<select class="form-control form-control-sm" name="user_role">
					<option value="">Choose</option>
					<?php if(count($user_type) > 0){foreach($user_type as $k => $v){ ?>
					<option value="<?php echo $v['role_id']?>" <?php echo (!empty($srch['role']) AND $srch['role'] == $v['role_id']) ? 'selected="selected"' : '';?>><?php echo $v['name'];?></option>
					<?php } } ?>
			</select>
          </div>
        </div>
       
		<div class="card-body table-responsive p-0" id="main_table">
			 <?php if(!empty($srch['token']) AND !empty($srch['role'])){ ?>
			<form action="" method="post">
			<input type="hidden" name="admin_role" value="<?php echo (!empty($srch['role'])) ? $srch['role'] : '';?>"/>
              <table class="table table-hover">
                <tbody>
				<tr>
					
                  <th style="width:25%">Menu</th>
                  <th style="width:25%">Sub Menu</th>
                  <th style="width:25%">Menu Code</th>
                  <th class="text-right" style="padding-right:15px;">Give Permission</th>
                </tr>
				<?php if(count($list) > 0){foreach($list as $key => $menu){ 
				$child = $menu['child'];
				?>
				<tr>
					<td>
						<?php echo $menu['name']; ?>
						<div><small><?php echo $menu['menu_desc'];?></small></div>
					</td>
					<td>&nbsp;</td>
					<td><?php echo $menu['menu_code']; ?></td>
                 
					<td align="right" style="padding-right:15px;">
						<div class="d-inline-flex align-items-start">
							<span class="check-inline">
								<input type="checkbox" class="parent_menu magic-checkbox" name="menu_code[]" value="<?php echo $menu['menu_code'].'|'.$menu['id'];?>" id="item_<?php echo $menu['id'];?>" data-menu-id="<?php echo $menu['id']; ?>" <?php echo in_array($menu['menu_code'], $user_permission) ? 'checked' : '';?>>
								<label for="item_<?php echo $menu['id'];?>"></label>
							</span>
							<a href="javascript:void(0)" onclick="toggleSubMenu('<?php echo $menu['id']?>', this)" class="mt-2 ml-4" style="display: inline-block; vertical-align: middle;"><i class="icon-feather-chevron-down fa-lg"></i></a>
						</div>
					</td>
                </tr>
				
				<?php if($child){foreach($child as $key => $child_menu){ ?>
				
				<tr class="tr-none child_menu childof-<?php echo $menu['id'];?> sub_trno_<?php echo $menu['id'];?>" style="display:none;">
					
				<td>&nbsp;</td>
                   <td>
					<?php echo $child_menu['name']; ?>
					<div><small><?php echo $child_menu['menu_desc'];?></small></div>
				  </td>
                  <td><?php echo $child_menu['menu_code']; ?></td>
                  <td align="right">
						<span class="check-inline">
						<input type="checkbox" class="magic-checkbox child_menu_<?php echo $menu['id']; ?>" name="menu_code[]" value="<?php echo $child_menu['menu_code'].'|'.$child_menu['id'];?>" id="item_<?php echo $child_menu['id'];?>" data-menu-id="<?php echo $child_menu['id']; ?>" <?php echo in_array($child_menu['menu_code'], $user_permission) ? 'checked' : '';?>>
						<label for="item_<?php echo $child_menu['id'];?>"></label>
						</span>
				  </td>
                </tr>
				<?php } } ?>
				
				<?php } }else{  ?>
				<tr>
                  <td colspan="10"><?php echo NO_RECORD; ?></td>
                 </tr>
				<?php } ?>
                
               </tbody>
			  </table>
				<div class="p-3 text-right">
					<button type="submit" class="btn btn-site mr-2">Save</button>
                    <a href="<?php echo base_url('permission/list_menu'); ?>" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
			<?php }else{  ?>
				<div class="p-3">
					<div class="callout callout-warning mb-0">
						<h4>No Role Selected</h4>
						<p class="mb-0">Please select a role first to given permission</p>
					</div>
              	</div>
			<?php } ?>
        </div>
		 <!-- /.box-body -->
		
      </div>
      <!-- /.box -->

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
function toggleSubMenu(parent_id, ele){
    if($(ele).is('.opened-subchild')){
        $('.childof-'+parent_id).slideToggle();
		$('.childof-'+parent_id).addClass('tr-block');
    }else{
        $('.opened-subchild').removeClass('opened-subchild');
        $(ele).addClass('opened-subchild');
        $('.child_menu').hide();
        $('.childof-'+parent_id).slideDown();
		$('.childof-'+parent_id).removeClass('tr-none');
    }
    
}
</script>
<script>
/* function add(p_id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$add_command);?>';
	if(p_id > 0){
		url += '&parent_id='+p_id;
	}
	load_ajax_modal(url);
} 

function edit(id){
	var url = '<?php echo base_url($curr_controller.'load_ajax_page?page='.$edit_command);?>&id='+id;
	load_ajax_modal(url);
}
*/
function deleteRecord(id, permanent){
	permanent = permanent || false;
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		console.log('ok');
		var url = '<?php echo base_url($curr_controller.'delete_menu');?>/'+id;
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
	
	 $('select[name="user_role"]').change(function(){
		var val = $(this).val();
		if(val == ''){
			return false;
		}
		window.location.href='<?php echo base_url('permission/give_permission?role=');?>'+val+'&type=list&token=<?php echo md5(time());?>';
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
	
	$('.parent_menu').change(function(){
		
		var checked = $(this).is(':checked');
		var menu_id = $(this).data('menuId');
		if(checked){
			if(menu_id){
				$('.sub_trno_'+menu_id).show();
				$('.child_menu_'+menu_id).prop('checked', true);
				
			}
		}else{
			if(menu_id){
				$('.sub_trno_'+menu_id).hide();
				$('.child_menu_'+menu_id).prop('checked', false);
			}
		}
	});


	$('.parent_menu').each(function(ind, item){
		var checked = $(this).is(':checked');
		var menu_id = $(this).data('menuId');
		if(checked){
			if(menu_id){
				$('.sub_trno_'+menu_id).show();
				
			}
		}else{
			if(menu_id){
				$('.sub_trno_'+menu_id).hide();
			}
		}
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
