<?php
if($template=='section_block'){
    $lang = get_lang();
	if($type=='list'){
     // get_print($cms_temp,false);  
	?>
<div class="box-group">
	<div class="section_block"> 
    <?php 
if($cms_temp){
	foreach($cms_temp as $k=>$block){
        $rowid=$k+1;
?>
<div class="section_block_row">
    <input type="hidden" name="cms_key[]" value="<?php echo $rowid;?>">

    <button class="btn btn-danger btn-circle btn-ico" type="button" onclick="$(this).closest('.section_block_row').remove()"><i class="icon-feather-trash"></i></button>

    <div class="form-group mb-0">
        <label class="form-label">Block type</label>
        <div class="radio-inline">
            <input type="radio" onclick="$('.block_type_section_<?php echo $rowid;?>').show();$('.block_type_custom_<?php echo $rowid;?>').hide();" name="block_type_<?php echo $rowid;?>" value="section" class="magic-radio" id="block_type_section_<?php echo $rowid;?>" <?php if($block->section_type=='SEC'){echo 'checked';}?>>
            <label for="block_type_section_<?php echo $rowid;?>">Section</label> 
        </div>
        <div class="radio-inline">
            <input type="radio" onclick="$('.block_type_section_<?php echo $rowid;?>').hide();$('.block_type_custom_<?php echo $rowid;?>').show();" name="block_type_<?php echo $rowid;?>" value="custom" class="magic-radio" id="block_type_custom_<?php echo $rowid;?>" <?php if($block->section_type=='CUS'){echo 'checked';}?>>
            <label for="block_type_custom_<?php echo $rowid;?>">Custom</label> 
        </div>
    </div>
    <div class="text-red error" id="block_type_<?php echo $rowid;?>Error"></div>
    <div class="block_type_section_<?php echo $rowid;?>" <?php if($block->section_type=='CUS'){echo 'style="display:none"';}?>>
    <div class="form-group">
        <label for="block_type_section_area_class_<?php echo $rowid;?>">Class name</label>
        <input class="form-control reset_field" type="text" id="block_type_section_area_class_<?php echo $rowid;?>" name="block_type_section_area_class_<?php echo $rowid;?>" autocomplete="off" value="<?php echo $block->cms_class;?>">
    </div> 
    <div class="form-group">
        <label for="block_type_section_area_sub_class_<?php echo $rowid;?>">Sub class name</label>
        <input class="form-control reset_field" type="text" id="block_type_section_area_sub_class_<?php echo $rowid;?>" name="block_type_section_area_sub_class_<?php echo $rowid;?>" autocomplete="off" value="<?php echo $block->child_class;?>">
    </div> 

    <div class="block_type_section_child col-sm-12">
    <?php if($block->part){
            foreach($block->part as $p=>$part){
                $partid=$p+1;   
    ?>
        <div class="block_type_section_child_row">
        	<button class="btn btn-danger btn-circle btn-ico" type="button" onclick="$(this).closest('.block_type_section_child_row').remove()"><i class="icon-feather-trash"></i></button>       
            <input type="hidden" name="child_key_p_<?php echo $rowid;?>[c_<?php echo $partid;?>][id]" value="<?php echo $partid;?>">
            <div class="form-group">
                <label for="block_type_section_child_class_<?php echo $rowid;?>_<?php echo $partid;?>">Area class name</label>
                <input class="form-control reset_field" type="text" id="block_type_section_child_class_<?php echo $rowid;?>_<?php echo $partid;?>" name="child_key_p_<?php echo $rowid;?>[c_<?php echo $partid;?>][block_type_section_child_class]" autocomplete="off" value="<?php echo $part['part_class'];?>">
            </div> 
            <?php foreach($lang as $k => $v){?>
            <div class="form-group">
                <label for="block_type_section_child_area_<?php echo $rowid;?>_<?php echo $partid;?>_<?php echo $v;?>">Area (<?php echo $v;?>)</label>
                <textarea class="form-control reset_field" id="block_type_section_child_area_<?php echo $rowid;?>_<?php echo $partid;?>_<?php echo $v;?>" name="child_key_p_<?php echo $rowid;?>[c_<?php echo $partid;?>][block_type_section_child_area][<?php echo $v;?>]" autocomplete="off"><?php if(array_key_exists($v,$part['part_content'])){echo $part['part_content'][$v];}?></textarea>

                <?php echo get_editor('block_type_section_child_area_'.$rowid.'_'.$partid.'_'.$v);?>
            </div>
            <?php }?>
        </div>
        <hr>
    <?php
            }
        ?>
    <?php }?>
    </div>
    
    <button class="btn btn-outline-site btn-sm" type="button" onclick="add_new_row_block_child(<?php echo $rowid;?>)"><i class="icon-feather-plus <?php echo ICON_SIZE;?>"></i> Add Section</button>
	</div> 
	<div class="block_type_custom_<?php echo $rowid;?>" <?php if($block->section_type=='SEC'){echo 'style="display:none"';}?>>
	<?php if($block->part){
                foreach($block->part as $p=>$part){
                    $partid=$p+1;   
        ?>
    <?php  foreach($lang as $k => $v){ ?>
            <div class="form-group">
                <label for="block_type_custom_area_<?php echo $rowid;?>_<?php echo $v?>">Custom HTML (<?php echo $v?>)</label>
                <div data-error-wrapper="block_type_custom_area_<?php echo $rowid;?>_<?php echo $v?>">
                    <textarea class="form-control reset_field" id="block_type_custom_area_<?php echo $rowid;?>_<?php echo $v?>" name="block_type_custom_area_<?php echo $rowid;?>[<?php echo $v?>]" autocomplete="off"><?php if(array_key_exists($v,$part['part_content'])){echo $part['part_content'][$v];}?></textarea>
                    <?php echo get_editor('block_type_custom_area_'.$rowid.'_'.$v);?>
                </div>
            </div>
    <?php }?>
    <?php break;}
    }?>
        </div> 
    </div>
    <hr>
<?php
    }
}   
?>  
    </div>
    <div class="clearfix"></div> 	
	<a href="javascript:void(0)" onclick="add_new_row_block()" class="btn btn-outline-site btn-sm mb-3"><i class="icon-feather-plus <?php echo ICON_SIZE;?>"></i> Add Section</a>
    <div class="text-red error" id="cms_keyError"></div>
	<div class="clearfix"></div> 					  
</div>
<?php }
	elseif($type=='template'){?>
    <script type="text/x-template" class="template_block">

  	<div class="section_block_row">
  	<input type="hidden" name="cms_key[]" value="{CNT}"/>            
            <button class="btn btn-danger btn-circle btn-ico" type="button" onclick="$(this).closest('.section_block_row').remove()"><i class="icon-feather-trash"></i></button>
            
            <div class="form-group mb-0">
                <label class="form-label">Block type</label>
                <div class="radio-inline">
                    <input type="radio" onclick="$('.block_type_section_{CNT}').show();$('.block_type_custom_{CNT}').hide();" name="block_type_{CNT}" value="section" class="magic-radio" id="block_type_section_{CNT}">
                    <label for="block_type_section_{CNT}">Section</label> 
                </div>
                <div class="radio-inline">
                    <input type="radio" onclick="$('.block_type_section_{CNT}').hide();$('.block_type_custom_{CNT}').show();" name="block_type_{CNT}" value="custom" class="magic-radio" id="block_type_custom_{CNT}">
                    <label for="block_type_custom_{CNT}">Custom</label> 
                </div>
            </div>
            <div class="text-red error" id="block_type_{CNT}Error"></div>
        	<div class="block_type_section_{CNT}" style="display:none">
			
            <div class="form-group">
                <label for="block_type_section_area_class_{CNT}">Class name</label>
                <input class="form-control reset_field" type="text" id="block_type_section_area_class_{CNT}" name="block_type_section_area_class_{CNT}" autocomplete="off">
            </div> 
			
            <div class="form-group">
                <label for="block_type_section_area_sub_class_{CNT}">Sub class name</label>
                <input class="form-control reset_field" type="text" id="block_type_section_area_sub_class_{CNT}" name="block_type_section_area_sub_class_{CNT}" autocomplete="off">
            </div> 

            <div class="block_type_section_child">
               
            </div>
            
            <button class="btn btn-outline-site btn-sm" type="button" onclick="add_new_row_block_child({CNT})"><i class="icon-feather-plus <?php echo ICON_SIZE;?>"></i> Add Block</button>
        	</div> 
        <div class="block_type_custom_{CNT}" style="display:none">
    	<?php  foreach($lang as $k => $v){ ?>
            <div class="form-group">
                <label for="block_type_custom_area_{CNT}_<?php echo $v;?>">Custom HTML (<?php echo $v;?>)</label>
                <div data-error-wrapper="block_type_custom_area_{CNT}_<?php echo $v;?>">
                <textarea class="form-control reset_field" id="block_type_custom_area_{CNT}_<?php echo $v;?>" name="block_type_custom_area_{CNT}[<?php echo $v; ?>]" autocomplete="off"></textarea>
                </div>
            </div>
        <?php }?>
        </div> 
        
	</div>
	</script>
	<script type="text/x-template" class="template_block_child">

    <div class="block_type_section_child_row">
        <button class="btn btn-danger btn-circle btn-ico" type="button" onclick="$(this).closest('.block_type_section_child_row').remove()"><i class="icon-feather-trash"></i></button>
        
        <input type="hidden" name="child_key_p_{CNT}[c_{CNTC}][id]" value="{CNTC}"/>
        <div class="form-group">
            <label for="block_type_section_child_class_{CNT}_{CNTC}">Area class name</label>
            <input class="form-control reset_field" type="text" id="block_type_section_child_class_{CNT}_{CNTC}" name="child_key_p_{CNT}[c_{CNTC}][block_type_section_child_class]" autocomplete="off">
        </div> 
        <?php  foreach($lang as $k => $v){ ?>
        <div class="form-group">
            <label for="block_type_section_child_area_{CNT}_{CNTC}_<?php echo $v;?>">Area (<?php echo $v;?>)</label>
            <textarea class="form-control reset_field" id="block_type_section_child_area_{CNT}_{CNTC}_<?php echo $v;?>" name="child_key_p_{CNT}[c_{CNTC}][block_type_section_child_area][<?php echo $v; ?>]" autocomplete="off"></textarea>
        </div>
        <?php }?>
    </div>
</script>
<script>
var alllanguage=<?php echo json_encode($lang);?>;
var ckeditor_url = "<?php echo ADMIN_PLUGINS.'ckeditor/ckeditor.js'?>";
var ckfinder_url = "<?php echo ADMIN_PLUGINS.'ckfinder'?>";
function get_editor(input_id){
    if(typeof CKEDITOR == 'undefined'){
        var scriptTag = document.createElement('script');
        scriptTag.type = 'text/javascript';
        scriptTag.src = ckeditor_url;
        scriptTag.onload = function(){
           CKEDITOR.replace(input_id, {
                    filebrowserBrowseUrl: ckfinder_url+'/ckfinder.html',
                    filebrowserUploadUrl: ckfinder_url+'/core/connector/php/connector.php?command=QuickUpload&type=Files',
                    filebrowserWindowWidth: '1000',
                    filebrowserWindowHeight: '700'
            });
        };
        document.body.appendChild(scriptTag);
    }else{
       CKEDITOR.replace(input_id, {
                    filebrowserBrowseUrl: ckfinder_url+'/ckfinder.html',
                    filebrowserUploadUrl: ckfinder_url+'/core/connector/php/connector.php?command=QuickUpload&type=Files',
                    filebrowserWindowWidth: '1000',
                    filebrowserWindowHeight: '700'
            });
    }
}



var sectionblockcnt=$('.section_block .section_block_row').length;
var sectionblockcntchild=$('.section_block .block_type_section_child_row').length;
function add_new_row_block(){	
	var html=$('.template_block').html();
	sectionblockcnt=sectionblockcnt+1;
  	cnt=sectionblockcnt;
  	html=html.replace(/{CNT}/g, cnt);
  	$('.section_block').append(html);
    if(alllanguage){
        for (i = 0; i < alllanguage.length; i++) {
            get_editor('block_type_custom_area_'+cnt+'_'+alllanguage[i]);
        }
    }   	
}
function add_new_row_block_child(parentcnt){	
	var html=$('.template_block_child').html();
	sectionblockcntchild=sectionblockcntchild+1;
  	cnt=sectionblockcntchild;
  	html=html.replace(/{CNT}/g, parentcnt);
  	html=html.replace(/{CNTC}/g, cnt);
  	$('.block_type_section_'+parentcnt+' .block_type_section_child').append(html);	 
    if(alllanguage){
        for (i = 0; i < alllanguage.length; i++) {
            get_editor('block_type_section_child_area_'+parentcnt+'_'+cnt+'_'+alllanguage[i]);
        }
    } 
    
}
</script>

<?php
}
 }?>