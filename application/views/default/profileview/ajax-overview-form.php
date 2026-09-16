<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('profileview_overview','Overview');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveOverview(this)"><?php echo __('profileview_save','Save');?></button>
	</div>
    <div class="modal-body">
	    <form action="" method="post" accept-charset="utf-8" id="overviewform" class="form-horizontal" role="form" name="overviewform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
					<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<p><?php echo __('profileview_overview_use_this','Use this space to show clients you have the skills and experience they are looking for.');?></p>
								<ul>
									<li><?php echo __('profileview_overview_describe','Describe your strengths and skills');?></li>
									<li><?php echo __('profileview_overview_highlight','Highlight projects, accomplishments and education');?></li>
									<li><?php echo __('profileview_overview_keep','Keep it short and make sure it is error-free');?></li>
								</ul>
								<textarea  class="form-control input-text with-border" name="overview" id="overview" placeholder="I can ensure the quality of software while leveraging my coding abilities to build and utilize complex testing tools. I am proficient in all stages of the QA process from test planning to post-release verification. Throughout my QA career, I have worked in many different environments, following methodologies from Agile SCRUM to Waterfall."><?php D($memberInfo->member_overview)?></textarea>
								<span id="overviewError" class="rerror"></span>
							</div>
						</div>
       				</div>
       			</form>
    </div>