<section class="sub-banner">
	<h2 class="text-center">Pricing</h2>
</section>
<section class="sec overview" id="overview">
	<div class="container">			
			<div class="row">
				<?php if(count($plans) > 0){foreach($plans as $k => $v){ 
					if($k == 0){
						$head_css =  'study';
					}else if($k == 1){
						$head_css =  'account active';
					}else if($k == 2){
						$head_css =  'flat';
					}else{
						$head_css =  'study';
					} 
				?>
				<div class="col-sm-4 col-xs-12">					
					<div class="pricing text-center <?php echo $head_css;?>">
                    <div class="price-title">
                    <h3><?php echo $v['title']?></h3>
                    </div>
					<?php if(count($v['features']) > 0){foreach($v['features'] as $feature){ ?>
					 <p><?php echo $feature['feature'];?></p><hr>
					<?php } } ?>
					<a href="<?php echo base_url('cms/choosen_plan/'.$v['id']);?>" class="btn btn-block btn-info">Order</a>
                    <br />
                </div>
				</div>
				<?php } } ?>				
			</div>
			<div class="clearfix"></div>
				<div class="text-center">
					<a href="<?php echo base_url('cms/choosen_plan/F');?>" class="btn site btn-lg text-uppercase">Free Trail</a>
				</div>
		</div>    
</section>

