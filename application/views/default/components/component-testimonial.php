<?php
$this->load->model('home/home_model');
if(!empty($show_in_page)){
	$testimonials = $this->home_model->getTestimonial($show_in_page);
}else{
	$testimonials = $this->home_model->getTestimonial();
}

?>
<link href="<?php echo CSS;?>testimonial.css" rel="stylesheet" type="text/css">
<div class="testimonial-style-5 testimonial-slider-2 poss--relative">
    
        <!-- Start Testimonial Nav -->
        <div class="testimonal-nav">
			<?php if($testimonials){foreach($testimonials as $k => $v){ 
			if($v->photo && file_exists(WEBSITE_UPLOAD_PATH.$v->photo)){
				$photo = WEBSITE_UPLOAD_HTTP_PATH.$v->photo;
			}else{
				$photo = NO_IMAGE_USER;
			}
			?>
			 <div class="testimonal-img">
                <img src="<?php echo $photo; ?>" alt="<?php echo $v->user_info;?>">
            </div>
			<?php } } ?>
            
        </div>
        <!-- End Testimonial Nav -->    
        <!-- Start Testimonial For -->
        <div class="testimonial-for"> 
			<?php if($testimonials){foreach($testimonials as $k => $v){ ?>		
            <div class="testimonial-desc">
            	<img src="<?php echo IMAGE; ?>quote_left.png" alt="" class="quote-left">            
                <div class="triangle"></div>                
                <div class="client">
                    
                    <h4><?php echo $v->user_info;?></h4>
                    <ul class="rating" hidden>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star-half-o"></i></li>
                    </ul>
                </div>                
                <p><?php echo $v->message;?></p>
                    <img src="<?php echo IMAGE; ?>quote_right.png" alt="" class="quote-right">
            </div>
			<?php } } ?>
            
        <!-- End Testimonial For -->        
    
    </div>
</div>
	<script src="<?php echo JS;?>testimonial.slick.min.js"></script>
<script src="<?php echo JS;?>testimonial.active.js"></script>