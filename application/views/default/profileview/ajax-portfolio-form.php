<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <?php if($dataid){?>
        <h4 class="modal-title"><?php echo __('profileview_portfolia_change','Change portfolio');?></h4>
        <?php }else{?>
        <h4 class="modal-title"><?php echo __('profileview_portfolia_add','Add portfolio');?></h4>
        <?php }?>
        <?php if($limit_over==0){?>
        <button type="button" class="btn btn-success float-end" onclick="SavePortfolio(this)"><?php echo __('profileview_save','Save');?></button>
      <?php }?>
      </div>
    <div class="modal-body">
    <?php if($limit_over){?>
        <div class="alert alert-warning"><?php echo __('profileview_portfolia_limit_over','Max limit over, please upgrade your membership plan.');?> <a href="<?php echo get_link('membershipURL');?>"><?php echo __('profileview_portfolia_click_here','Click here');?></a> <?php echo __('profileview_portfolia_to_upgrade','to upgrade');?></div>
    <?php }else{?>
	    <form action="" method="post" accept-charset="utf-8" id="portfolioform" class="form-horizontal" role="form" name="portfolioform" onsubmit="return false;">  
				<input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
				<input  type="hidden" value="<?php echo $dataid;?>" id="dataid" name="dataid"/>
       			<div class="row">
       			<div class="col-lg-6 col-12">
                	<div class="submit-field">
                        <h5><?php echo __('profileview_portfolia_project_title','Project Title');?></h5>
                        <input type="text" class="form-control" value="<?php if($memberInfo){D($memberInfo->portfolio_title);}?>" name="title" id="title">
                        <span id="titleError" class="rerror"></span>
                    </div>
                    <div class="submit-field">
                        <h5><?php echo __('profileview_portfolia_project_url','Project URL (Optional)');?></h5>
                        <input type="text" class="form-control" value="<?php if($memberInfo){D($memberInfo->portfolio_url);}?>" name="url" id="url">
                        <span id="urlError" class="rerror"></span>
                    </div>
       				<div class="submit-field">
                        <h5><?php echo __('profileview_portfolia_project_overview','Project Overview');?></h5>
                        <textarea  class="form-control" name="description" id="description"><?php if($memberInfo){D($memberInfo->portfolio_description);}?></textarea>
                        <span id="descriptionError" class="rerror"></span>
                    </div>
       			</div>
       			<div class="col-lg-6 col-12">
                	<div class="submit-field remove_arrow_select">
                        <h5><?php echo __('profileview_portfolia_category','Category');?></h5>
                        <select name="category" id="category" data-size="4" class="selectpicker browser-default" title="Category" data-live-search="true">
                        <?php
                        if($all_category){
                            foreach($all_category as $category_list){
                                ?>
                                <option value="<?php D($category_list->category_id);?>" <?php if($memberInfo && $category_list->category_id==$memberInfo->category_id){echo 'selected';}?>><?php D(ucfirst($category_list->category_name));?></option>
                                <?php
                            }
                        }
                         ?>
                        </select>
                        <span id="categoryError" class="rerror"></span>
                    </div>
                    <div class="sub_category_display" <?php if($memberInfo && $category_list->category_id){}else{D('style="display: none"');}?> >
                        <div class="submit-field remove_arrow_select">
                            <h5><?php echo __('profileview_sub_category','Sub Category');?></h5>
                            <div id="load_sub_category">
                            <select name="sub_category" id="sub_category" data-size="4" class="selectpicker browser-default" title="Sub category" data-live-search="true">
                            <?php
                            if($all_category_subchild){
                                foreach($all_category_subchild as $category_subchild_list){
                                    ?>
                                    <option value="<?php D($category_subchild_list->category_subchild_id);?>" <?php if($memberInfo && $category_subchild_list->category_subchild_id==$memberInfo->category_subchild_id){echo 'selected';}?>><?php D(ucfirst($category_subchild_list->category_subchild_name));?></option>
                                    <?php
                                }
                            }
                             ?>
                            </select>
                            </div>
                            <span id="sub_categoryError" class="rerror"></span>
                        </div>
                    </div>
                    <div class="submit-field">
                        <h5><?php echo __('profileview_portfolia_complete_date','Completion Date (Optional)');?></h5>
                        <input type="date" class="form-control" value="<?php if($memberInfo && $memberInfo->portfolio_complete_date){D(date('Y-m-d',strtotime($memberInfo->portfolio_complete_date)));}?>" name="complete_date" id="complete_date" min="1990-12-31" max="<?php echo D(date('Y-m-d',strtotime('+5 year')));?>" placeholder="DD-MM-YYYY">
                        <span id="complete_dateError" class="rerror"></span>
                    </div>
       			</div>       			
       			</div>
                <div class="row">
                    <div class="col-lg-12 col-12">
                    <div class="submit-field">
                        <label><?php echo __('profileview_portfolia_image','Image');?></label>                
                        <input type="file" name="fileinput" id="fileinput">
                        <div class="upload-area" id="uploadfile">
                            <p><?php echo __('profileview_portfolia_drag','Drag');?> &amp; <?php echo __('profileview_portfolia_drop_file','drop file here');?> <br /><?php echo __('profileview_portfolia_or','or');?> <br /> <span class="text-site"><?php echo __('profileview_portfolia_click','click');?></span> <?php echo __('profileview_portfolia_select_file','to select file');?></p>
                        </div>
                        <div id="uploadfile_container">
                    <?php  
                        if($memberInfo && $memberInfo->portfolio_image){
                        $files=json_decode($memberInfo->portfolio_image);
                    ?>
                            <div id="thumbnail_1" data-name="<?php echo $files->file;?>" class="thumbnail_sec">
                                <input type="hidden" name="projectfileprevious" value='<?php D($memberInfo->portfolio_image)?>'/>
                                <a href="<?php echo UPLOAD_HTTP_PATH.'member-portfolio/'.$files->file;?>" target="_blank"><?php echo $files->name;?></a>
                                <a href="javascript:void(0)" class="text-danger ripple-effect ico float-end" onclick="$(this).parent().remove()">
                                <i class="icon-feather-trash"></i>
                                </a>
                            </div>   
                        <?php 
                        } 
                        ?>
                        </div>
                    </div>
                    </div>
                </div> 
       		</form>
    <?php }?>
    </div>
