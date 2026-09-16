/* File upload Module */

/*
	DOCUMENTATION
	
	DEVELOPED BY VENKATESH BISHU
	
	Methods : 
	setPath : Set the path where you want to upload the file
		param1 : path (sting)
		ex. setPath(FILE UPLOAD URL)
	
	
	beforeUpload: A function to run before the actual request is send to server
	onProgress : A function to run when the file uploading is in progress
	onFail : A function to run when the request is failed
	validate : A function to run to validate the file type
	upload : A function to finally upload the file to server
*/

(function($, window ) {

  'use strict';
	
 function FileUpload(){
	var self = this;
	var f = {};
	f.path = VPATH + 'image/upload_file';
	
	f.setPath = function(p){
		f.path = p;
	};
	
	f.beforeUpload = function(f){
		
	}
	
	f.validate = function(file, type){
		var images = ["image/jpeg","image/jpg","image/png"];
		var options = [];
		if(type == 'image'){
			options = images;
		}
		if(options.indexOf(file.type) < 0){
			return false;
		}else{
			return true;
		}
	}
	
	f.onSuccess = function(d){
		
	}
	
	f.onProgress = function(d){
		
	}
	
	f.onFail = function(f){
		$.alert({
		theme: 'material',
		type: 'red',
		title: 'Error',
		content: 'Something went wrong',
		columnClass: 'col l6 s12 offset-l3'
		});
	}
	
	f.upload = function(file){
		var fdata = new FormData();
		fdata.append('file', file);
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();

				xhr.upload.addEventListener("progress", function(evt) {
				  if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					percentComplete = parseInt(percentComplete * 100);
					
					f.onProgress != undefined ? f.onProgress(percentComplete) : '';
				
				  }
				  
				}, false);

				return xhr;
			},
			url: f.path,
			type: 'POST',
			dataType: 'json',
			data: fdata,
			beforeSend: f.beforeUpload,
			processData: false,
			contentType: false,
			success: function(data){
				f.onSuccess(data);
			}
			
		}).fail(function(){
			f.onFail();
		});
	};
	
	return f;
	
}

window.FileUpload = FileUpload;
})(jQuery,  window );
