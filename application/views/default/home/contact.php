<?php 
$current_banner = !empty($banner[0]) ? $banner[0] : null;
?>
<style>
.incorrect,.incorrect_parent{
    border: 1px solid red;
}
</style>
<div class="breadcrumb-with-bg" data-background-image="<?php echo $current_banner ? $current_banner['image_url'] : ''; ?>">
    <div class="container">
        <?php echo $current_banner ?  $current_banner['title'] : null; ?>
    </div>
</div>

<section>  
<div class="contact-location-info margin-bottom-65">                    
    <div id="single-job-map-container">
        <div id="singleListingMap" data-latitude="37.777842" data-longitude="-122.391805" data-map-icon="im im-icon-Hamburger"></div>
        <a href="#" id="streetView"><?php echo __('home_page_contact_street_view','Street View');?></a>
    </div>
</div>

<div class="container">
    <div class="contact-address margin-bottom-40">
    <div class="row">
    	<article class="col-md-4">
        	<div class="card">
            <div class="card-body">
         	<img src="<?php echo LOGO; ?>" alt="SnapHive" height="60" class="logo" style="width: auto; object-fit: contain;" />
            <p><i class="icon-feather-map-pin"></i> Nordlandsstigen 2, 139 36 Värmdö</p>
            <p><i class="icon-feather-phone"></i> (010) 551 70 30</p>
            <p><i class="icon-feather-mail"></i> kundservice@efirm.se</p>
            <div class="freelancer-socials" hidden>
                <ul>
                    <li><a href="#" title="Facebook" data-tippy-placement="top"><i class="icon-brand-facebook"></i></a></li>
                    <li><a href="#" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
                    <li><a href="#" title="Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus"></i></a></li>
                    <li><a href="#" title="Linkedin" data-tippy-placement="top"><i class="icon-brand-linkedin"></i></a></li>                
                </ul>                
            </div>
            </div>
            </div>
        </article>
        
    	<article class="col-md-4">
        	<div class="card">
            <div class="card-body">
        	<img src="<?php echo IMAGE; ?>efirm_invoice.png" alt="" height="60" class="logo" />
            <p><i class="icon-feather-map-pin"></i> Nordlandsstigen 2, 139 36 Värmdö</p>
            <p><i class="icon-feather-phone"></i> (010) 551 70 30</p>
            <p><i class="icon-feather-mail"></i> kundservice@efirm.se</p>
            <div class="freelancer-socials" hidden>
                <ul>
                    <li><a href="#" title="Facebook" data-tippy-placement="top"><i class="icon-brand-facebook"></i></a></li>
                    <li><a href="#" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
                    <li><a href="#" title="Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus"></i></a></li>
                    <li><a href="#" title="Linkedin" data-tippy-placement="top"><i class="icon-brand-linkedin"></i></a></li>                
                </ul>
            </div>
            </div>
            </div>
        </article>
        
        <article class="col-md-4">
        	<div class="card">
            <div class="card-body">
        	<img src="<?php echo IMAGE; ?>jobb.png" alt="" height="60" class="logo" />
            <p><i class="icon-feather-map-pin"></i> Nordlandsstigen 2, 139 36 Värmdö</p>
            <p><i class="icon-feather-phone"></i> (010) 551 70 30</p>
            <p><i class="icon-feather-mail"></i> kundservice@efirm.se</p>
            <div class="freelancer-socials" hidden>
                <ul>
                    <li><a href="#" title="Facebook" data-tippy-placement="top"><i class="icon-brand-facebook"></i></a></li>
                    <li><a href="#" title="Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
                    <li><a href="#" title="Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus"></i></a></li>
                    <li><a href="#" title="Linkedin" data-tippy-placement="top"><i class="icon-brand-linkedin"></i></a></li>                
                </ul>
            </div>
            </div>
            </div>
        </article>
        
    </div>
        
                    
	</div>
    
	<section id="contact" class="margin-bottom-60">
				<h3 class="headline margin-top-15 margin-bottom-20"><?php echo __('home_page_contact_any_question','Any questions? Feel free to contact us!');?></h3>
				<div id="contact_status"></div>
				<form method="post" name="contactform" id="contactform" autocomplete="off">
					<input type="hidden" name="req_type" value="CONTACT_REQUEST"/>
					<div class="row">
						<div class="col-md-6">
                        	<div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-material-outline-account-circle"></i></span>
                              </div>
                              <input type="text" class="form-control" placeholder="Your Name" name="name">
                            </div>							
						</div>

						<div class="col-md-6">
							<div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-material-outline-email"></i></span>
                            </div>
							<input type="email" class="form-control" name="email" id="email" placeholder="Email Address" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" required="required" />
								
							</div>
						</div>
					</div>

					<div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-material-outline-assignment"></i></span>
                        </div>
						<input class="form-control" name="subject" type="text" id="subject" placeholder="Subject" required="required" />						
					</div>

					<div class="form-field">
						<textarea class="form-control" name="message" cols="40" rows="5" id="comments" placeholder="Message" spellcheck="true" required="required"></textarea>
					</div>

					<button type="submit" class="btn btn-primary" id="submit_btn"><?php echo __('home_page_contact_send','Send');?></button>
					
					

				</form>
			</section>
</div>


</section>



<script src="https://maps.googleapis.com/maps/api/js?key=&libraries=places"></script>

<script src="<?php echo JS; ?>infobox.min.js"></script>
<script src="<?php echo JS; ?>markerclusterer.js"></script>
<script src="<?php echo JS; ?>maps.js"></script>

<script>
$('#contactform').ajaxSubmit({
	validate : false,
	url : {
		submit: '<?php echo base_url('ajax/contact_us')?>',
	},
	submitBtn: '#submit_btn',
	formType: 'contact_us',
	success: function(res){
		if(res.cmd == 'show_msg'){
			$('#contact_status').html(res.cmd_params.show_msg);
			$('#contactform')[0].reset(); 
		}
	},
});
</script>
