<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('projectclient_hire_cancel','Cancel');?></button>
        <h4 class="modal-title"><?php echo __('projectclient_invite_user','Invite user');?></h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveInvite(this)"><?php echo __('projectclient_invite','Invite');?></button>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col">
			<form action="" method="post" accept-charset="utf-8" id="inviteform" class="form-horizontal" role="form" name="inviteform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $req_type;?>_freelancer" id="formtype" name="formtype"/>
				<input  type="hidden" value="<?php echo $fid;?>" id="fid" name="fid"/>
				<div class="row">
					<div class="col-xl-12">
						<div class="content with-padding">
							<div class="submit-field remove_arrow_select">
							<label><?php echo __('projectclient_invite_select_prjct','Select Project');?></label>
								<select name="project_id" id="project_id" data-size="4" class="selectpicker browser-default" data-live-search="true">
								<?php
								if($all_projects){
									foreach($all_projects as $k=>$project){
										?>
										<option value="<?php D($project->project_id);?>" ><?php D(ucfirst($project->project_title));?></option>
										<?php
									}
								}
								?>
								</select>
								<span id="project_idError" class="rerror"></span>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>

function SaveInvite(ev){
		var buttonsection=$(ev);
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled','disabled');
		var formID="inviteform";
		$.ajax({
	        type: "POST",
	        url: "<?php D(get_link('myProjectBidsClientStatusAjaxURL'))?>",
	        data:$('#'+formID).serialize(),
	        dataType: "json",
	        cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					$('#myModal').modal('hide');
					bootbox.alert({
						title:'Invite freelancer',
						message: 'Invitation sent successfully',
						buttons: {
						'ok': {
							label: 'Ok',
							className: 'btn-primary float-end'
							}
						}
					});
				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID,msg['errors']);
				}
			}
		})
}

</script>