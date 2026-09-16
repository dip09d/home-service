<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div id="dataStep-4" style="display: none">		
    <div class="dashboard-box">
        <!-- Headline -->
        <div class="headline">
            <h4><?php echo __('postproject_expertise','Expertise');?>  </h4>
        </div>
        <div class="content with-padding">    
            <div class="submit-field mb-0">
                <label class="form-label"><?php echo __('postproject_required_skills','Select required skills');?></label>
                <div class="keywords-list skillContaintag"></div>
			   <input  class="form-control tagsinput_skill" name="skills" id="skills" value="">
                <span id="skillsError" class="rerror"></span>

            </div>
        </div>        
    </div>
	<button class="btn btn-secondary backbtnproject" data-step="4"><?php echo __('postproject_back','Back');?></button>
	<button class="btn btn-primary nextbtnproject" data-step="4"><?php echo __('postproject_next','Next');?></button>			
</div>