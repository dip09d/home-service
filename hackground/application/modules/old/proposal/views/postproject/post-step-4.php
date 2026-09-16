<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div id="dataStep-4"  style="display: none">
		
    <div class="dashboard-box margin-top-0">
        <!-- Headline -->
        <div class="headline">
            <h3> Expertise </h3>
        </div>
        <div class="content with-padding">    
            <div class="submit-field mb-0">
                <label>Select required skills</label>
                <div class="keywords-list skillContaintag"></div>
			   <input  class="form-control tagsinput_skill" name="skills" id="skills" value="">
                <span id="skillsError" class="rerror"></span>

            </div>
    
        </div>
        <div class="dashboard-box-footer">
            <button class="btn btn-secondary backbtnproject" data-step="4">Back</button>
            <button class="btn btn-site nextbtnproject" data-step="4">Next</button>
        </div>
    </div>
				
</div>