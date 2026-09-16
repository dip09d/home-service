<style>
.image-wrapper{
	position: relative;
    width: 210px;
}
.image-wrapper button.close{
	position: absolute;
	top: 0px;
	right: 0px;
	padding: 5px;
	background-color: black;
	color: white;
	z-index: 111;
	opacity: .8;
}
</style>

<div class="form-group">
  <label for="display_order">Image </label>
  <input type="file" onchange="upload_file(this)"/>
  <br/>
  <div id="upload">
		<div id="upload_progress" style="display:none;">
		 <div class="progress progress-xxs">
			<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
			</div>
		  </div>
		</div>
		<div id="upload_result"></div>
  </div>
</div>

<template id="image_upload_template">
<div class="image-wrapper" id="{IMAGE_ID}">
	<button type="button" class="close" onclick="removeByID('{IMAGE_ID}')"><span aria-hidden="true">&times;</span></button>
	<img src="{IMAGE_SRC}" class="img-rounded" alt="" width="210">
	<input type="hidden" name="<?php echo $input_name; ?>" value="{FILENAME}"/>
</div>
</template>

<script>
function upload_file(ele){
	var $upload = $('#upload');
	var $upload_progress = $upload.find('#upload_progress');
	var $progress_bar = $upload_progress.find('.progress-bar');
	var $upload_result = $upload.find('#upload_result');
	
	var allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
	var url = '<?php echo !empty($url) ? $url :  base_url('category/upload_file'); ?>';
	
	var file = $(ele)[0].files[0];
	if(allowed_types.indexOf(file.type) === -1){
		$upload_result.html('<div class="text-red">File not allowed </div>');
		return;
	}else{
		$upload_result.html('');
	}
	
	var f_data = new FormData();
	f_data.append('file', file);
	
	
	$upload_progress.show();
	$.ajax({
	  xhr: function() {
		var xhr = new window.XMLHttpRequest();

		xhr.upload.addEventListener("progress", function(evt) {
		  if (evt.lengthComputable) {
			var percentComplete = evt.loaded / evt.total;
			percentComplete = parseInt(percentComplete * 100);
			$progress_bar.css('width', percentComplete+'%');

			if (percentComplete === 100) {
				$progress_bar.css({width: '0%'});
				$upload_progress.hide();
			}

		  }
		}, false);

		return xhr;
	  },
	  url: url,
	  type: "POST",
	  data: f_data,
	  contentType:false,
	  processData: false,
	  dataType: "json",
	  success: function(res) {
		if(res.err_length === 0){
			var html = null;
			var  filename = res.data.upload_data.file_name;
			var is_image  = res.data.upload_data.is_image;
			var orig_name  = res.data.upload_data.orig_name;
			var wrapper_id = 'file_wrapper_'+Date.now();
			
			if(is_image){
				html = $('#image_upload_template').html();
				html = html.replace(/{IMAGE_ID}/g, wrapper_id);
				html = html.replace(/{IMAGE_SRC}/g, res.data.file_url);
				html = html.replace(/{FILENAME}/g, filename);
			}else{
				html = '<div id="'+wrapper_id+'"> '+orig_name+' <a href="#" onclick="removeByID(\''+wrapper_id+'\')"><i class="icon-feather-trash"></i></a><input type="hidden" name="<?php echo $input_name; ?>" value="'+filename+'"/> </div>';
			}
			
			$upload_result.html(html);
			
		}else{
			$upload_result.html(res.error.upload_error);
		}
	  }
	});
}

function removeByID(id){
	$('#'+id).remove();
}

</script>

