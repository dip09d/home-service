<a class="btn btn-site float-right mb-2" href="<?php echo base_url('worker/view_edit/invoice_add/'.$worker_id)?>">Create Invoice</a>
<div class="box-body table-responsive no-padding" id="main_table">
    <table class="table table-hover">
    <tbody>
    <tr>
        
        <th style="width:10%">Number</th>
        <th style="width:20%" class="text-center">Total</th>
        <th style="width:25%">Info</th>
        <th style="width:20%" class="text-center">Date</th>
        <th style="width:15%">Status</th>
        <th align="right">Action</th>
    </tr>
    <?php $currency = get_setting('site_currency'); 
    if(count($list) > 0){foreach($list as $k => $v){ 
        $token=md5(date('Y-m-d').'-ORGUP');
        $invoice_url=SITE_URL.'/invoice/details/'.md5($v['invoice_id']).'?auth='.$token;
    $status = '-';
    if($v['invoice_status'] == -1){
        $status = '<span class="badge badge-danger">Deleted</span>';
        $status_txt = 'Deleted';
    }elseif($v['invoice_status'] == 1){
        $status = '<span class="badge badge-success">Paid</span>
        <a href="'.VZ.'" data-toggle="tooltip" title="'.$v['change_reason'].'"> <i class="icon-feather-info"></i> </a>';
        if($v['attachment']){
            $attachment=UPLOAD_HTTP_PATH.'invoice/attachment/'.$v['attachment'];
            $status .= '<a data-toggle="tooltip" title="Attachment" href="'.$attachment.'" target="_blank"><i class="icon-feather-file text-success fa-lg"></i></a>';  
        }
        $status_txt = 'Paid';
    }
    elseif($v['invoice_status'] == 2){
        $status = '<span class="badge badge-danger">Rejected</span>
        <a href="'.VZ.'" data-toggle="tooltip" title="'.$v['change_reason'].'"> <i class="icon-feather-info"></i> </a>';
        $status_txt = 'Rejected';
    }
    else{
        $status = '<span class="badge badge-warning">Pending</span>';
        $status_txt = 'Pending';
    }
    $sender=unserialize($v['issuer_information']);
    //print_r($sender);
    $receiver=unserialize($v['recipient_information']);
    //print_r($receiver);
    $info=array();
    
    $info[]='<p class="mb-0"><b>Invoice Type:</b> '.$v['description_tkey'].'</p>';
    $info[]='<p class="mb-0"><b>Sender:</b> <a href="'.base_url('member/list_record').'?member_id='.$v['issuer_member_id'].'" target="_blank">'.$sender['I_name'].'</a></p>';
    $info[]='<p><b>Receiver:</b> <a href="'.base_url('member/list_record').'?member_id='.$v['recipient_member_id'].'" target="_blank">'.$receiver['R_name'].'</a></p>';
    ?>
    <tr>
        
    
        <td># <?php echo $v['invoice_number']; ?></td>
        <td class="text-center"><?php echo $currency.''.round($v['total'],2); ?></td>
        <td><?php echo implode('',$info); ?></td>
        <td class="text-center"><?php echo format_date_time($v['invoice_date']); ?></td>
        <td><?php echo $status; ?></td>
        <td align="right">
        <div class="dropdown">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $status_txt; ?></button>					 					  <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" target="_blank" href="<?php echo $invoice_url; ?>&is_download=1">Download</a>												
            <!-- <a class="dropdown-item" target="_blank" href="<?php echo $invoice_url; ?>">Invoice details</a>	 -->	
                <?php if($v['invoice_status'] == 0){?>
                <a class="dropdown-item" href="<?php echo JS_VOID; ?>" onclick="return deleteRecord('<?php echo $v['invoice_id']; ?>')">Delete</a>									
            <a class="dropdown-item"  href="<?php echo JS_VOID; ?>" onclick="markaspaid('<?php echo $v['invoice_id']; ?>')">Mark As Paid</a>	
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
<?php if($links){?>
		<nav>
			<ul class="pagination justify-content-center">
			<?php echo $links;?>
			</ul>
		</nav>
		 <?php }?>
<script>
function deleteRecord(id, permanent){
	permanent = permanent || false;
	var c = confirm('Are you sure to delete this record ?');
	if(c){
		console.log('ok');
		var url = '<?php echo base_url('invoice/delete_record');?>/'+id;
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
function markaspaid(id){
	var url = '<?php echo base_url('invoice/load_ajax_page?page=markaspaid');?>&id='+id;
	load_ajax_modal(url);
}
$(function(){
	
	init_plugin(); /* global.js */
	init_event();
	
	
});
</script>