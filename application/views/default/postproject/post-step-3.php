<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($projectData,TRUE);
?>
<div id="dataStep-3" style="display: none">
    <div class="dashboard-box">
        <!-- Headline -->
        <div class="headline">
            <h4><?php echo __('postproject_details','Details');?>  </h4>
        </div>
        <div class="content with-padding">
            <div class="submit-field myradio mb-0">
                <label class="form-label"><?php echo __('postproject_what_type_project','What type of project you have ?');?></label>                
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <?php if($all_projectType){
                    foreach($all_projectType as $key=>$keydata){
                    ?>                    
                    <input type="radio" class="btn-check" name="projectType" id="defaultInline<?php D($key);?>" autocomplete="off"  value="<?php D($key);?>" <?php if($projectData && $projectData['project_settings']->project_type_code==$key){echo 'checked';}?>>
                    <label class="btn btn-outline-light <?php if($projectData && $projectData['project_settings']->project_type_code==$key){echo 'active';}?>" for="defaultInline<?php D($key);?>"><i class="icon-material-<?php D($key);?>"></i><br>
                    <?php D($keydata['name']);?>
                    </label>
                <?php
                    }
                }
                ?>
                </div>
                <div class="clearfix"></div>
                <span id="projectTypeError" class="rerror"></span>

            </div>
        </div>
    </div>
				
				
    <div class="dashboard-box">
        <!-- Headline -->
        <div class="headline">
            <h4><?php echo __('postproject_screen_question','Screen Question ');?> <span class="text-muted"><?php echo __('optional','optional');?></span></h4>
        </div>
        <div class="content with-padding">
            <div class="submit-field mb-0">
                <label class="form-label"><?php echo __('postproject_add_screen','Add screen question / require a cover letter');?> </label>
                <div id="addQuestion_container">
                <?php if($projectData && $projectData['project_question']){
                		$question_previous=$projectData['project_question'];
                		foreach($question_previous as $k=>$ques){
						?>	
						<div class="question_sec input-group mb-3">
			                <input type="text" class="form-control input-text with-border" name="pre_question[<?php echo $ques->question_id;?>]" placeholder="Enter question" value="<?php echo $ques->question_title;?>">
			               <a href="<?php D(VZ);?>" class="btn text-danger" onclick="$(this).closest('.question_sec').remove()"><i class="icon-feather-x f20"></i></a>			                
		                </div>
                		<?php
                		}
                	}?>
                
                
                
                </div>
                <span id="questionError" class="rerror"></span>
                <a href="javascript:void(0)" class="btn btn-outline-success" id="addQuestion"><?php echo __('postproject_add','+ Add');?> </a>    
            </div>

            <div class="mt-3" id="is_cover_required_display" style="display: none">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_cover_required" value="1" id="is_cover_required" checked>
                    <label class="form-check-label" for="is_cover_required">
                        <?php echo __('postproject_cover_letter','Required cover letter');?>
                    </label>
                </div>                
            </div>
            
        </div>
        
    </div>
	<button class="btn btn-secondary backbtnproject" data-step="3"><?php echo __('postproject_back','Back');?></button>
	<button class="btn btn-primary nextbtnproject" data-step="3"><?php echo __('postproject_next','Next');?></button>	
</div>