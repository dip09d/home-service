<?php
$fb_link = get_setting('facebook');
$twitter_link = get_setting('twitter');
$linkedin_link = get_setting('linkedin');
$gplus_link = get_setting('googleplus');
$insta_link = get_setting('instagram');
?>
<?php if($view == 'header'){ ?>
<ul class="footer-social-links">
	<?php if($fb_link){ ?>
	<li>
		<a title="Facebook" href="<?php echo $fb_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-facebook-f"></i>
		</a>
	</li>
	<?php } ?>
	
	<?php if($twitter_link){ ?>
	<li>
		<a title="Twitter" href="<?php echo $twitter_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-twitter"></i>
		</a>
	</li>
	<?php } ?>
	
	<?php if($gplus_link){ ?>
	<li>
		<a title="Google Plus"  href="<?php echo $gplus_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-google-plus-g"></i>
		</a>
	</li>
	<?php } ?>
	
	<?php if($linkedin_link){ ?>
	<li>
		<a title="LinkedIn" href="<?php echo $linkedin_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-linkedin-in"></i>
		</a>
	</li>
	<?php } ?>
	
	
</ul>	
<?php } else { ?>
<ul class="footer-social-links">
	<?php if($fb_link){ ?>
	<li>
		<a title="Facebook" href="<?php echo $fb_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-facebook-f"></i>
		</a>
	</li>
	<?php } ?>
	
	<?php if($twitter_link){ ?>
	<li>
		<a title="Twitter" href="<?php echo $twitter_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-twitter"></i>
		</a>
	</li>
	<?php } ?>
	
	<?php if($gplus_link){ ?>
	<li>
		<a title="Google Plus"  href="<?php echo $gplus_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-google-plus-g"></i>
		</a>
	</li>
	<?php } ?>
	
	<?php if($linkedin_link){ ?>
	<li>
		<a title="LinkedIn" href="<?php echo $linkedin_link; ?>" data-tippy-placement="bottom" data-tippy-theme="light" target="_blank">
			<i class="icon-brand-linkedin-in"></i>
		</a>
	</li>
	<?php } ?>
	
	
</ul>	
<?php } ?>
