<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if($is_valid){?>
<section class="section">
<div class="container">            
    <div class="row">	
    <aside class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-12">			
        <div class="card text-center d-flex align-items-center" style="min-height:250px; flex-direction: inherit;">
        <div class="card-body">
            <img src="<?php echo IMAGE;?>checkmark.png" alt="" class="mb-3" />
            <h1 class="h4 mb-4"><?php echo __('user_page_verify_account','Your account has been activated successfully.');?></h1>
            <a href="<?php D(get_link('homeURL'))?>" class="btn btn-primary mb-2"><?php echo __('user_page_success_back_home','Back to Home');?></a>
		</div>
        </div>
	</aside>        
	</div>
</div>
</section>
<!--<script type="text/javascript">
var main=function(){
bootbox.alert({
	title:'Verify User',
	message: '<?php D(__('verify_user_success',"Your account has been activated successfully. Welcome on board."));?>',
	buttons: {
	'ok': {
		label: 'Ok',
		className: 'btn-primary float-end'
		}
	},
	callback: function () {
		window.location.href='<?php D(get_link('dashboardURL'))?>';
    }
});
}
</script>-->
<?php }else{?>
<section class="section">
<div class="container">            
    <div class="row">	
    <aside class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-12">			
        <div class="card text-center d-flex align-items-center" style="min-height:250px; flex-direction: inherit;">
        <div class="card-body">
            <img src="<?php echo IMAGE;?>close-circle.png" alt="" class="mb-3" />
            <h1 class="h4 mb-4"><?php echo __('user_page_verify_account_invalid','Your account activation link is invalid.');?></h1>
            <a href="<?php D(get_link('homeURL'))?>" class="btn btn-primary mb-2"><?php echo __('user_page_success_back_home','Back to Home');?></a>
		</div>
        </div>
	</aside>        
	</div>
</div>
</section>
<!--<script type="text/javascript">
var main=function(){
bootbox.alert({
	title:'Verify User',
	message: '<?php D(__('verify_user_invalid_link',"Your account activation link is invalid."));?>',
	buttons: {
	'ok': {
		label: 'Ok',
		className: 'btn-primary float-end'
		}
	},
	callback: function () {
		window.location.href='<?php D(get_link('homeURL'))?>';
    }
});
}
</script>-->
<?php }?>