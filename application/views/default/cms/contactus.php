<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($filter);
?>

<style>
.contact-hero {
    background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
    padding: 5rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 4rem;
    border-radius: 0 0 40px 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
}
.contact-hero::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(43,170,177,0.15) 0%, rgba(255,255,255,0) 70%);
    top: -100px;
    left: -50px;
    border-radius: 50%;
}
.contact-title {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}
.contact-title span {
    background: linear-gradient(135deg, #1e293b 0%, #2BAAB1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.contact-subtitle {
    font-size: 1.15rem;
    color: #64748b;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
    position: relative;
    z-index: 1;
}
.premium-contact-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 3rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.04);
    border: 1px solid rgba(43,170,177,0.08);
}
.form-field {
    margin-bottom: 1.5rem;
}
.form-field .form-label {
    font-weight: 600;
    color: #334155;
    margin-bottom: 0.5rem;
}
.form-field .form-control {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 0.875rem 1rem;
    background: #f8fafc;
    transition: all 0.3s ease;
    color: #1e293b;
}
.form-field .form-control:focus {
    background: #ffffff;
    border-color: #2BAAB1;
    box-shadow: 0 0 0 4px rgba(43,170,177,0.1);
    outline: none;
}
.help-text {
    font-size: 0.85rem;
    color: #94a3b8;
    margin-top: 0.5rem;
}
.info-card {
    background: #f8fafc;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
    border: 1px solid rgba(43,170,177,0.05);
    transition: all 0.3s ease;
}
.info-card:hover {
    background: #ffffff;
    box-shadow: 0 10px 20px rgba(43,170,177,0.08);
    transform: translateY(-3px);
    border-color: rgba(43,170,177,0.2);
}
.info-icon {
    width: 48px;
    height: 48px;
    background: rgba(43,170,177,0.1);
    color: #2BAAB1;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.info-content h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}
.info-content p, .info-content a {
    color: #64748b;
    margin: 0;
    font-size: 0.95rem;
    text-decoration: none !important;
}
.info-content a:hover {
    color: #2BAAB1;
}
.map-container {
    border-radius: 16px;
    overflow: hidden;
    margin-top: 2rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.04);
    border: 1px solid #e2e8f0;
}
.btn-primary-custom {
    background: linear-gradient(135deg, #2BAAB1 0%, #22888E 100%);
    color: white !important;
    border: none;
    border-radius: 12px;
    padding: 1rem 2.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
}
.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(43,170,177,0.3);
}
.uploadButton-button {
    border-radius: 12px !important;
    font-weight: 600 !important;
}
.social-links-grid {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}
.social-links-grid a {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(43,170,177,0.1);
    color: #2BAAB1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    text-decoration: none;
}
.social-links-grid a:hover {
    background: #2BAAB1;
    color: white;
    transform: translateY(-2px);
}
</style>

<div class="contact-hero">
    <div class="container">
        <h1 class="contact-title">Get in <span>Touch</span></h1>
        <p class="contact-subtitle">Have a question about a home service booking or need technical support? Drop us a message and our team will get back to you shortly.</p>
    </div>
</div>

