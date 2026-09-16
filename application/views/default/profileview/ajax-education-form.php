<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <?php if($dataid){?>
        <h4 class="modal-title"><?php echo __('profileview_education_change','Change education');?></h4>
        <?php }else{?>
        <h4 class="modal-title"><?php echo __('profileview_education_add','Add education');?></h4>
        <?php }?>
        <button type="button" class="btn btn-success float-end" onclick="SaveEducation(this)"><?php echo __('profileview_save','Save');?></button>
      </div>
    <div class="modal-body">
	    <div class="row">
			<div class="col">
				<form action="" method="post" accept-charset="utf-8" id="educationform" class="form-horizontal" role="form" name="educationform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
				<input  type="hidden" value="<?php echo $dataid;?>" id="dataid" name="dataid"/>
       			
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_education_school','School');?></h5>
								<input type="text" class="form-control input-text with-border" value="<?php if($memberInfo){D($memberInfo->education_school);}?>" name="school" id="school">
								<span id="schoolError" class="rerror"></span>
							</div>
						</div>
       				</div>
       				<div class="row">
						<div class="col-xl-6">
							<div class="submit-field remove_arrow_select">
								<h5><?php echo __('profileview_education_date_attend','Dates Attended');?></h5>
								<select name="from_year" id="from_year" data-size="4" class="selectpicker browser-default" title="From" data-live-search="true">
			            		<?php
								for($i=date('Y');$i>=1940;$i--){
									?>
									<option value="<?php D($i);?>" <?php if($memberInfo && $i==$memberInfo->education_from_year){echo 'selected';}?>><?php D($i);?></option>
									<?php
								}
			            		 ?>
			            		</select>
							</div>
							<span id="from_yearError" class="rerror"></span>
						</div>
						<div class="col-xl-6">
							<div class="submit-field remove_arrow_select">
		            		<h5>&nbsp;</h5>  
			            	<select name="end_year" id="end_year" data-size="4" class="selectpicker browser-default" title="To" data-live-search="true">
			            		<?php
								for($i=date('Y')+7;$i>=1940;$i--){
									?>
									<option value="<?php D($i);?>" <?php if($memberInfo && $i==$memberInfo->education_end_year){echo 'selected';}?>><?php D($i);?></option>
									<?php
								}
			            		 ?>
			            	</select>          	
			        	</div>
			        	<span id="end_yearError" class="rerror"></span>
						</div>
       				</div>
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_education_degree','Degree (Optional)');?></h5>
								<input type="text" class="form-control input-text with-border" value="<?php if($memberInfo){D($memberInfo->education_degree);}?>" name="degree" id="degree">
								<span id="degreeError" class="rerror"></span>
							</div>
						</div>
       				</div>
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_education_area_study','Area of Study (Optional)');?></h5>
								<input type="text" class="form-control input-text with-border" value="<?php if($memberInfo){D($memberInfo->education_area_of_study);}?>" name="area_of_study" id="area_of_study">
								<span id="area_of_studyError" class="rerror"></span>
							</div>
						</div>
       				</div>
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_education_description','Description (Optional)');?></h5>
								<textarea  class="form-control input-text with-border" name="description" id="description"><?php if($memberInfo){D($memberInfo->education_description);}?></textarea>
								<span id="descriptionError" class="rerror"></span>
							</div>
						</div>
       				</div>
       				
       				
       					
       			</form>
       		</div>
       	</div>
    </div>