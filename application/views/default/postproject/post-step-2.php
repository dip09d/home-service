<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div id="dataStep-2" style="display: none">	
<div class="dashboard-box">
    <!-- Headline -->
    <div class="headline">
        <h4><?php echo __('postproject_description','Description');?>  </h4>
    </div>
    <div class="content with-padding">    
            <div class="submit-field">
                <label class="form-label"><?php echo __('postproject_description_about','Description about project');?></label>
                <textarea rows="4" class="form-control" name="description" id="description"><?php if($projectData){echo $projectData['project_additional']->project_description;}?></textarea>
                <span id="descriptionError" class="rerror"></span>

            </div>
        
            <div class="submit-field">
                <label class="form-label"><?php echo __('postproject_project_attachment','Project attachment');?></label>                                
                <input type="file" name="fileinput" id="fileinput" multiple="true">
                <div class="upload-area" id="uploadfile">
                	
                    <p><?php echo __('postproject_drag','Drag');?> &amp; <?php echo __('postproject_drop_file','drop file here');?> <br /><?php echo __('postproject_or','or');?> <br /> <span class="text-site"><?php echo __('click','click');?></span> <?php echo __('postproject_select_file','to select file');?> </p>
                </div>
                <div id="uploadfile_container">
                <?php if($projectData && $projectData['project_files']){
			           	$inc=0;
			           	foreach($projectData['project_files'] as $files){
			           		$inc++;
			           		$filejson=array(
			           		'file_id'=>$files->file_id,
			           		'file_name'=>$files->server_name,
			           		'original_name'=>$files->original_name,
			           		);
							?>
						<div id="thumbnail_<?php D($inc)?>" data-name="<?php echo $filejson['file_name'];?>" class="thumbnail_sec">
						<input type="hidden" name="projectfileprevious[]" value='<?php D(json_encode($filejson))?>'/>
						<a href="<?php echo UPLOAD_HTTP_PATH.'projects-files/projects-requirement/'.$files->server_name;?>" target="_blank"><?php echo $files->original_name;?></a>
						<a href="javascript:void(0)" class="text-danger ripple-effect ico float-end" onclick="$(this).parent().remove()">
						<i class="icon-feather-trash"></i>
						</a>
						</div>
							
					<?php 
						}
					}
					?>
                </div>
            </div>        
    </div>    
</div>
<button class="btn btn-secondary backbtnproject" data-step="2"><?php echo __('postproject_back','Back');?></button>
        <button class="btn btn-primary nextbtnproject" data-step="2"><?php echo __('postproject_next','Next');?></button>
</div>
