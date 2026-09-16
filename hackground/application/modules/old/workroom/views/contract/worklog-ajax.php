<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

  <ul>
<?php if($contractDetails->worklog){
foreach($contractDetails->worklog as $l=>$log){
$hour=floor($log->total_time_worked/60);
$minutes = floor($log->total_time_worked %60);
//print_r($log);
?>
<li>

<div class="comment-content p-0">
<div class="arrow-comment"></div>
<div class="comment-by">
<?php 
$is_waiting_invoice=0;
if($show=='completed'){if($is_owner){}else{$is_waiting_invoice=1;}}
?>
<?php if($is_waiting_invoice){?>
<div class="checkbox">
  <input type="checkbox" name="plog[]" class="pending_invoice" value="<?php echo $log->log_id;?>" id="pending_invoice_<?php echo $log->log_id;?>" data-content="<?php echo $log->log_title;?> - <?php echo $hour;?>hr <?php echo $minutes;?>min" form="sendinvoiceform">
  <label for="pending_invoice_<?php echo $log->log_id;?>"><span class="checkbox-icon"></span>
  <h4 class="mb-1"> <b><?php echo $log->log_title;?></b></h4>
  </label>
</div>
<?php }else{?>
    <h4 class="mb-1"><i class="icon-feather-arrow-right text-success"></i> <b><?php echo $log->log_title;?></b></h4>
<?php }?>
    <span class="date"><i class="icon-feather-calendar"></i> <?php echo $log->reg_date;?> </span>
    <div class="row mt-3">
    	<div class="col-md-4"><b>Start Date:</b> <?php echo $log->start_time;?></div>
        <div class="col-md-4"><b>End Date:</b> <?php echo $log->end_time;?></div>
        <div class="col-md-4"><b>Duration:</b> <?php echo $hour;?>hr <?php echo $minutes;?>min </div>
    </div>
    
    	<?php if($log->log_status==1 && $log->invoice_id){
    	$invoice_url=SITE_URL.'invoice/details/'.md5($log->invoice_id);	
    		?>
    	<a href="<?php echo $invoice_url;?>" target="_blank"><span class="status badge badge-primary"><i class="fa fa-eye"></i> Invoice</span></a>
     	<?php } elseif($log->log_status==1){?>
          <span class="status badge badge-success"> Approved</span>
          <?php } elseif($log->log_status==2){?>
          <span class="status badge badge-danger"> Rejected</span>
          <?php }else{
          	if($is_owner){ ?>
          <div class="status"><button class="btn btn-success acceptbtn" data-sid="<?php echo $log->log_id;?>">Accept</button>&nbsp;
          <button class="btn btn-danger denybtn" data-sid="<?php echo $log->log_id;?>">Reject</button></div>
          <?php
          }else{
          	?>
          <span class="status badge badge-warning"> Pending</span>
          <?php	
          }
		}?>
    
</div>
<p><?php echo nl2br($log->log_details);?></p>
</div>
<?php if($log->log_attachment){?>
      <div class="attachments-container">
        <?php
	$attachments=json_decode($log->log_attachment);
	foreach($attachments as $k=>$val){
		if($val->file && file_exists(UPLOAD_PATH.'projects-files/workroom-submission/'.$val->file)){
			$path_parts = pathinfo($val->name);
	?>
        <a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/workroom-submission/'.$val->file;?>" target="_blank" class="attachment-box "><span><?php echo $path_parts['filename'];?></span><i><?php echo strtoupper($path_parts['extension']);?></i></a>
        <?php
		}	
	}
	?>
      </div>
<?php }?>
<?php if($log->log_status==2){?>
<ul>
<li>
  <div class="comment-content p-0">
    <div class="arrow-comment"></div>
    <div class="comment-by"><b class="text-danger">Submission got rejected</b> </div>
    <p><b>Reason:</b> <?php echo nl2br($log->reject_reason);?></p>
  </div>
</li>
</ul>
<?php }?>        
</li>
<?php		
}
}else{?>
<p>No record</p>
<?php }?>
</ul>