<section class="section mb-5">
  <div class="container"> 
  <div class="row g-5">  
  
  <aside class="col-lg-7 col-12">
    <div class="premium-contact-card">
        <form id="contact_form">
        <div id="server_status"></div>
        
        <div class="form-field">
            <label class="form-label"><?php echo __('cms_contactus_inquiry','What is your inquiry about?');?> <span class="req text-danger">*</span></label>
            <input type="text" class="form-control" name="inquiry" placeholder="e.g., Booking Issue, Partnership, General Query" />
        </div>
        
        <div class="form-field">
            <label class="form-label"><?php echo __('cms_contactus_email','Your email address');?> <span class="req text-danger">*</span></label>
            <input type="email" class="form-control" name="email" placeholder="hello@example.com" />
        </div>
        
        <div class="form-field">
            <label class="form-label"><?php echo __('cms_contactus_description','Description');?> <span class="req text-danger">*</span></label>
            <textarea rows="5" class="form-control" name="description" placeholder="How can we help you today?"></textarea>
            <p class="help-text"><?php echo __('cms_contactus_respons','Please enter the details of your request. A member of our support staff will respond as soon as possible.');?></p>
        </div>
        
        <div class="form-field">
            <label class="form-label"><?php echo __('cms_contactus_attachment','Attachments (Optional)');?></label>
            <div class="uploadButton">
                <input class="uploadButton-input" name="attachment" type="file" accept="image/*, application/pdf" id="upload1" />
                <label class="uploadButton-button ripple-effect" for="upload1"><?php echo __('cms_contactus_upload','Upload Files');?></label>
                <span class="uploadButton-file-name"><?php echo __('cms_contactus_helpful','Images of the issue you need fixed or relevant PDFs');?></span>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary-custom mt-2"><?php echo __('cms_contactus_submit','Send Message');?></button>
        </form>
    </div>
  </aside>

  <aside class="col-lg-5 col-12">
    
    <div class="info-card">
        <div class="info-icon"><i class="ri-map-pin-line"></i></div>
        <div class="info-content">
            <h4><?php echo __('cms_contactus_office','Our Office');?></h4>
            <p>Eraqi Street, South Bazar, Andal,<br>West Bengal, 713321</p>
        </div>            					
    </div>
    
    <div class="info-card">
        <div class="info-icon"><i class="ri-mail-send-line"></i></div>
        <div class="info-content">
            <h4><?php echo __('cms_contactus_mail','Drop A Mail');?></h4>
            <a href="mailto:moin.knockonce@gmail.com">moin.knockonce@gmail.com</a>
        </div>								
    </div>
    
    <div class="info-card">
        <div class="info-icon"><i class="ri-phone-line"></i></div>
        <div class="info-content">
            <h4><?php echo __('cms_contactus_call','Call Us');?></h4>
            <a href="tel:+919525952621">+91 9525952621</a>
        </div>								
    </div>
    
    <div class="info-card">        
        <div class="info-icon"><i class="ri-share-line"></i></div>
        <div class="info-content">
        	<h4><?php echo __('cms_contactus_social','Social Links');?></h4>
            <div class="social-links-grid">
                <?php 
                $facebook_url=get_setting('facebook_url');
                if($facebook_url){?>
                    <a href="<?php echo  $facebook_url;?>" title="Facebook"><i class="icon-brand-facebook-f"></i></a>
                <?php }?>
                <?php 
                $twitter_url=get_setting('twitter_url');
                if($twitter_url){?>
                    <a href="<?php echo  $twitter_url;?>" title="Twitter"><i class="icon-brand-twitter"></i></a>
                <?php }?>
                <?php 
                $linkedin_url=get_setting('linkedin_url');
                if($linkedin_url){?>
                    <a href="<?php echo  $linkedin_url;?>" title="LinkedIn"><i class="icon-brand-linkedin-in"></i></a>
                <?php }?>
                <?php 
                $instagram_url=get_setting('instagram_url');
                if($instagram_url){?>
                    <a href="<?php echo  $instagram_url;?>" title="Instagram"><i class="icon-brand-instagram"></i></a>
                <?php }?>
                <?php 
                $youtube_url=get_setting('youtube_url');
                if($youtube_url){?>
                    <a href="<?php echo  $youtube_url;?>" title="Youtube"><i class="icon-brand-youtube"></i></a>
                <?php }?>  
            </div>
        </div>								
    </div>
  
    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d471218.38560188503!2d88.04952746944409!3d22.676385755547646!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f882db4908f667%3A0x43e330e68f6c2cbc!2sKolkata%2C%20West%20Bengal!5e0!3m2!1sen!2sin!4v1578895327808!5m2!1sen!2sin" height="300" frameborder="0" style="border:0;width:100%" allowfullscreen=""></iframe>
    </div>
  </aside>

  </div>
  </div>
</section>

<script>
var main=function(){
	$('#contact_form').submit(subContact);
}
function subContact(e){
	e.preventDefault();
	var submit_btn = $(this).find('[type="submit"]'),
		btn_text = submit_btn.html();
	submit_btn.attr('disabled', 'disabled');
	submit_btn.html('Sending <i class="ri-loader-4-line ri-spin"></i>');
	$('#server_status').html('');
	var _self = $(this);
	var _self_form = new FormData(_self[0]);
	$.ajax({
		url : '<?php echo get_link('conatctCheckAjaxURL')?>',
		data: _self_form,
		type: 'POST',
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function(res){
			submit_btn.removeAttr('disabled');
			submit_btn.html(btn_text);
			if(res.status == 1){
				$('#server_status').html(res.success_html);
				setTimeout(function(){
					location.reload();
				}, 2000);
				
			}else{
				$('#server_status').html(res.error_html);
			}
		}
	});
	
}
</script>