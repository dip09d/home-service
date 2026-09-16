<?php
defined('BASEPATH') or exit('No direct script access allowed');
//get_print($last_application,FALSE);
?>
<!-- Dashboard Container -->
<div class="dashboard-container">
	<?php echo $left_panel; ?>
	<!-- Dashboard Content -->
	<div class="dashboard-content-container">
		<div class="dashboard-content-inner">
			<?php if (!$is_email_verified) { ?>
				<div class="mx-auto alert alert-warning text-center">
					<p class="mb-0"> <i class="icon-material-outline-check-circle text-danger"></i><?php echo __('dashboard_email_verify_first','Your email is not verified. Please verify first to process document verification'); ?> .<a href="<?php D(VZ); ?>" class="btn btn-primary btn-sm resendEmail"><?php echo __('dashboard_freelancer_resend_email','Resend Email'); ?></a></p>
				</div>
			<?php } elseif ($is_doc_verified) { ?>
				<div class="mx-auto alert alert-success text-center">
					<p class="mb-0"> <i class="icon-material-outline-check-circle text-danger"></i><?php echo __('dashboard_document_verification_seccess','Your document verification successfull'); ?> .</p>
				</div>
				<?php
			} else {
				$show_form = 1;
				if ($last_application) {
					if ($last_application->document_status == 0) {
						$show_form = 0;
				?>
						<div class="mx-auto alert alert-primary text-center">
							<p class="mb-0"> <i class="icon-material-outline-check-circle text-primary"></i> <?php echo __('dashboard_document_verification_under_process','Your document verification under process'); ?> .</p>
						</div>
					<?php
					} elseif ($last_application->document_status == 2) {
					?>
						<div class="mx-auto alert alert-danger text-center">
							<p class="mb-0"> <i class="icon-material-outline-check-circle text-danger"></i> <?php echo __('dashboard_document_last_applictaion_reject','Your last application got rejected. Please check reason and submit again'); ?> .</p>
							<p class="mb-0"> <strong><?php echo __('dashboard_document_reason','Reason'); ?>:</strong> <?php echo $last_application->reject_reason; ?></p>
						</div>
				<?php
					}
				}
				?>
				<?php if ($show_form) { ?>

					<h1 class="display-5 mb-4"><?php echo __('dashboard_document_application_verification','Application for document verification'); ?></h1>
					<form action="" method="post" accept-charset="utf-8" id="documentform" class="form-horizontal" role="form" name="documentform" onsubmit="saveDocument(this);return false;">
						<div class="panel mb-4">
							<div class="panel-header">
								<h4><i class="icon-material-outline-account-circle"></i> <?php echo __('dashboard_document_id_proff','ID Proff'); ?>  </h4>
							</div>
							<div class="panel-body">
								<div class="submit-field">
									<label class="form-label"><?php echo __('dashboard_document_document_name','Document Name'); ?></label>
									<input type="text" class="form-control input-text with-border" name="id_proof_name" id="id_proof_name">
									<span id="id_proof_nameError" class="rerror"></span>

								</div>
								<div class="submit-field mb-0">
									<label class="form-label"><?php echo __('dashboard_document_attachment','Attachment'); ?></label>
									<input type="file" id="upload1" class="d-none" accept="image/*, application/pdf">
									<div class="upload-area" id="uploadfile1">
										<p><?php echo __('dashboard_document_drag','Drag'); ?> &nbsp;<?php echo __('dashboard_document_drop_file','drop file here or'); ?>  <span class="text-site"><?php echo __('dashboard_document_click','click'); ?></span> <?php echo __('dashboard_document_to_select_file','to select file'); ?> </p>
									</div>
									<p class="text-muted"><small><?php echo __('dashboard_document_only_image_pdf','Note: Only image and pdf allowed'); ?></small></p>
									<div id="uploadfile_container_uploadfile1"></div>
									<span id="projectfile_uploadfile1Error" class="rerror"></span>
								</div>
							</div>
						</div>

						<div class="panel mb-4">
							<div class="panel-header">
								<h4><i class="icon-feather-map-pin"></i> <?php echo __('dashboard_document_address_proff','Address Proff'); ?>  </h4>
							</div>
							<div class="panel-body">
								<div class="submit-field">
									<label class="form-label"><?php echo __('dashboard_document_document_name','Document Name'); ?></label>
									<input type="text" class="form-control input-text with-border" name="address_proof_name" id="address_proof_name">
									<span id="address_proof_nameError" class="rerror"></span>

								</div>
								<div class="submit-field">
									<label class="form-label"><?php echo __('dashboard_document_attachment','Attachment'); ?></label>
									<div class="">
										<input type="file" id="upload2" class="d-none" accept="image/*, application/pdf, application/doc, application/docx, application/docs">
										<div class="upload-area" id="uploadfile2">
											<p><?php echo __('dashboard_document_drag','Drag'); ?> &nbsp;<?php echo __('dashboard_document_drop_file','drop file here or'); ?>  <span class="text-site"><?php echo __('dashboard_document_click','click'); ?></span><?php echo __('dashboard_document_to_select_file','to select file'); ?> </p>
										</div>
										<p class="text-muted"><small><?php echo __('dashboard_document_only_image_pdf','Note: Only image and pdf allowed'); ?></small></p>
										<div id="uploadfile_container_uploadfile2"></div>
									</div>
									<span id="projectfile_uploadfile2Error" class="rerror"></span>
								</div>
							</div>
						</div>
						<button class="btn btn-primary saveBTN"><?php echo __('dashboard_document_submit','Submit'); ?></button>&nbsp;
						<a href="<?php echo URL::get_link('dashboardURL'); ?>" class="btn btn-secondary"><?php echo __('dashboard_document_cancel','Cancel'); ?></a>
					</form>
				<?php } ?>






			<?php } ?>

		</div>
	</div>
