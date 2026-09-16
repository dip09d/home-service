<!-- Membership Plans -->
<div class="dashboard-container">
	<?php echo $left_panel;?>
	<div class="dashboard-content-container">
		<div class="dashboard-content-inner" >
    <?php
  //get_print($selected_membership,false);
  if($selected_membership && $selected_membership->is_free==0 &&  $selected_membership->membership_expire_date >= date('Y-m-d')){
  ?>
  <div class="alert alert-warning"><?php echo __('membership_your_current_membership','Your current membership plan');?>  : <?php echo $selected_membership->details->name;?> till <?php echo date('d M, Y',strtotime($selected_membership->membership_expire_date));?></div>
  <?php
  }
  ?>
    <!-- Section Headline -->
    <div class="section-headline centered margin-top-0 mb-3">
      <h2><?php echo __('membership_plans','Membership Plans');?></h2>
      <p><?php echo __('membership_plans_p_tag','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua enim ad minim veniam, quis nostrud exercitation ullamco.');?></p>
    </div>
    <?php 
   //get_print($membership,false); 
    ?>
    <!-- Billing Cycle  -->
      <div class="billing-cycle-radios mb-4">
        <div class="radio billed-monthly-radio">
          <input id="radio-5" name="radio-payment-type" type="radio" checked value="month">
          <label for="radio-5"><span class="radio-label"></span><?php echo __('membership_billed_monthly','Billed Monthly');?> </label>
        </div>
        <div class="radio billed-yearly-radio">
          <input id="radio-6" name="radio-payment-type" type="radio" value="year">
          <label for="radio-6"><span class="radio-label"></span><?php echo __('membership_billed_yearly','Billed Yearly');?>  <span class="small-label"><?php echo __('membership_sav_10','Save 10%');?></span></label>
        </div>
      </div>
    <!-- Pricing Plans Container -->
    <div class="pricing-plans-container"> 
    <?php
    $currency=get_setting('site_currency');
    if($membership){
      foreach($membership as $k=>$row){
      ?>
      <!-- Plan -->
      <div class="pricing-plan <?php if($k==1){echo 'recommended';}?>">
        <h3><?php echo $row->name;?></h3>
        <?php if($row->price_per_month>0){?>
        <div class="pricing-plan-label billed-monthly-label"><strong><?php echo $currency;?><?php echo $row->price_per_month;?></strong>/ mo<?php if($k==1){echo '*';}?></div>
        <?php }else{?>
        <div class="pricing-plan-label billed-monthly-label"><strong><?php echo __('membership_free','Free');?></strong></div>
        <?php }?>
        <?php if($row->price_per_year>0){?>
        <div class="pricing-plan-label billed-yearly-label"><strong><?php echo $currency;?><?php echo $row->price_per_year;?></strong>/ yr</div>
        <?php }else{?>
        <div class="pricing-plan-label billed-yearly-label"><strong><?php echo __('membership_free','Free');?></strong></div>
        <?php }?>

        <img src="<?php echo IMAGE;?>badge_<?php echo ($k==1 ? 'white':'green')?>.png" alt="badge" class="mb-2">
        <div class="pricing-plan-features"> <strong><?php echo __('membership_features','Features');?></strong>
          <ul class="list list-2">
            <li><?php echo $row->description;?></li>
            <li><?php echo $row->membership_bid;?><?php echo __('membership_bids','bids');?> </li>
            <li><?php echo $row->membership_portfolio;?><?php echo __('membership_portfolio','portfolio');?> </li>
            <li><?php echo $row->membership_skills;?> <?php echo __('membership_skills','skills');?></li>
            <li><?php echo $row->membership_commission_percent;?><?php echo __('membership_commission','% commission');?></li>
          </ul>
        </div>
        <?php if($row->price_per_month>0){?>
        <a href="<?php D(VZ);?>" data-id="<?php D(md5($row->membership_id));?>" class=" select-membership btn <?php echo ($k==1 ? 'btn-white':'btn-primary')?>  btn-block"><?php echo __('membership_select_plan','Select Plan');?></a> 
      <?php }?>
      </div>
      <?php
      }
    }
    ?>
    </div>
  </div>
