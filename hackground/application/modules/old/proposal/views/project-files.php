<style>
.upload-area {
    width: 100%;
    max-height: 100px;
    border: 2px dashed #40de00;
    border-radius: 4px;
    text-align: center;
    overflow: auto;
    padding: 1rem;
    margin-bottom: 0.25rem;
	cursor: pointer;
}
.upload-area h4 {
    color: #999;
    font-weight: 500;
    font-size: 1rem;
    line-height: 20px;
    margin-bottom: 0;
}
#fileinput {
    display: none;
}
</style>
<script src="<?php echo JS;?>upload-drag-file.js" type="text/javascript"></script>
<div class="submit-field">
	<h5>Project attachment</h5>                
	<input type="file" name="fileinput" id="fileinput" multiple="true">
	<div class="upload-area" id="uploadfile">
		<h4>Drag &amp; drop file here or <span class="text-site">click</span> to select file</h4>
	</div>
</div>


<ul class="list-group" id="uploadfile_container">
<?php 
foreach($detail['files'] as $k => $file){ ?>
<li class="list-group-item">
<a href="<?php echo $file['file_url']; ?>" target="_blank" class="text-black"><?php echo $file['original_name']; ?></a> &nbsp; 
<a href="javascript:void(0)" onclick="remove_project_files('<?php echo $file['file_id']; ?>', '<?php echo $detail['project_id'];?>')" title="Remove" class="btn btn-sm btn-outline-danger float-right"><i class="icon-feather-trash"></i></a>
</li>

<?php } ?>
</ul>



<script>
var SPINNER='<svg class="MYspinner" width="30px" height="30px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg>';
function remove_project_files(file_id, project_id){
	var c = confirm('Are you sure to delete this file ?');
	if(c){
		var data = {
			project_id: project_id,
			file_id: file_id,
		};
		$.post('<?php echo base_url('proposal/delete_project_file')?>', data, function(res){
			location.reload(); 
		});
	}
}

function uploadData(formdata){
	formdata.append('project_id', '<?php echo $detail['project_id'];?>');
	var len = $("#uploadfile_container li.thumbnail_sec").length;
	var num = Number(len);
	num = num + 1;	
	$("#uploadfile_container").append('<li class="list-group-item thumbnail_sec" id="thumbnail_'+num+'">'+SPINNER+'</li>');
	$.ajax({
		url: "<?php echo base_url('proposal/uploadattachment_direct')?>",
		type: 'post',
		data: formdata,
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function(response){
		   if(response.status=='OK'){
				var name = response.upload_response.original_name;
				/* $("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="<?php echo TMP_UPLOAD_HTTP_PATH; ?>'+response.upload_response.file_name+'" target="_blank" class="text-black">'+name+'</a> &nbsp; <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger float-right" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>'); */
				$("#thumbnail_"+num).html('<a href="<?php echo UPLOAD_HTTP_PATH; ?>'+response.upload_response.file_name+'" target="_blank" class="text-black">'+name+'</a> &nbsp; <a href="javascript:void(0)" class="btn btn-sm btn-outline-danger float-right" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
		   }else{
				$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');
		   }
		   
		},
		
	}).fail(function(){
		$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
	});
}
</script>