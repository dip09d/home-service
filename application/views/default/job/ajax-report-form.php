<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php D(__('project_view_Cancel','Cancel'));?></button>
        <h4 class="modal-title"><?php D(__('project_view_Report_This_Project','Report This Project'));?></h4>
        <button type="button" class="btn btn-primary float-end" onclick="submitReport()" id="submit_btn"><?php D(__('project_view_Submit_Report','Submit Report'));?></button>
      </div>
    <div class="modal-body">
	    <div class="row">
			<div class="col">
				<form id="report_form">  
				<input type="hidden" name="project_id" value="<?php echo $project_id; ?>"/>
				<input type="hidden" name="cmd" value="<?php echo $cmd; ?>"/>
					<div class="row">
						<div class="col-xl-12">
							<h6><?php D(__('project_view_text_report','Let us know why you would like to report this Project.'));?></h6>
							<div class="submit-field">
								<select class="form-control required_field" name="reason" id="reason">
									<option value=""><?php D(__('project_view_text_Select_Reason','Select Reason'));?></option>
									<option><?php D(__('project_view_text_Non_Original_Content','Non Original Content'));?></option>
									<option><?php D(__('project_view_text_Inappropriate_Content','Inappropriate Content'));?></option>
									<option><?php D(__('project_view_text_Trademark_Violation','Trademark Violation'));?></option>
									<option><?php D(__('project_view_text_Copyrights_Violation','Copyrights Violation'));?></option>
									<option><?php D(__('project_view_text_Fraud','Fraud'));?></option>
									<option><?php D(__('project_view_text_Miscategorized','Miscategorized'));?></option>
									<option><?php D(__('project_view_text_Repetitive_Listing','Repetitive Listing'));?></option>
									<option><?php D(__('project_view_text_Other','Other'));?></option>
								</select>
								<span id="reasonError" class="rerror"></span>
							</div>
							
							<div class="submit-field">
								<h5><?php D(__('project_view_text_Additional_Information','Additional Information'));?></h5>
								<textarea  class="form-control input-text with-border required_field" name="additional_information" id="additional_information"></textarea>
								<span id="additional_informationError" class="rerror"></span>
							</div>
						</div>
					</div>
					
					<div id="error_wrapper"></div>
					
       			</form>
       		</div>
       	</div>
    </div>
	
<script>
function submitReport(){
	var form = $('#report_form');
	var f_data = form.serialize();
	var is_valid = true;
	$('.is-invalid').removeClass('is-invalid');
	$('.required_field').each(function(ind, item){
		if($(item).val().trim()== ''){
			$(item).addClass('is-invalid');
			is_valid =false;
		}else{
			$(item).removeClass('is-invalid');
		}
	});
	
	if(is_valid){
		var submit_btn = $('#submit_btn');
		submit_btn.attr('disabled', 'disabled');
		submit_btn.html(SPINNER);
	
		$.ajax({
			url : '<?php echo get_link('actionreport_job'); ?>',
			data: f_data,
			dataType: 'json',
			type: 'POST',
			success: function(res){
				if(res.status == 0){
					var error_html = res.error_html;
					if(error_html){
						$('#error_wrapper').html(error_html);
					}
				}else{
					$('#myModal').modal('hide');
					bootbox.alert({
						title:'Report Project',
						message: 'Successfully Submitted',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						},
						callback: function () {
							location.reload();
					    }
					});
					
				}
			}
		});
	}
	
}
</script>