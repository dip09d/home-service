<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//print_r($memberInfo);
$image=NO_IMAGE;
if($memberInfo->portfolio_image){
    $portfolio_image=json_decode($memberInfo->portfolio_image);
    if(file_exists(UPLOAD_PATH."member-portfolio/".$portfolio_image->file)){
        $image=UPLOAD_HTTP_PATH."member-portfolio/".$portfolio_image->file;
    }
}
?>
 <div class="modal-header">        
    <h4 class="modal-title"><?php echo __('profileview_portfolia_view','View portfolio');?></h4>
    <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
</div>
<div class="modal-body">
    <div class="row">
        <aside class="col-lg-8 col-12">
            <div id="carouselExampleCaptions" class="carousel slide mb-3 mb-lg-0" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <?php if($image){?>
                    <img src="<?php echo $image;?>" class="d-block w-100" alt="...">   
                    <?php }?>   
                    </div>
                   
                </div>
            </div>
        </aside>
        <aside class="col-lg-4 col-12">
            <p><b><?php if($memberInfo){D($memberInfo->portfolio_title);}?></b> <?php if($memberInfo && $memberInfo->portfolio_url){?><a href="<?php D($memberInfo->portfolio_url);?>" target="_blank"><i class="icon-feather-external-link"></i></a><?php }?></p>    
            <?php if($memberInfo && $memberInfo->category_name){?>
            <p class="mb-0"><?php echo __('profileview_portfolia_view_category','Category:');?>  <?php D($memberInfo->category_name);?> </p>
            <?php if($memberInfo && $memberInfo->category_subchild_name){?>
            <p class="mb-0"><?php echo __('profileview_portfolia_view_sub_category','Sub Category:');?>  <?php D($memberInfo->category_subchild_name);?> </p>
            <?php }?>
            <?php }?>
            <p><?php if($memberInfo){D(nl2br($memberInfo->portfolio_description));}?></p>
            <?php if($memberInfo && $memberInfo->portfolio_complete_date){?>
            <p class="mb-0"><i class="icon-feather-calendar"></i> <?php D(date('d M, Y',strtotime($memberInfo->portfolio_complete_date)));?></p>
            <?php }?>
        </aside>
    </div>
</div> 
