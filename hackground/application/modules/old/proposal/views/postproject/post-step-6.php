<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>

<div id="dataStep-6" style="display: none">
  <div class="dashboard-box margin-top-0"> 
    <!-- Headline -->
    <div class="headline">
      <h3> Budget </h3>
    </div>
    <div class="content with-padding">
      <div class="submit-field myradio">
        <label>How would you like to pay freelancer ?</label>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn">
            <input type="radio" class="project_payment_type" id="defaultInlineHourly" name="projectPaymentType" value="hourly" autocomplete="off">
            <i class="icon-feather-clock"></i><br>
            Hourly </label>
          <label class="btn">
            <input type="radio" class="project_payment_type" id="defaultInlineFixed" name="projectPaymentType" value="fixed" autocomplete="off">
            <i class="icon-feather-tag"></i><br>
            Fixed </label>
        </div>
        <div class="clearfix"></div>
        <span id="projectPaymentTypeError" class="rerror"></span> </div>
      <div class="submit-field myradio">
        <label>Experience level required ?</label>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <?php if($all_projectExperienceLevel){
										foreach($all_projectExperienceLevel as $key=>$keydata){
										?>
          <label class="btn">
            <input type="radio" name="experience_level" id="defaultInline<?php D($keydata->experience_level_key);?>" autocomplete="off"  value="<?php D($keydata->experience_level_id);?>">
            <i class="icon-feather-<?php D($keydata->experience_level_key);?>"></i><br>
            <?php D($keydata->experience_level_name);?>
          </label>
          <?php
										}
									}
									?>
        </div>
        <div class="clearfix"></div>
        <span id="experience_levelError" class="rerror"></span> </div>
      <div class="row fixed_project_display" style="display: none">
      	<div class="col-xl-6">
        <div class="submit-field mb-0">
          <label>Do you have a specific budget ?</label>
          <input type="text" class="form-control" name="fixed_budget" id="fixed_budget" value="">
          <span id="fixed_budgetError" class="rerror"></span> </div>
        </div>
      </div>
      <div class="hourly_project_display" style="display: none">
        <div class="submit-field myradio">
          <label>Project duration</label>
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <?php if($all_projectDuration){
										foreach($all_projectDuration as $key=>$keydata){
										?>
            <label class="btn">
              <input type="radio" name="hourly_duration" id="defaultInline<?php D($key);?>" autocomplete="off"  value="<?php D($key);?>">
              <i class="icon-line-awesome-<?php D($key);?>"></i><br>
              <?php D($keydata['name']);?>
            </label>
            <?php
										}
									}
									?>
          </div>
          <div class="clearfix"></div>
          <span id="hourly_durationError" class="rerror"></span> </div>
      </div>
      <div class="hourly_project_display" style="display: none">
        <div class="submit-field myradio mb-0">
          <label>Time required for this project</label>
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <?php if($all_projectDurationTime){
										foreach($all_projectDurationTime as $key=>$keydata){
										?>
            <label class="btn">
              <input type="radio" name="hourly_duration_time" id="defaultInlineDuration<?php D($key);?>" autocomplete="off"  value="<?php D($key);?>">
              <i class="icon-feather-<?php D($key);?>"></i><br>
              <?php D($keydata['name']);?>
            </label>
            <?php
										}
									}
									?>
          </div>
          <div class="clearfix"></div>
          <span id="hourly_duration_timeError" class="rerror"></span> </div>
      </div>
    </div>
    <div class="dashboard-box-footer">
      <button class="btn btn-secondary backbtnproject" data-step="6">Back</button>
      <button class="btn btn-site nextbtnproject" data-step="6">Next</button>
    </div>
  </div>
</div>
