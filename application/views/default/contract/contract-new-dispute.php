<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($contractDetails,false);
if($contractDetails->is_hourly){
	$contract_details_url=get_link('ContractDetailsHourly').'/'.md5($contractDetails->contract_id);
}else{
	$contract_details_url=get_link('ContractDetails').'/'.md5($contractDetails->contract_id);
}

?>
<section class="section">
<div class="container">
      <a href="<?php echo $contract_details_url;?>" class="mb-1 btn btn-link p-0"><i class="icon-feather-chevron-left"></i><?php echo __('contract_dispute_b_contract','Back to Contract');?> </a>
      
        <h1 class="display-4"><?php echo __('contract_dispute_submit','Submit Dispute');?></h1>
        <div class="row">
          <div class="col-lg-9">
<form action="" method="post" accept-charset="utf-8" id="newdisputeform" class="form-horizontal" role="form" name="newdisputeform" onsubmit="return false;">  
            <div class="panel mb-4">
              <div class="panel-header">
                <h4><?php echo __('contract_end_details','Details');?></h4>
              </div>
              <div class="panel-body">
              <?php if($is_owner){?>
              <p><b><?php echo __('contract_end_contractor','Contractor:');?></b> <?php echo $contractDetails->contractor->member_name;?></p>
              <?php }else{?>
              <p><b><?php echo __('contract_end_client','Client:');?></b> <?php echo ($contractDetails->owner->organization_name ? $contractDetails->owner->organization_name:$contractDetails->owner->member_name);?></p>
              <?php }?>
                <p><b><?php echo __('contract_end_c_title','Contract Title:');?></b> <?php echo $contractDetails->contract_title;?></p>
              
              </div>
            </div>
            <div class="panel mb-4">
              <div class="panel-header d-flex">
                <h4 class="mt-2"><?php echo __('contract_dispute_m_dispute','Milestones for dispute');?></h4>
               
                </div>
              <div class="panel-body">
              <ul >
 <?php 
 $show_request_btn=FALSE;
 if($contractDetails->milestone){
		foreach($contractDetails->milestone as $m=>$milestone){
			$enc_mid=md5($milestone->contract_milestone_id);
			$check_option_false=false;
			if($contractDetails->is_contract_ended){
				$check_option_false=TRUE;
			}elseif($milestone->is_approved){
				$check_option_false=TRUE;
			}elseif(!$milestone->is_escrow){
				$check_option_false=TRUE;	
			}elseif($milestone->project_contract_dispute_id){
				$check_option_false=TRUE;		
			}else{
				$show_request_btn=true;
			}
			if($check_option_false){
				continue;
			}
?>
<li>
<div class="checkbox">
  <input type="checkbox" name="mid[]" class="work_id" value="<?php echo $enc_mid;?>" id="mid_<?php echo $enc_mid;?>"  form="newdisputeform" <?php if($check_option_false){echo 'disabled';}?>>
  <label for="mid_<?php echo $enc_mid;?>"><span class="checkbox-icon"></span>
  
  <div class="milestone-item pl-1">
  		<h4 class="mb-1"> <b><?php echo $milestone->milestone_title;?></b></h4> 
        <b><?php echo __('contract_details_budgets','Budget:');?></b> <?php echo $milestone->milestone_amount;?> <br>
        <?php if($milestone->is_approved){?>
        <b><?php echo __('contract_details_complete','Completed:');?></b><?php echo $milestone->approved_date;}else{?><b><?php echo __('contract_dispute_deu_date','Due Date:');?></b> <?php echo $milestone->milestone_due_date; }?> 
    </div>
  </label>
</div>


  <?php 
  if(!$milestone->is_approved){
  	
  if(!$milestone->is_escrow){
	?>
	<p class="alert alert-warning"><?php echo __('contract_details_not_start','Not started');?> </p>
	<?php	
	}elseif($milestone->project_contract_dispute_id){
?>
	<p class="alert alert-warning"><?php echo __('contract_dispute_already','Already disputed');?></p>
<?	
	}
	
  }
  ?>

</li>
 <?php		
		}
	}
	?>
	</ul>
<div id="midError" class="rerror"></div>
              </div>
				
            </div>
           	
            <?php
            if($show_request_btn){?>
				<button class="btn btn-primary nextbtnapply"><?php echo __('contract_dispute_submit','Submit Dispute');?></button> &nbsp;
				<button class="btn btn-secondary backbtnapply"><?php echo __('contract_end_cancel','Cancel');?></button>
			<?php }else{?>
			<p><?php echo __('contract_dispute_no_new','No new milestone for dispute');?> <a class="backbtnapply" href="<?php echo VZ;?>"><?php echo __('contract_dispute_b_contract','back to contarct');?></a>.</p>
			<?php }?>
			
</form>
          </div>

        </div>
      </div>
</section>
<script>
var SPINNER='<?php load_view('inc/spinner',array('size'=>30));?>';
var c_id='<?php echo md5($contractDetails->contract_id);?>';
	var main=function(){
		$('.backbtnapply').on('click',function(){
			window.location.href="<?php echo $contract_details_url;?>";
		});
		$('.nextbtnapply').on('click',function(){
			var buttonsection=$(this);
			buttonsection.attr('disabled','disabled');
			var buttonval = buttonsection.html();
			buttonsection.html(SPINNER).attr('disabled','disabled');
			var formID="newdisputeform";
			$.ajax({
		        type: "POST",
		        url: "<?php D(get_link('MakeDisputeURLFormCheckAJAXURL'))?>/",
		        data:$('#'+formID).serialize()+'&c_id='+c_id,
		        dataType: "json",
		        cache: false,
				success: function(msg) {
					buttonsection.html(buttonval).removeAttr('disabled');
					clearErrors();
					if (msg['status'] == 'OK') {
						bootbox.alert({
							title:'End Contract',
							message: '<?php D(__('dispute_contract_success_message','Dispute created succesfully.'));?>',
							buttons: {
							'ok': {
								label: 'Ok',
								className: 'btn-primary float-end'
								}
							},
							callback: function () {
								window.location.href="<?php echo $contract_details_url;?>";
						    }
						});
					} else if (msg['status'] == 'FAIL') {
						registerFormPostResponse(formID,msg['errors']);
					}
				}
			})		
		});
	}
</script>