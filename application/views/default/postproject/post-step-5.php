<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>

<div id="dataStep-5" style="display: none">
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
      <h4><?php echo __('postproject_visibility','Visibility');?></h4>
    </div>
    <div class="content with-padding">
      <div class="submit-field myradio">
        <label class="form-label"><?php echo __('postproject_who_can_see','Who can see your project?');?></label>
        <div class="btn-group">        
            <input type="radio" class="btn-check" id="defaultInlinePublic" name="projectVisibility" value="public" autocomplete="off" <?php if($projectData && $projectData['project_settings']->is_visible_anyone==1){echo "checked";}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->is_visible_anyone==1){echo "active";}?>" for="defaultInlinePublic"><i class="icon-line-awesome-eye"></i><br>
            <?php echo __('postproject_anyone','Anyone');?> </label>
          
            <input type="radio" class="btn-check" id="defaultInlinePrivate" name="projectVisibility" value="private" autocomplete="off" <?php if($projectData && $projectData['project_settings']->is_visible_private==1){echo "checked";}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->is_visible_private==1){echo "active";}?>" for="defaultInlinePrivate"><i class="icon-line-awesome-eye-slash"></i><br>
            <?php echo __('postproject_private','Private');?> </label>
          
            <input type="radio" class="btn-check" id="defaultInlineInvite" name="projectVisibility" value="invite" autocomplete="off" <?php if($projectData && $projectData['project_settings']->is_visible_invite==1){echo "checked";}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->is_visible_invite==1){echo "active";}?>" for="defaultInlineInvite"><i class="icon-line-awesome-lock"></i><br>
            <?php echo __('postproject_invite','Invite');?> </label>
        </div>
        <div class="clearfix"></div>
        <span id="projectVisibilityError" class="rerror"></span> </div>
      <div class="submit-field myradio mb-0">
        <label class="form-label"><?php echo __('postproject_how_many','How many freelancer you need for your project?');?></label>
        <div class="btn-group">          
            <input type="radio" class="btn-check no_of_freelancer_radio" id="defaultInlineOne" name="member_required" value="S" autocomplete="off" <?php if($projectData && $projectData['project']->project_member_required==1){echo "checked";}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project']->project_member_required==1){echo "active";}?>" for="defaultInlineOne"><i class="icon-feather-user"></i><br>
            <?php echo __('postproject_one_freelancer','One freelancer');?> </label>
                      
            <input type="radio" class="btn-check no_of_freelancer_radio" id="defaultInlineMulti" name="member_required" value="M" autocomplete="off" <?php if($projectData && $projectData['project']->project_member_required>1){echo "checked";}?>>
            <label class="btn btn-outline-light <?php if($projectData && $projectData['project']->project_member_required>1){echo "active";}?>" for="defaultInlineMulti"><i class="icon-feather-users"></i><br>
            <?php echo __('postproject_more_than','More than one freelancer');?> </label>
        </div>
        <div class="clearfix"></div>
        <span id="member_requiredError" class="rerror"></span> </div>
      <div class="row no_of_freelancer_display mt-3" style="<?php if($projectData && $projectData['project']->project_member_required>1){echo "";}else{?>display: none<?php }?>">
        <div class="col-xl-6">
          <div class="submit-field mb-0">
            <label class="form-label"><?php echo __('postproject_number_freelancer','Number of freelancer');?></label>
            <input type="text" class="form-control" name="no_of_freelancer" id="no_of_freelancer" value="<?php if($projectData && $projectData['project']->project_member_required){echo $projectData['project']->project_member_required;}?>"/>
            <span id="no_of_freelancerError" class="rerror"></span> </div>
        </div>
      </div>
    </div>    
  </div>
  <button class="btn btn-secondary backbtnproject" data-step="5"><?php echo __('postproject_back','Back');?></button>
      <button class="btn btn-primary nextbtnproject" data-step="5"><?php echo __('postproject_next','Next');?></button>
</div>
