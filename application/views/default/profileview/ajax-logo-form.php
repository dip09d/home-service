<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal">Cancel</button>
        <h4 class="modal-title">Change logo</h4>
        <button type="button" class="btn btn-success float-end" onclick="SaveLogo(this)">Save</button>
      </div>
    <div class="modal-body" id="logo-modal">
	    <div class="row">
			<div class="col">
				<form action="" method="post" accept-charset="utf-8" id="logoform" class="form-horizontal avatar-form" role="form" name="logoform" onsubmit="return false;"  enctype="multipart/form-data">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>



       			<div class="avatar-body">
                <!-- Upload image and data -->
                

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-9">
                    <div class="avatar-contain"><img id="avatar-contain-image" src="<?php echo theme_url().IMAGE;?>company-logo-03a.png" alt=""></div>
                  </div>
                  <div class="col-md-3">
                    <div class="avatar-preview preview-lg"><img src="<?php echo theme_url().IMAGE;?>company-logo-03a.png" alt=""></div>
                    <div class="avatar-preview preview-md d-none"><img  src="<?php echo theme_url().IMAGE;?>company-logo-03a.png" alt=""></div>
                    <div class="avatar-preview preview-sm d-none"><img  src="<?php echo theme_url().IMAGE;?>company-logo-03a.png" alt=""></div>
                  </div>
                </div>
                
                <div class="avatar-upload">
                
                  <input type="hidden" class="avatar-src" name="avatar_src">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <label for="avatarInput"> Profile Picture</label>
                  
                <div class="uploadButton margin-top-0">
					<input class="uploadButton-input avatar-input" type="file" id="avatarInput" name="avatar_file">
					<label class="uploadButton-button ripple-effect" for="avatarInput">Upload Files</label>
					<span class="uploadButton-file-name">Maximum file size: 2 MB</span>
				</div>
				 <p class="green-text">File must be gif, jpg, png, jpeg.</p>   

                </div>                
              </div>
              
       				
       				
       			</form>
       		</div>
       	</div>
    </div>
