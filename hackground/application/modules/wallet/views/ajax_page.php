<?php if($page == 'add_fund'){ ?>
<div class="modal-header">
	<h4 class="modal-title"><?php echo $title;?><small><br> <?php echo $info;?></small></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="modal-body">
	<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
		<input type="hidden" name="ID" value="<?php echo $ID;?>"/>
		<div class="form-group">
          <label for="amount" class="form-label">Amount </label>
          <input type="text" class="form-control reset_field" id="amount" name="amount" autocomplete="off">
        </div>
        <div class="form-group">
          <label for="reason" class="form-label">Reason </label>
          <input type="text" class="form-control reset_field" id="reason" name="reason" autocomplete="off">
        </div>
		<button type="submit" class="btn btn-site">Add</button>
	</form>
</div>

<script>

init_plugin();

function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd){
		if(res.cmd == 'reload'){
			location.reload();
		}else if(res.cmd == 'reset_form'){
			var form = $('#add_form');
			form.find('.reset_field').val('');
		}		
		
	}
}

</script>
<?php } ?>
<?php if($page == 'add'){ ?>
<div class="modal-header">
<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
             
				
				<?php
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off">
                </div>
				<?php } ?>
               
			   <div class="form-group">
			   <label class="form-label">Status</label>
                <div class="radio-inline">
					<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
					<label for="status_1">Active</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0">
					  <label for="status_0">Inactive</label> 
				  </div>
              </div>
			  
			  <div class="form-group">
				<div>
			     <input type="checkbox" name="add_more" value="1" class="magic-checkbox" id="add_more">
                  <label for="add_more">Add more record</label>
				</div>
              </div>
			  
    
                <button type="submit" class="btn btn-site">Add</button>
             
        </form>
</div>

<script>

init_plugin();

function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd){
		if(res.cmd == 'reload'){
			location.reload();
		}else if(res.cmd == 'reset_form'){
			var form = $('#add_form');
			form.find('.reset_field').val('');
		}		
		
	}
}

</script>
<?php } ?>

<?php if($page == 'edit'){ ?>
<div class="modal-header">
<h4 class="modal-title"><?php echo $title;?></h4>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span></button>
	
</div>
<div class="modal-body">
		<form role="form" id="add_form" action="<?php echo $form_action;?>" onsubmit="submitForm(this, event)">
			  <input type="hidden" name="ID" value="<?php echo $ID;?>"/>
             
				<?php
				
				$lang = get_lang();
				foreach($lang as $k => $v){ ?>
				<div class="form-group">
                  <label for="name_<?php echo $v;?>">Name (<?php echo $v;?>)</label>
                  <input type="text" class="form-control reset_field" id="name_<?php echo $v;?>" name="lang[name][<?php echo $v; ?>]" autocomplete="off" value="<?php echo !empty($detail['lang']['name'][$v]) ? $detail['lang']['name'][$v] : '';?>">
                </div>
				<?php } ?>
				
			   <div class="form-group">
			   <label class="form-label">Status</label>
                <div class="radio-inline">
					<input type="radio" name="status" value="1" class="magic-radio" id="status_1" checked>
					<label for="status_1">Active</label> 
				</div>
				 <div class="radio-inline">
					  <input type="radio" name="status" value="0" class="magic-radio" id="status_0" <?php echo $detail['status'] == '0' ?  'checked' : ''; ?>>
					  <label for="status_0">Inactive</label> 
				  </div>
              </div>
			  
              
                <button type="submit" class="btn btn-site">Save</button>
              
        </form>
</div>

<script>

init_plugin();

function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>
<?php } ?>

