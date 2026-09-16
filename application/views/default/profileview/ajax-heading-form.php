<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('profileview_heading_edit_title','Edit your title');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveHeading(this)"><?php echo __('profileview_save','Save');?></button>
      </div>
    <div class="modal-body">
	    <div class="row">
			<div class="col">
				<form action="" method="post" accept-charset="utf-8" id="headingform" class="form-horizontal" role="form" name="headingform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
					<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_heading_your_title','Your title');?></h5>
								<p><?php echo __('profileview_heading_professional_skill','Enter a single sentence description of your professional skills/experience');?></p>
								<input type="text" class="form-control input-text with-border" value="<?php D($memberInfo->member_heading)?>" name="heading" id="heading" placeholder="EXAMPLE: Software Quality Assurance Analyst">
								<span id="headingError" class="rerror"></span>
							</div>
						</div>
       				</div>
       			</form>
       		</div>
       	</div>
    </div>