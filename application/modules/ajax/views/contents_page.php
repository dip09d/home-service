
<?php 
        $page_title = $content_list['page_title'];
        $contents = $content_list['contents'];
        $contents = str_replace('{IMAGE_PATH}', base_url('assets/images').'/' , $contents);
        ?>
<section class="sub-banner">
	<h2 class="center"><?php echo $page_title; ?></h2>
</section>  

<div class="dark-wrapper">
                                    
        <?php echo $contents; ?>
        
</div>
