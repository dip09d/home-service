<?php 
$blogURL=get_link('blogDetailsURL').'/'.$details->blog_slug;
$image=NO_IMAGE;
if($details->images->blog_image && file_exists(UPLOAD_PATH.'blog/banner/'.$details->images->blog_image)){
	$image=UPLOAD_HTTP_PATH.'blog/banner/'.$details->images->blog_image;
}
?>
<?php /*?><div class="short-banner" style="background-image:url('<?php echo IMAGE;?>bg-project.jpg')">
	<div class="container">		
		<div class="dashboard-headline mb-0">
        	<h1><?php echo __('Blog_details_page_heading','Blog Details');?></h1>
        </div>
	</div>
</div><?php */?>
<section class="section blog-details-page">
<div class="container">
	<div class="row">		
		<!-- Inner Content -->
		<div class="col-xl-8 col-lg-8">
			<div class="section-headline mb-3">
				<h1 class="h2"><?php echo $details->blog_title;?></h1>								
			</div>
			<!-- Blog Post -->
			<div class="blog-post single-post">

				<!-- Blog Post Thumbnail -->
				<div class="blog-post-thumbnail">
					<div class="blog-post-thumbnail-inner">
						<div class="blog-tags">
						<?php /*
						if($details->tags){
							foreach($details->tags as $t=>$tag){
						?>
						<span class="blog-item-tag"><?php echo ucfirst($tag->name);?></span>
						<?php
							}
						}						
						*/?>
						</div>
						<img src="<?php echo $image;?>" alt="<?php echo $details->blog_title;?>">
					</div>
				</div>

				<!-- Blog Post Content -->
				<div class="blog-post-content" style="background-size: contain;">					

					<div class="blog-post-info-list ">
						
						<a href="<?php echo VZ;?>" class="blog-post-info"><i class="ri-calendar-line"></i> <?php D(dateFormat($details->blog_reg_date,'M d, Y'))?></a>
						
						<a href="<?php echo VZ;?>"  class="blog-post-info"><i class="ri-eye-line"></i> <?php echo $details->blog_views;?> <?php echo __('blog_trending_views','Views');?></a>
					</div>

					<?php echo html_entity_decode($details->blog_description);?>
					
				</div>

			</div>
			<!-- Blog Post Content / End -->
			
			<!-- Blog Nav -->
			<ul id="posts-nav" class="">
				<?php if($next_blog){?>
				<li class="next-post">
					<a href="<?php echo get_link('blogDetailsURL').'/'.$next_blog->blog_slug;?>">
						<span><?php echo __('blog_trending_next_insight','Next Blog');?></span>
						<strong><?php echo $next_blog->blog_title;?></strong>
					</a>
				</li>
				<?php }?>
				<?php if($previous_blog){?>
				<li class="prev-post">
					<a href="<?php echo get_link('blogDetailsURL').'/'.$previous_blog->blog_slug;?>">
						<span><?php echo __('blog_trending_previous_insight','Previous Blog');?></span>
						<strong><?php echo $previous_blog->blog_title;?></strong>
					</a>
				</li>
				<?php }?>
			</ul>
			
		</div>
		<!-- Inner Content / End -->


		<div class="col-xl-4 col-lg-4">
				<div class="sidebar-container mt-5 mt-lg-0 mb-0">
					
						<form action="<?php echo get_link('blogListURL');?>">
						<!-- Location -->
						<div class="search-box input-group mb-4">
							<input type="text" class="form-control" value="<?php if($this->input->get('term')){echo $this->input->get('term');}?>" name="term" placeholder="<?php echo __('blog_trending_search','Search');?>">
							<button type="submit" class="btn btn-site" title="Search"><i class="icon-feather-search"></i></button>
						</div>
						</form>	
					

					<!-- Widget -->
					<div class="sidebar-widget">
						<div class="section-headline">
							<h3>Trending <span>Blogs</span></h3>
						</div>
						<ul class="widget-tabs">
							<?php
							if($tranding){
								foreach($tranding as $key=>$val){
									//print_r($val);
									$link = get_link('blogDetailsURL').'/'.$val->blog_slug;
									$image=NO_IMAGE;
									if($val->blog_thumb && file_exists(UPLOAD_PATH.'blog/thumb/'.$val->blog_thumb)){
										$image=UPLOAD_HTTP_PATH.'blog/thumb/'.$val->blog_thumb;
									}
							?>
								<li>
									<a href="<?php echo $link;?>" class="widget-content">
										<img src="<?php echo $image;?>" alt="<?php echo $val->blog_title;?>">
										<div class="widget-text">
											<h5><?php echo $val->blog_title;?></h5>
											<span><i class="ri-calendar-line"></i> <?php D(dateFormat($val->blog_reg_date,'M d, Y'))?></span>
										</div>
									</a>
								</li>
							<?php }
							}
							?>												
						</ul>
					</div>					
					<!-- Widget / End-->

					
                
                	<!-- Widget -->
                    <div class="panel mb-4">
                    	<div class="panel-header"><h4><?php echo __('projectview_view_tags','Tags');?></h4></div>
                        <div class="panel-body"> 
                        <div class="task-tags">
                            <?php if($popular_tags){
                                foreach($popular_tags as $t=>$tag){
                            ?>
                            <a href="<?php echo get_link('blogListURL');?>?tag=<?php echo $tag->name;?>"><span><?php echo $tag->name;?></span></a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                        </div>
                    </div>
					<div class="panel mb-4">
                    <div class="panel-header"><h4><?php echo __('projectview_view_share','Share');?></h4></div>
					<div class="panel-body"> 
						<!-- Share Buttons -->
						<div class="share-buttons">
							<div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
							<div class="share-buttons-content">
								<span><strong>Share It!</strong></span>
								<ul class="share-buttons-icons">
									<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $blogURL;?>" target="_blank" data-button-color="#3b5998" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>								
									<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $blogURL;?>&title=&summary=&source=" target="_blank" data-button-color="#0077b5" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
									<li><a href="mailto:?body=Check out this site <?php echo $blogURL;?>" target="_blank" data-button-color="#ea4335" title="Share on Mail" data-tippy-placement="top"><i class="icon-feather-mail"></i></a></li>
									<li><a href="https://twitter.com/home?status=<?php echo $blogURL;?>" target="_blank" data-button-color="#222" title="Share on Twitter" data-tippy-placement="top"><img src="<?php echo IMAGE;?>twitter-x-fill.svg" alt="X" height="20" width="20" /></a></li>
									<li><a href="https://www.instagram.com/regulatoryrisks=<?php echo $blogURL;?>" target="_blank" data-button-color="#1da1f2" title="Share on Instagram" data-tippy-placement="top"><i class="icon-brand-instagram"></i></a></li>
								</ul>
							</div>
						</div>                                        
					</div>
				</div>

					<!-- Widget -->
					 <?php /* if($poll){ ?>
						<div class="panel mt-4">
							<!-- <div class="panel-header"><h4></h4></div> -->
							<div class="panel-body"> 
								<?php echo html_entity_decode($poll['script']); ?>
							</div>
						</div>
					<?php } */?>

				</div>
			</div>

	</div>
</div>
</section>