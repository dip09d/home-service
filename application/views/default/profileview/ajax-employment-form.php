<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <?php if($dataid){?>
        <h4 class="modal-title"><?php echo __('profileview_employment_change','Change employment')?></h4>
        <?php }else{?>
        <h4 class="modal-title"><?php echo __('profileview_employment_add','Add employment')?></h4>
        <?php }?>
        <button type="button" class="btn btn-success float-end" onclick="SaveEmployment(this)"><?php echo __('profileview_save','Save');?></button>
      </div>
    <div class="modal-body">
	    <div class="row">
			<div class="col">
				<form action="" method="post" accept-charset="utf-8" id="employmentform" class="form-horizontal" role="form" name="employmentform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
				<input  type="hidden" value="<?php echo $dataid;?>" id="dataid" name="dataid"/>
       			
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_employment_company','Company');?></h5>
								<input type="text" class="form-control input-text with-border" value="<?php if($memberInfo){D($memberInfo->employment_company);}?>" name="company" id="company">
								<span id="companyError" class="rerror"></span>
							</div>
						</div>
       				</div>
       				<div class="row">
						<div class="col-xl-6">
							<div class="submit-field">
								<h5><?php echo __('profileview_employment_location','Location');?></h5>
								<input type="text" class="form-control input-text with-border" value="<?php if($memberInfo){D($memberInfo->employment_city);}?>" name="city" id="city">
								<span id="cityError" class="rerror"></span>
							</div>
						</div>
						<div class="col-xl-6">
							<div class="submit-field remove_arrow_select">
		            		<h5>&nbsp;</h5>  
			            	<select name="country" id="country" data-size="4" class="selectpicker browser-default" title="Select country" data-live-search="true">
			            		<?php
			            		if($country){
									foreach($country as $country_list){
										?>
										<option value="<?php D($country_list->country_code);?>" <?php if($memberInfo && $country_list->country_code==$memberInfo->employment_country_code){echo 'selected';}?>><?php D(ucfirst($country_list->country_name));?></option>
										<?php
									}
								}
			            		 ?>
			            	</select>          	
			        	</div>
			        	<span id="countryError" class="rerror"></span>
						</div>
       				</div>
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_employment_title','Title');?></h5>
								<input type="text" class="form-control input-text with-border" value="<?php if($memberInfo){D($memberInfo->employment_title);}?>" name="title" id="title">
								<span id="titleError" class="rerror"></span>
							</div>
						</div>
       				</div>
       				
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field remove_arrow_select">
								<h5><?php echo __('profileview_employment_role','Role');?></h5>
								<select name="role" id="role" data-size="4" class="selectpicker browser-default" title="Select role" data-live-search="true">
			            		<?php
			            		if($role){
									foreach($role as $key=>$role_name){
										?>
										<option value="<?php D($key);?>" <?php if($memberInfo && $key==$memberInfo->employment_role){echo 'selected';}?>><?php D(ucfirst($role_name));?></option>
										<?php
									}
								}
			            		 ?>
			            		</select>
								
							</div>
							<span id="roleError" class="rerror"></span>
						</div>
       				</div>
       				<div class="row">
						<div class="col-xl-6">
							<div class="submit-field remove_arrow_select">
								<h5><?php echo __('profileview_employment_period_from','Period from');?></h5>
								<select name="frommonth" id="frommonth" data-size="4" class="selectpicker browser-default" title="Select month" data-live-search="true">
			            		<?php
			            		if($month){
									foreach($month as $key=>$month_name){
										?>
										<option value="<?php D($key);?>" <?php if($memberInfo && $key==date('m',strtotime($memberInfo->employment_from))){echo 'selected';}?>><?php D(ucfirst($month_name));?></option>
										<?php
									}
								}
			            		 ?>
			            		</select>
							</div>
							<span id="frommonthError" class="rerror"></span>
						</div>
						<div class="col-xl-6">
							<div class="submit-field remove_arrow_select">
		            		<h5>&nbsp;</h5>  
			            	<select name="fromyear" id="fromyear" data-size="4" class="selectpicker browser-default" title="Select year" data-live-search="true">
			            		<?php
								for($i=date('Y');$i>=1940;$i--){
									?>
									<option value="<?php D($i);?>" <?php if($memberInfo && $i==date('Y',strtotime($memberInfo->employment_from))){echo 'selected';}?>><?php D($i);?></option>
									<?php
								}
			            		 ?>
			            	</select>          	
			        	</div>
			        	<span id="fromyearError" class="rerror"></span>
						</div>
       				</div>

       				<div class="row" id="is_working_now" <?php if($memberInfo && $memberInfo->employment_is_working_on==1){echo 'style="display:none"';}?>>
						<div class="col-xl-6">
							<div class="submit-field remove_arrow_select">
								<h5><?php echo __('profileview_employment_period_to','Period to');?></h5>
								<select name="tomonth" id="tomonth" data-size="4" class="selectpicker browser-default" title="Select month" data-live-search="true">
			            		<?php
			            		if($month){
									foreach($month as $key=>$month_name){
										?>
										<option value="<?php D($key);?>" <?php if($memberInfo && $memberInfo->employment_is_working_on!=1 && $key==date('m',strtotime($memberInfo->employment_to))){echo 'selected';}?>><?php D(ucfirst($month_name));?></option>
										<?php
									}
								}
			            		 ?>
			            		</select>
							</div>
							<span id="tomonthError" class="rerror"></span>
						</div>
						<div class="col-xl-6">
							<div class="submit-field remove_arrow_select">
		            		<h5>&nbsp;</h5>
			            	<select name="toyear" id="toyear" data-size="4" class="selectpicker browser-default" title="Select year" data-live-search="true">
			            		<?php
								for($i=date('Y');$i>=1940;$i--){
									?>
									<option value="<?php D($i);?>" <?php if($memberInfo && $memberInfo->employment_is_working_on!=1 && $i==date('Y',strtotime($memberInfo->employment_to))){echo 'selected';}?>><?php D($i);?></option>
									<?php
								}
			            		 ?>
			            	</select>          	
			        	</div>
			        	<span id="toyearError" class="rerror"></span>
						</div>
       				</div>
       				<div class="row">
	       				<div class="col-xl-12">
		       				<div class="submit-field">
								<div class="checkbox">
									<input type="checkbox" name="employment_is_working_on" id="employment_is_working_on" value="1" <?php if($memberInfo && $memberInfo->employment_is_working_on==1){echo 'checked';}?>  onchange="$('#is_working_now').toggle();">
									<label for="employment_is_working_on"><span class="checkbox-icon"></span><?php echo __('profileview_employment_work_here','I currently work here');?> </label>
								</div>
							</div>
						</div>
       				</div>
       				
       				<div class="row">
						<div class="col-xl-12">
							<div class="submit-field">
								<h5><?php echo __('profileview_education_description','Description (Optional)');?></h5>
								<textarea  class="form-control input-text with-border" name="description" id="description"><?php if($memberInfo){D($memberInfo->employment_description);}?></textarea>
								<span id="descriptionError" class="rerror"></span>
							</div>
						</div>
       				</div>
       				
       				
       					
       			</form>
       		</div>
       	</div>
    </div>