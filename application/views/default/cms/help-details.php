<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//var_dump($details);
?>
<div class="single-page-header bg-site">
	<div class="container">		
		<h1 class="text-center"><?php D($details->title); ?></h1>
	</div>
</div>
<section class="section">
  <div class="container">   
  		<p><a href="<?php D(get_link('CMShelp'))?>"><?php echo __('cms_help_details','Back to Help');?></a></p>
        
    	<?php D(html_entity_decode($details->description)); ?>
    </div>
</section>