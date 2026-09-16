<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="modal-header">
        <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('profileview_cancel','Cancel');?></button>
        <?php if($dataid){?>
        <h4 class="modal-title"><?php echo __('profileview_language_change','Change language');?></h4>
        <?php }else{?>
        <h4 class="modal-title"><?php echo __('profileview_language_add','Add language');?></h4>
        <?php }?>
        <button type="button" class="btn btn-success float-end" onclick="SaveLanguage(this)"><?php echo __('profileview_save','Save');?></button>
      </div>
    <div class="modal-body">
    <form action="" method="post" accept-charset="utf-8" id="languageform" class="form-horizontal" role="form" name="languageform" onsubmit="return false;">  
    <input  type="hidden" value="<?php echo $formtype;?>" id="formtype" name="formtype"/>
    <input  type="hidden" value="<?php echo $dataid;?>" id="dataid" name="dataid"/>
    <?php if($memberInfo && $memberInfo->language_id){?>
    <input  type="hidden" value="<?php echo $memberInfo->language_id;?>" id="language" name="language"/>        
        <div class="submit-field">
            <h5><?php echo __('profileview_language','Language:');?></h5> 
            <p><?php D($memberInfo->language_name);?></p>
        </div>
    <?php }else{?>       	
        <div class="submit-field remove_arrow_select">
            <h5><?php echo __('profileview_language','Language:');?></h5>  
            <select name="language" id="language"  class="selectpicker browser-default" title="Select language" data-live-search="true">
                <?php
                if($alllanguage){
                    foreach($alllanguage as $alllanguage_list){
                        ?>
                        <option value="<?php echo $alllanguage_list->language_id;?>" <?php if($memberInfo && $alllanguage_list->language_id==$memberInfo->language_id){echo 'selected';}?>><?php echo ucfirst($alllanguage_list->language_name);?></option>
                        <?php
                    }
                }
                 ?>
            </select>          	
        </div>
        <span id="languageError" class="rerror"></span>
        
    <?php }?>
    	
        <div class="submit-field remove_arrow_select">
            <h5><?php echo __('profileview_language_proficiency','Proficiency:');?></h5>  
            <div class="language-input margin-top-0">
            <?php
                if($language_preference){
                    foreach($language_preference as $l=>$language_preference_list){
                        ?>
                        <div class="radio">
                            <input id="radio-<?php D($l);?>" name="language_preference" type="radio" <?php if(($memberInfo && $language_preference_list->language_preference_id==$memberInfo->language_preference_id) || $l==0){echo 'checked';}?> value="<?php D($language_preference_list->language_preference_id)?>">
                            <label for="radio-<?php D($l);?>"><span class="radio-label"></span> <b><?php D($language_preference_list->language_preference_name);?></b>: <?php D($language_preference_list->language_preference_info);?></label>
                        </div>
                    <?php
                    }
                }
                 ?>			
                </div>
            
        </div>
        <span id="language_preferenceError" class="rerror"></span>   
    </form>
    </div>