<script type="text/javascript" src="<?php echo JS;?>upload-drag-file.js"></script>
<script type="text/javascript">
	$('#category').on('change',function(){
	$('.sub_category_display').show();
	$( "#load_sub_category").html('<div class="text-center" style="min-height: 70px;width: 100%;line-height: 50px;">'+SPINNER+'<div>').show();
		$.get( "<?php echo get_link('editprofileAJAXURL')?>",{'formtype':'getsubcat','Okey':$(this).val()}, function( data ) {
			setTimeout(function(){ $("#load_sub_category").html(data);$('.selectpicker').selectpicker('refresh');},1000)
		});
	})
function uploadData(formdata){
	num = 1;	
	$("#uploadfile_container").html('<div id="thumbnail_'+num+'" class="thumbnail_sec">'+SPINNER+'</div>');
    $.ajax({
        url: "<?php D(get_link('uploadFileFormCheckAJAXURL'))?>?from=portfolio",
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response){
           if(response.status=='OK'){
    			var name = response.upload_response.original_name;
    			$("#thumbnail_"+num).html('<input type="hidden" name="projectfile[]" value=\''+JSON.stringify(response.upload_response)+'\'/> <a href="<?php D(get_link('downloadTempURL'))?>/'+response.upload_response.file_name+'" target="_blank">'+name+'</a><a href="<?php D(VZ);?>" class=" text-danger ripple-effect ico float-end" onclick="$(this).parent().remove()"><i class="icon-feather-trash"></i></a>');
		   }else{
		   		$("#thumbnail_"+num).html('<p class="text-danger">Error in upload file</p>');
		   }
           
        },
        
    }).fail(function(){
    	$("#thumbnail_"+num).html('<p class="text-danger">Error occurred</p>');
    });
}
</script>