</div>
</div>
<!-- Membership Plans / End-->
<?php /*?>
<section class="section pt-2">  
<div class="container">	
	<!-- Section Headline -->
    <div class="section-headline centered margin-top-0 mb-3">
      <h2 class="mb-0">FAQs </h2>      
      <h4>Related to membership plans</h4>
    </div>
    <div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
        <h4 class="mb-2"><a class="d-flex collapsed" href="javascript:void(0)" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Why should I upgrade? <i class="ms-auto icon-feather-plus"></i></a></h4>        
        <p class="mb-0">You get more earning opportunities and better savings as a Basic, Plus, Professional or Premier member. Bid on more projects, add more skills, save on project listing upgrades and unlock special rewards!</p>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body pt-0">
        <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua enim ad minim veniam, quis nostrud exercitation ullamco.</p>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h4 class="mb-2"><a class="d-flex collapsed" href="javascript:void(0)" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Can I change plans? <i class="ms-auto icon-feather-plus"></i></a></h4>        
      <p class="mb-0">Of course! Upgrade your membership plan at anytime to get additional benefits immediately. Alternatively, you can downgrade your membership and continue to receive the benefits of your current membership until it expires, before switching to the lower membership tier.</p>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body pt-0">
        <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h4 class="mb-2"><a class="d-flex collapsed" href="javascript:void(0)" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">How much will my project cost total? <i class="ms-auto icon-feather-plus"></i></a></h4>
      <p class="mb-0">Clients can post either hourly or fixed-price jobs. Describe your project in as much detail as possible and freelancers will submit a bid with their proposed cost. Most payments are subject to a standard 5% processing fee.</p>      
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body pt-0">
        <p>Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</p>
      </div>
    </div>
  </div>
</div>   
</div>
</section>
<script src="<?php echo JS;?>jquery-3.3.1.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
$('.collapse').on('shown.bs.collapse', function(){
$(this).parent().find(".icon-feather-plus").removeClass("icon-feather-plus").addClass("icon-feather-minus");
}).on('hidden.bs.collapse', function(){
$(this).parent().find(".icon-feather-minus").removeClass("icon-feather-minus").addClass("icon-feather-plus");
});
});
</script>
<?php */?>
<script>
var main=function(){
  $(".select-membership").click(function(e){
		e.preventDefault();
    var id=$(this).data('id');
    var duration=$('input[name="radio-payment-type"]:checked').val();
    window.location.href="<?php echo get_link('processMembershipURL')?>/"+id+'/'+duration;

  });
}
</script>
<?php if(($this->input->get('refer') && $this->input->get('refer')=='paymentsuccess') || ($this->input->get('ref_p') && $this->input->get('ref_p')=='paymentsuccess')){?>
<script>
var mainload=function(){
	bootbox.alert({
		title: '<?php D(__('popup_manageproposal_Payment_Success',"Payment Success"));?>',
		message: 'Payment Successfull',
		size: 'small',
		buttons: {
			ok: {
				label: "Ok",
				className: 'btn-primary float-end'
			},
		},
		callback: function(result){
			window.location.href='<?php D(get_link('dashboardURL'));?>';
		}

	});
}
</script>
<?php }elseif(($this->input->get('refer') && $this->input->get('refer')=='paymenterror') || ($this->input->get('ref_p') && $this->input->get('ref_p')=='paymenterror')){?>
<script>
var mainload=function(){
	bootbox.alert({
		title: '<?php D(__('popup_manageproposal_Payment_Error',"Payment Error"));?>',
		message: 'Payment failed',
		size: 'small',
		buttons: {
			ok: {
				label: "Ok",
				className: 'btn-primary float-end'
			},
		},
		callback: function(result){
			window.location.href='<?php D(get_link('dashboardURL'));?>';
		}

	});
}
</script>
<?php }?>
