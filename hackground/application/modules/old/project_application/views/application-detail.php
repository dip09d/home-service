<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//get_print($proposaldetails,FALSE);
//get_print($projects,FALSE);
$is_hourly=$projects['project_settings']->is_hourly;
$HireApplicationProjecURL='#';
$CreateMessageRoomURL='#';
$logo=getMemberLogo($member_id);
?>

<style>
.boxed-list {
    display: block;
}
.boxed-list ul.boxed-list-ul {
    padding: 0;
    margin: 0;
    list-style: none;
}
.boxed-list ul.boxed-list-ul > li {
    display: block;
    background-color: #fff;
    padding: 20px;
    border-bottom: 1px solid #ddd;
}
.boxed-list-item {
    display: flex;
}
.boxed-list-item .item-content {
    flex: 1;
}
.boxed-list-item .item-content h4 a, .boxed-list-item .item-content h4 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}
.boxed-list-item .item-details {
    display: flex;
    margin-top: 5px;
}
.boxed-list-item .item-details .detail-item {
    margin-right: 15px;
    color: #808080;
}
.boxed-list-item .item-details .detail-item i {
    margin-right: 3px;
    position: relative;
    top: 0;
}
</style>
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
       
        <div id="main_table">
             <!-- content area -->
             <div class="card mb-4">
              <div class="card-header">
                <h4 class="card-title"><i class="icon-feather-file-text text-site"></i> Cover Letter </h4>
              </div>
              <div class="card-body">
                <ec id="profile-overview-data">
                  <?php D(ucfirst(nl2br($proposaldetails['proposal']->bid_details)))?>
                </ec>
              </div>
            </div>
        <?php if($is_hourly){}else{
		if($proposaldetails['proposal']->bid_by_project!=1){
			if($proposaldetails['proposal']->project_bid_milestones){
		?>
        <div class="card mb-4">
          <div class="card-header">
            <h4 class="card-title"><i class="icon-material-outline-account-balance-wallet text-site"></i> Milestone </h4>
          </div>
          <div class="boxed-list">
            <ul class="boxed-list-ul">
              <?php foreach($proposaldetails['proposal']->project_bid_milestones as $k=>$val){?>
              <li class="milestone-contain">
                <div class="boxed-list-item"> 
                  <!-- Content -->
                  <div class="item-content">
                    <h4><?php echo ucfirst($val->bid_milestone_title);?></h4>
                    <div class="item-details margin-top-7">
                      <div class="detail-item"><i class="icon-material-outline-account-balance-wallet"></i> Amount: <?php echo priceSymbol().priceFormat($val->bid_milestone_amount);?></div>
                      <div class="detail-item"><i class="icon-material-outline-date-range"></i> Due date: <?php echo $val->bid_milestone_due_date;?></div>
                    </div>
                  </div>
                </div>
              </li>
              <?php }?>
            </ul>
          </div>
        </div>
        <?php }}}?>
        <?php if($proposaldetails['project_question']){?>
        <div class="card mb-4">
          <div class="card-header">
            <h4 class="card-title"><i class="icon-line-awesome-question-circle text-site"></i> Question </h4>
          </div>
          <div class="card-body">
            <?php
                foreach($proposaldetails['project_question'] as $k=>$val){
                ?>
            <div class="form-group">
              <label><b><?php echo $k+1;?>. <?php echo $val->question_title;?></b></label>
              <p><?php echo $val->question_answer;?></p>
            </div>
            <?php		
                    }
                ?>
          </div>
        </div>
        <?php }?>
        <?php if($proposaldetails['proposal']->bid_attachment){?>
        <div class="card mb-4">
          <div class="card-header">
            <h4 class="card-title"><i class="icon-feather-paperclip text-site"></i> Attachments </h4>
          </div>
          <div class="card-body">
                <div class="attachments-container">
                  <?php
                    $attachments=json_decode($proposaldetails['proposal']->bid_attachment);
                    foreach($attachments as $k=>$val){
                        if($val->file && file_exists(UPLOAD_PATH.'projects-files/projects-applications/'.$val->file)){
                            $path_parts = pathinfo($val->name);
                    ?>
                  <a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/projects-applications/'.$val->file;?>" target="_blank" class="attachment-box "><span><?php echo $path_parts['filename'];?></span><i><?php echo strtoupper($path_parts['extension']);?></i></a>
                  <?php
                        }	
                    }
                    ?>
                </div>
                  </div>
        </div>
        <?php } ?>
        </div>
		
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<script>
$(document).ready(function(){
init_plugin();
});
</script>  