<?php if($page == 'single_txn_detail'){ ?>
<style>
@media (min-width: 768px){
	.modal-dialog {
	    width: 750px;
	    margin: 30px auto;
	}
}

</style>
<table class="table table-hover">
<tr>
  <th style="width:15%">TXN ROW ID#</th>
  <th style="width:20%">Transaction Date</th>
   <th style="width:30%">Info</th>
 <!-- <th style="width:20%">Wallet Owner</th>
  <th style="width:5%">Wallet ID</th>
  <th style="width:20%">Ref.</th>
  <th style="width:20%">From Wallet</th>
  <th style="width:20%">To Wallet</th>-->
  <th style="width:10%" class="text-center">Debit (<?php echo get_setting('site_currency');?>)</th>
  <th style="width:10%" class="text-center">Credit (<?php echo get_setting('site_currency');?>)</th>
  <!-- <th style="width:30%" class="text-center">Description</th>-->
 
</tr>
<?php foreach($detail as $k => $v){
$wallet_user_id= getField('user_id', 'wallet', 'wallet_id', $v['wallet_id']);
$wallet_title = getField('title', 'wallet', 'wallet_id', $v['wallet_id']);
/*if($wallet_user_id>0){
	$wallet_title = getField('member_name', 'member', 'member_id', $wallet_user_id);
}*/
$tool=$url="";
if($v['description_tkey']){
	if($v['description_tkey']=='OrderID'){
		$url=base_url('orders/order_detail/'.$v['relational_data']);
	}
	if($v['description_tkey']=='Commission'){
		$tool.='Commission for OrderID';
		$url=base_url('orders/order_detail/'.$v['relational_data']);
	}elseif($v['description_tkey']=='PID'){
		$tool.='Proposal Id';
	}else{
		$tool.=str_replace('_',' ',$v['description_tkey']);	
	}
	
	if($v['relational_data']){
		
		$d=json_decode($v['relational_data']);
		if(is_object($d)){
			$tool.=' - ';
			if($v['description_tkey']=='Bank_Transfer'){
				$tool.=' <br>Account Number : '.$d->to;
				$tool.=' <br>Bank Name : '.$d->name;
				$tool.=' <br>Swift Code : '.$d->swift;
				$tool.=' <br>IBAN : '.$d->iban;
				
			}else{
				foreach($d as $kd=>$vald){
					$tool.=' <br>'.str_replace('_',' ',$kd).' : '.$vald;	
				}	
			}
		}else{
			$tool.=' - '.$v['relational_data'];
		}
	}
}
$addcss="";
if($wallet_id && $wallet_id>0){
	if($v['wallet_id']!=$wallet_id){
	$addcss='style="font-style: italic;color: grey;"';	
	}
}
$member_name=$member_email=$project_id=$ref=$info=$duration=$package="";
if($v['ref_data_cell']){
	$ref_data_cell=json_decode($v['ref_data_cell']);
	if(is_object($ref_data_cell)){
		$fromwallet=!empty($ref_data_cell->FW) ? $ref_data_cell->FW : '-';
		$towallet=!empty($ref_data_cell->TW) ? $ref_data_cell->TW : '-';
		
		
		
		$info.=' <b>From Wallet :</b> '.$fromwallet;
		$info.= '<br/>';
		$info.=' <b>To Wallet :</b> '.$towallet;
		
	}
}
?>
<tr <?php echo $addcss;?>>
  <td>
  <?php echo $v['wallet_transaction_row_id']; ?> 
  <a href="<?php echo JS_VOID; ?>" data-toggle="tooltip" data-html="true" title="<?php echo $tool; ?>"><i class="fa fa-info-circle"></i></a>
  </td>
  <td><?php echo format_date_time($v['transaction_date']); ?></td>
   <td><?php echo $info;?></td>
  <!--<td><?php echo $wallet_title; ?></td>
    <td><a href="<?php echo base_url('wallet/txn_detail/'.$v['wallet_id'])?>" target="_blank"><?php echo $v['wallet_id']; ?></a></td>
    <td><?php if($url){?>
    <a href="<?php echo $url?>" target="_blank"><?php  echo $tool;?></a>
    <?php }else{ echo $tool;} ?></td>
    <td class=""><?php echo $from_wallet;?></td>
    <td class=""><?php echo $to_wallet;?></td>-->
  <td class="text-center text-red"><?php echo format_money($v['debit']); ?></td>
  <td class="text-center text-green"><?php echo format_money($v['credit']); ?></td>
 <!-- <td class="text-center"><?php echo $tool;?></td>-->
 
</tr>
<?php } ?>
</table>
<div class="text-center p-3">
<?php if($org_ref){
	echo '<a href="'.base_url('wallet/online_txn_data?ref_key='.$org_ref).'" target="_blank">View Payment DATA</a>';
}?>
</div>
<script>

init_plugin();

</script>

<?php } ?>

<?php if($page == 'online_txn_data'){ ?>
<?php
$request_value = json_decode($detail['request_value'], true);
$response_value = json_decode($detail['response_value'], true);
if($type == 'request_data'){
	get_print($request_value, false);
}else if($type == 'response_data'){
	get_print($response_value, false);
}

 ?>


<?php } ?>
