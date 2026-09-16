<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div id="dataStep-3"  style="display: none">
		
    <div class="dashboard-box margin-top-0">
        <!-- Headline -->
        <div class="headline">
            <h3> Details </h3>
        </div>
        <div class="content with-padding">
            <div class="submit-field myradio mb-0">
                <label>What type of project you have ?</label>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <?php if($all_projectType){
                    foreach($all_projectType as $key=>$keydata){
                    ?>
                    <label class="btn">
                        <input type="radio" name="projectType" id="defaultInline<?php D($key);?>" autocomplete="off"  value="<?php D($key);?>">
                        <i class="icon-material-<?php D($key);?>"></i><br>
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
            <h3> Screen Question (optional) </h3>
        </div>
        <div class="content with-padding">
            <div class="submit-field mb-0">
                <label>Add screen question / require a cover letter </label>
                <div id="addQuestion_container"></div>
                <span id="questionError" class="rerror"></span>
                <a href="javascript:void(0)" class="btn btn-outline-success" id="addQuestion">+ Add </a>    
            </div>

            <div class="mt-3" id="is_cover_required_display" style="display: none">
                <div class="checkbox">
                    <input type="checkbox" name="is_cover_required" id="is_cover_required" value="1" checked>
                    <label for="is_cover_required"><span class="checkbox-icon"></span> Required cover letter</label>
                </div>
            </div>
            
        </div>
        <div class="dashboard-box-footer">
            <button class="btn btn-secondary backbtnproject" data-step="3">Back</button>
            <button class="btn btn-site nextbtnproject" data-step="3">Next</button>
        </div>
    </div>
				
</div>