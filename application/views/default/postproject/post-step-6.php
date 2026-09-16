<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>

<div id="dataStep-6" style="display: none">
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
      <h4><?php echo __('postproject_budget','Budget');?> </h4>
    </div>
    <div class="content with-padding">
      <div class="-mb-125">
      <div class="submit-field myradio">
        <label class="form-label"><?php echo __('postproject_how_pay','How would you like to pay freelancer ?');?></label>
        <div class="btn-group">          
            <input type="radio" class="btn-check project_payment_type" id="defaultInlineHourly" name="projectPaymentType" value="hourly" autocomplete="off" <?php if($projectData && $projectData['project_settings']->is_hourly==1){echo "checked";}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->is_hourly==1){echo "active";}?>" for="defaultInlineHourly"><i class="icon-feather-clock"></i><br>
            <?php echo __('postproject_hourly','Hourly');?> </label>
          
            <input type="radio" class="btn-check project_payment_type" id="defaultInlineFixed" name="projectPaymentType" value="fixed" autocomplete="off" <?php if($projectData && $projectData['project_settings']->is_fixed==1){echo "checked";}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->is_fixed==1){echo "active";}?>" for="defaultInlineFixed">
            <i class="icon-feather-tag"></i><br>
            <?php echo __('postproject_fixed','Fixed');?> </label>
        </div>
        <div class="clearfix"></div>
        <span id="projectPaymentTypeError" class="rerror"></span> 
      </div>
      <div class="submit-field myradio">
        <label class="form-label"><?php echo __('postproject_experience_level','Experience level required?');?></label>
        <div class="btn-group">
          <?php if($all_projectExperienceLevel){
			foreach($all_projectExperienceLevel as $key=>$keydata){
			?>          
            <input type="radio" class="btn-check" name="experience_level" id="defaultInline<?php D($keydata->experience_level_key);?>" autocomplete="off"  value="<?php D($keydata->experience_level_id);?>" <?php if($projectData && $projectData['project_settings']->experience_level_key==$keydata->experience_level_key){echo 'checked';}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->experience_level_key==$keydata->experience_level_key){echo 'active';}?>" for="defaultInline<?php D($keydata->experience_level_key);?>"><i class="icon-feather-<?php D($keydata->experience_level_key);?>"></i><br>
            <?php D($keydata->experience_level_name);?>
          </label>
          <?php
				}
			}
			?>
        </div>
        <div class="clearfix"></div>
        <span id="experience_levelError" class="rerror"></span> </div>
      <div class="row fixed_project_display" <?php if($projectData && $projectData['project_settings']->is_fixed==1){}else{?>style="display: none" <?php }?>>
        <div class="col-xl-6">
          <div class="submit-field">
            <label class="form-label"><?php echo __('postproject_specific_budget','Do you have a specific budget?');?></label>
            <input type="text" class="form-control" name="fixed_budget" id="fixed_budget" value="<?php if($projectData && $projectData['project_settings']->budget){echo $projectData['project_settings']->budget;}?>">
            <span id="fixed_budgetError" class="rerror"></span> </div>
        </div>
      </div>
      <div class="hourly_project_display" <?php if($projectData && $projectData['project_settings']->is_hourly==1){}else{?>style="display: none" <?php }?>>
        <div class="submit-field myradio">
          <label class="form-label"><?php echo __('postproject_project_duration','Project duration');?></label>
          <div class="btn-group">
            <?php if($all_projectDuration){
			foreach($all_projectDuration as $key=>$keydata){
			?>            
              <input type="radio" class="btn-check" name="hourly_duration" id="defaultInline<?php D($key);?>" autocomplete="off"  value="<?php D($key);?>" <?php if($projectData && $projectData['project_settings']->hourly_duration==$key){echo "checked";}?>>
              <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->hourly_duration==$key){echo "active";}?>" for="defaultInline<?php D($key);?>"><i class="icon-line-awesome-<?php D($key);?>"></i><br>
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
        <div class="submit-field myradio">
          <label class="form-label"><?php echo __('postproject_time_required','Time required for this project');?></label>
          <div class="btn-group">
            <?php if($all_projectDurationTime){
			foreach($all_projectDurationTime as $key=>$keydata){
			?>            
              <input type="radio" class="btn-check" name="hourly_duration_time" id="defaultInlineDuration<?php D($key);?>" autocomplete="off"  value="<?php D($key);?>" <?php if($projectData && $projectData['project_settings']->hourly_time_required==$key){echo "checked";}?>>
              <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->hourly_time_required==$key){echo "active";}?>" for="defaultInlineDuration<?php D($key);?>"><i class="icon-feather-<?php D($key);?>"></i><br>
              <?php D($keydata['name']);?>
            </label>
            <?php
				}
			}
			?>
          </div>
          <div class="clearfix"></div>
          <span id="hourly_duration_timeError" class="rerror"></span>
        </div>
      </div>
      </div>
    </div>
  </div>
  <button class="btn btn-secondary backbtnproject" data-step="6"><?php echo __('postproject_back','Back');?></button>
  <button class="btn btn-primary nextbtnproject" data-step="6"><?php echo __('postproject_next','Next');?></button>
</div>
