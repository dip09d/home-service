<style>
.incorrect,.incorrect_parent{
    border: 1px solid red;
}
.file-flex-row{
	display: flex;
    align-items: center;
    padding: 10px;
    margin-top: 18px;
    border: 1px solid #ddd;
}

.file-flex-row > div {
	flex-grow: 1;
}
</style>
<?php if($page == 'apply_job'){ ?>
<!-- Modal Header -->
  <div class="modal-header">
	<h4 class="modal-title">Apply </h4>
	<button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>

  <!-- Modal body -->
  <div class="modal-body">
	<form id="applyForm">
		<input type="hidden" name="job_id" value="<?php echo $job_id;?>"/>
		<?php $job_title = getField('title', 'jobs', 'job_id', $job_id); ?>
		<div class="form-field"><b>Apply For : </b> <?php echo $job_title;?></div>
		
		<div class="form-group">
			<label>Name</label>
			<input type="text" class="form-control" name="name"/>
		</div>
		<div class="form-group">
			<label>Email</label>
			<input type="text" class="form-control" name="email"/>
		</div>
		<div class="form-group">
			<label>Phone</label>
			<input type="text" class="form-control" name="phone"/>
		</div>
		<div class="form-group" data-error-wrapper="user_cv">
			<label>Your CV</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile" onchange="upload_file(this)">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
			<div><small>Only, <b>jpg, png, gif, pdf, and doc</b> files are allowed </small></div>
			
			<div id="upload_wrapper"></div>
			
		</div>
	</form>
  </div>

  <!-- Modal footer -->
  <div class="modal-footer">
	<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
	<button class="btn btn-primary" id="apply_btn" form="applyForm">Apply</button>
 </div>

<script type="text/template" id="upload_file_tpl">
<div class="file-flex-row" id="file_upload_component">
	<div class="filename" id="upload_filename">..</div>
	<div class="text-right" id="upload_status">
		<div class="text-danger error" style="display:none;"><small>File not allowed</small></div>
		<div class="progress" style="display:none;">
		  <div class="progress-bar" style="width:0%;"></div>
		</div>
		<a href="javascript:void(0);" style="font-size: 20px; color: grey;display:none;" class="delete_file" onclick="$(this).parent().parent().remove();"><i class="icon-material-outline-delete"></i></a>
		<div id="hidden_input"></div>
	</div>
</div>
</script>

 
<script>
var upload_tpl = $('#upload_file_tpl').html();

function upload_file(ele){
	var component = $(upload_tpl);
	var filename = component.find('.filename');
	var progress = component.find('.progress');
	var delete_btn = component.find('.delete_file');
	var error_ele = component.find('.error');
	var hidden_file_input = component.find('#hidden_input');
	
	var allowed_files = ['application/pdf', 'application/doc', 'image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
	var file = $(ele)[0].files[0];
	
	$('#upload_wrapper').html(component);
	filename.html(file.name);
	
	if(allowed_files.indexOf(file.type) == -1){
		error_ele.html('File not allowed');
		error_ele.show();
		return;
	}
	
	var form_data = new FormData();
	form_data.append('file', file);
	
	var upload_url = '<?php echo base_url('ajax/upload_file');?>';
	
	$.ajax({
	  xhr: function() {
		var xhr = new window.XMLHttpRequest();

		xhr.upload.addEventListener("progress", function(evt) {
		  if (evt.lengthComputable) {
			var percentComplete = evt.loaded / evt.total;
			percentComplete = parseInt(percentComplete * 100);
			progress.find('.progress-bar').css('width', percentComplete+'%');
			
			if (percentComplete === 100) {
				progress.hide();
			}

		  }
		}, false);

		return xhr;
	  },
	  url: upload_url,
	  type: "POST",
	  data: form_data,
	  contentType: false,
	  processData: false,
	  dataType: "json",
	  success: function(res) {
		if(res.status == 1){
			var hidden_input = '<input type="hidden" name="user_cv" value="'+res.file_name+'"/>';
			hidden_file_input.html(hidden_input);
		}else{
			error_ele.html(res.error);
			error_ele.show();
		}
		delete_btn.show();
	  }
	});

	
	
	
}


$('#applyForm').ajaxSubmit({
	validate : false,
	url : {
		submit: '<?php echo base_url('ajax/apply_job')?>',
	},
	submitBtn: '#apply_btn',
	formType: 'apply_job',
	success: function(res){
		if(res.cmd == 'show_msg'){
			$('#applyForm').html(res.cmd_params.show_msg);
			$('#apply_btn').hide();
			/* $('#applyForm')[0].reset(); */
		}
	},
});


</script>
  
<?php } ?>