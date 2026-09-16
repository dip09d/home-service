<?php
$loggedUser=$this->session->userdata('loggedUser');

?>
<!-- Header Container -->
<style>
@media (min-width: 992px) {
    #header .container {
        display: flex !important;
        align-items: center !important;
        position: relative !important;
    }
    #header .start-side {
        float: none !important;
    }
    #header .end-side {
        float: none !important;
        position: absolute !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
    #navigation ul {
        display: flex !important;
        justify-content: center !important;
    }
    #navigation ul li {
        float: none !important;
    }
}
</style>
<header id="header-container" class="fullwidth">
<?php /* if($this->router->fetch_class()=='dashboard'){?>dashboard-header not-sticky<?php }*/?>
	<!-- Header -->
	<div id="header">
		<div class="container">
			
			<!-- Left Side Content -->
			<div class="start-side">				
				<!-- Logo -->
				<div id="logo">
					<a href="<?php echo base_url();?>"><img src="<?php echo LOGO;?>" data-sticky-logo="<?php echo LOGO;?>" data-transparent-logo="<?php echo LOGO;?>" alt="SnapHive" style="height: 90px !important; width: auto !important; max-height: 90px !important; object-fit: contain;"></a>
				</div>				
				
			</div>
			<!-- Left Side Content / End -->


			<!-- Right Side Content / End -->
			<div class="end-side">
            	<!-- Main Navigation -->
				<nav id="navigation">
					<ul id="responsive">
					<li><a href="<?php D(get_link())?>"><?php echo __('home','Home');?></a></li>                  
                    <li><a href="<?php D(get_link('CMSaboutus'))?>"><?php echo __('about_us','About Us');?></a></li>
					<li><a href="<?php D(get_link('servicesURL'))?>"><?php echo __('services','Services');?></a></li>                    
					<li><a href="<?php D(get_link('conatctURL'))?>"><?php echo __('contact_us','Contact Us'); ?></a></li>									                    
				  </ul>
				</nav>				
				<!-- Main Navigation / End -->			
                
				<!-- Mobile Navigation Button -->
				<span class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</span>

			</div>
			<!-- Right Side Content / End -->

		</div>
	</div>
	<!-- Header / End -->

</header>
<div class="clearfix"></div>
<!-- Header Container / End -->