</div>
<!-- Dashboard Container / End -->
<script type="text/javascript">
	var SPINNER = '<?php load_view('inc/spinner', array('size' => 30)); ?>';

	function saveDocument(ev) {
		var formID = "documentform";
		var buttonsection = $('#' + formID).find('.saveBTN');
		var buttonval = buttonsection.html();
		buttonsection.html(SPINNER).attr('disabled', 'disabled');

		$.ajax({
			type: "POST",
			url: "<?php D(get_link('SaveDocumentAJAXURL')) ?>",
			data: $('#' + formID).serialize(),
			dataType: "json",
			cache: false,
			success: function(msg) {
				buttonsection.html(buttonval).removeAttr('disabled');
				clearErrors();
				if (msg['status'] == 'OK') {
					bootbox.alert({
						title: 'Document Verification Request',
						message: '<?php D(__('verify_document_success_message', 'Your application send to admin. Admin will update you shortly.')); ?>',
						buttons: {
							'ok': {
								label: 'Ok',
								className: 'btn-primary float-end'
							}
						},
						callback: function() {
							window.location.reload();
						}
					});

				} else if (msg['status'] == 'FAIL') {
					registerFormPostResponse(formID, msg['errors']);
				}
			}
		})
	}

	function uploadData(formdata, e) {
		var section = e.currentTarget.id;
		uploadDataFile(formdata, section);
	}

	function uploadDataFile(formdata, section) {
		console.log(section);
		var u_key = new Date().getTime();
		$("#uploadfile_container_" + section).html('<div id="thumbnail_' + u_key + '" class="thumbnail_sec">' + SPINNER + '</div>');
		$.ajax({
			url: "<?php D(get_link('uploadFileFormCheckAJAXURL')) ?>?from=verifydocument",
			type: 'post',
			data: formdata,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(response) {
				if (response.status == 'OK') {
					var name = response.upload_response.original_name;
					$("#thumbnail_" + u_key).html('<input type="hidden" name="projectfile_' + section + '" value=\'' + JSON.stringify(response.upload_response) + '\'/> ' + name + '<a href="<?php D(VZ); ?>" class=" text-danger ripple-effect ico float-end" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
				} else {
					$("#thumbnail_" + u_key).html('<p class="text-danger">Error in upload file</p>');
				}

			},

		}).fail(function() {
			$("#thumbnail_" + u_key).html('<p class="text-danger">Error occurred</p>');
		});
	}
	var main = function() {
		$('.resendEmail').click(function() {
			var buttonsection = $(this);
			var buttonval = buttonsection.html();
			buttonsection.html(SPINNER).attr('disabled', 'disabled');
			$.ajax({
				type: "POST",
				url: "<?php D(get_link('resendEmailURLAJAX')) ?>",
				dataType: "json",
				cache: false,
				success: function(msg) {
					buttonsection.html(buttonval).removeAttr('disabled');
					clearErrors();
					if (msg['status'] == 'OK') {
						bootbox.alert({
							title: 'Verify Email',
							message: '<?php D(__('resendemail_success_message', 'An email has been sent to your email address with instructions on how to veirfy your email.')); ?>',
							buttons: {
								'ok': {
									label: 'Ok',
									className: 'btn-primary float-end'
								}
							},
							callback: function() {

							}
						});

					} else if (msg['status'] == 'FAIL') {
						bootbox.alert({
							title: 'Verify Email',
							message: '<?php D(__('resendemail_error_message', "Opps! . Please try again.")); ?>',
							buttons: {
								'ok': {
									label: 'Ok',
									className: 'btn-primary float-end'
								}
							}
						});
					}
				}
			})
		});
		$("#uploadfile1").click(function() {
			$("#upload1").click();
		});
		$("#uploadfile2").click(function() {
			$("#upload2").click();
		});
		$("#upload1").change(function() {
			var fd = new FormData();
			var all_files = $('#upload1')[0].files;
			for (var i = 0; i < all_files.length; i++) {
				var files = $('#upload1')[0].files[i];
				fd.append('fileinput', files);
				uploadDataFile(fd, 'uploadfile1');
			}
		});
		$("#upload2").change(function() {
			var fd = new FormData();
			var all_files = $('#upload2')[0].files;
			for (var i = 0; i < all_files.length; i++) {
				var files = $('#upload2')[0].files[i];
				fd.append('fileinput', files);
				uploadDataFile(fd, 'uploadfile2');
			}
		});
	}
</script>