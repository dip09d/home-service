<?php $blogURL=get_link('blogListURL');?>

<style>
.blog-hero {
    background: linear-gradient(135deg, #f0fdfa 0%, #e0f2fe 100%);
    padding: 5rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 4rem;
    border-radius: 0 0 40px 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
}
.blog-hero::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(43,170,177,0.15) 0%, rgba(255,255,255,0) 70%);
    top: -100px;
    left: -50px;
    border-radius: 50%;
}
.blog-hero-title {
    font-size: 3rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}
.blog-hero-title span {
    background: linear-gradient(135deg, #1e293b 0%, #2BAAB1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.blog-hero-subtitle {
    color: #64748b;
    font-size: 1.15rem;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.section-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 2rem;
}
.section-title span {
    color: #2BAAB1;
}

/* Premium Blog Cards */
.premium-blog-card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(43,170,177,0.08);
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none !important;
}
.premium-blog-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(43,170,177,0.12);
    border-color: rgba(43,170,177,0.2);
}
.blog-img-wrapper {
    position: relative;
    overflow: hidden;
    height: 220px;
}
.blog-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.premium-blog-card:hover .blog-img-wrapper img {
    transform: scale(1.08);
}
.premium-blog-content {
    padding: 1.75rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
.blog-meta {
    font-size: 0.85rem;
    color: #94a3b8;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.blog-meta i {
    color: #2BAAB1;
}
.premium-blog-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1rem;
    line-height: 1.4;
    transition: color 0.3s ease;
}
.premium-blog-card:hover .premium-blog-title {
    color: #2BAAB1;
}
.premium-blog-desc {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    flex-grow: 1;
}
.read-more-link {
    color: #2BAAB1;
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: gap 0.3s ease;
}
.premium-blog-card:hover .read-more-link {
    gap: 0.75rem;
    color: #1e293b;
}

/* Sidebar Styles */
.premium-sidebar-widget {
    background: #f8fafc;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(43,170,177,0.05);
}
.widget-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.5rem;
    position: relative;
    padding-bottom: 0.75rem;
}
.widget-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: #2BAAB1;
    border-radius: 2px;
}
.trending-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
    text-decoration: none !important;
    transition: transform 0.3s ease;
}
.trending-item:last-child {
    margin-bottom: 0;
}
.trending-item:hover {
    transform: translateX(5px);
}
.trending-img {
    width: 70px;
    height: 70px;
    border-radius: 12px;
    object-fit: cover;
}
.trending-text h5 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
    line-height: 1.3;
    transition: color 0.3s ease;
}
.trending-item:hover .trending-text h5 {
    color: #2BAAB1;
}
.trending-text span {
    font-size: 0.8rem;
    color: #94a3b8;
}

/* Search Box */
.premium-search {
    position: relative;
}
.premium-search input {
    width: 100%;
    padding: 1rem 1.5rem;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 10px 20px rgba(0,0,0,0.02);
    transition: all 0.3s ease;
}
.premium-search input:focus {
    outline: none;
    border-color: #2BAAB1;
    box-shadow: 0 0 0 4px rgba(43,170,177,0.1);
}
.premium-search button {
    position: absolute;
    right: 8px;
    top: 8px;
    bottom: 8px;
    background: #2BAAB1;
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0 1.25rem;
    transition: background 0.3s ease;
}
.premium-search button:hover {
    background: #22888E;
}

/* Tags */
.premium-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.premium-tags a {
    padding: 0.5rem 1rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    font-size: 0.85rem;
    color: #64748b;
    text-decoration: none !important;
    transition: all 0.3s ease;
}
.premium-tags a:hover {
    background: #2BAAB1;
    color: white;
    border-color: #2BAAB1;
    transform: translateY(-2px);
}
</style>

<div class="blog-hero">
    <div class="container">
        <h1 class="blog-hero-title">Our <span>Insights</span></h1>
        <p class="blog-hero-subtitle">Discover the latest tips, tricks, and updates on keeping your home spotless, safe, and beautifully maintained.</p>
    </div>
</div>

<section class="section mb-5">
	<div class="container">

        <!-- Featured Blogs Carousel -->
        <?php if($featured_blog){ ?>
        <div class="section-headline mb-4">
            <h2 class="section-title">Featured <span>Blogs</span></h2>
        </div>   
        <div class="owl-carousel owl-theme blog-carousel mb-5">
            <?php foreach($featured_blog as $key=>$val){ 
                $link = get_link('blogDetailsURL').'/'.$val->blog_slug;
                $image=NO_IMAGE;
                if($val->blog_thumb && file_exists(UPLOAD_PATH.'blog/thumb/'.$val->blog_thumb)){
                    $image=UPLOAD_HTTP_PATH.'blog/thumb/'.$val->blog_thumb;
                }
            ?>
            <div class="item pt-3 pb-3">
                <a href="<?php echo $link;?>" class="premium-blog-card">
                    <div class="blog-img-wrapper">
                        <img src="<?php echo $image;?>" alt="<?php echo $val->blog_title;?>">
                    </div>
                    <div class="premium-blog-content">
                        <div class="blog-meta">
                            <i class="ri-calendar-line"></i> <?php echo dateFormat($val->blog_reg_date,'M d, Y')?>
                        </div>
                        <h3 class="premium-blog-title"><?php echo $val->blog_title;?></h3>
                        <p class="premium-blog-desc"><?php echo $val->blog_short_description;?></p>
                        <div class="read-more-link">Read Article <i class="ri-arrow-right-line"></i></div>
                    </div>
                </a>
            </div>                  
            <?php } ?>            
        </div>
        <?php } ?>

		<div class="row g-5">
			<div class="col-xl-8 col-lg-8">
                
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h2 class="section-title mb-0">Recent <span>Posts</span></h2>
                    <form action="" style="min-width: 280px;">
                        <div class="premium-search">
                            <input type="text" value="<?php if($this->input->get('term')){echo $this->input->get('term');}?>" name="term" placeholder="Search insights...">
                            <button type="submit" title="Search"><i class="ri-search-line"></i></button>
                        </div>
                    </form>
                </div>

                <div class="row g-4">
                <?php if($list){
                    foreach($list as $key=>$val){
                        $link = get_link('blogDetailsURL').'/'.$val->blog_slug;
                        $image=NO_IMAGE;
                        if($val->blog_thumb && file_exists(UPLOAD_PATH.'blog/thumb/'.$val->blog_thumb)){
                            $image=UPLOAD_HTTP_PATH.'blog/thumb/'.$val->blog_thumb;
                        }
                ?>
                    <div class="col-md-6">
                        <a href="<?php echo $link; ?>" class="premium-blog-card">
                            <div class="blog-img-wrapper">
                                <img src="<?php echo $image;?>" alt="<?php echo $val->blog_title;?>">
                            </div>
                            <div class="premium-blog-content">		                        		
                                <div class="blog-meta">
                                    <i class="ri-calendar-line"></i> <?php D(dateFormat($val->blog_reg_date,'M d, Y'))?>
                                </div>
                                <h3 class="premium-blog-title"><?php echo $val->blog_title;?></h3>
                                <p class="premium-blog-desc"><?php echo $val->blog_short_description;?></p>
                                <div class="read-more-link">Read Article <i class="ri-arrow-right-line"></i></div>
                            </div>
                        </a>
                    </div>
                <?php } } else { ?>
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted"><?php echo __('no_blog_available','No Blog Available')?></h4>
                    </div>
                <?php } ?>
                </div>
                
                <div class="mt-4">
                    <?php echo $links; ?> 
                </div>
			</div>

            <!-- Sidebar -->
			<div class="col-xl-4 col-lg-4">            
				<div class="sidebar-container">                
                    
                    <div class="premium-sidebar-widget">
                        <h3 class="widget-title">Trending <span>Blogs</span></h3>
                        <div class="trending-list">
                            <?php if($tranding){
                                foreach($tranding as $key=>$val){
                                    $link = get_link('blogDetailsURL').'/'.$val->blog_slug;
                                    $image=NO_IMAGE;
                                    if($val->blog_thumb && file_exists(UPLOAD_PATH.'blog/thumb/'.$val->blog_thumb)){
                                        $image=UPLOAD_HTTP_PATH.'blog/thumb/'.$val->blog_thumb;
                                    }
                            ?>
                            <a href="<?php echo $link;?>" class="trending-item">
                                <img src="<?php echo $image;?>" alt="<?php echo $val->blog_title;?>" class="trending-img">
                                <div class="trending-text">
                                    <h5><?php echo $val->blog_title;?></h5>
                                    <span><i class="ri-calendar-line"></i> <?php D(dateFormat($val->blog_reg_date,'M d, Y'))?></span>
                                </div>
                            </a>
                            <?php } } ?>
                        </div>                                                        
                    </div>
                    
                    <div class="premium-sidebar-widget">
                        <h3 class="widget-title"><?php echo __('blog_','Tags');?></h3>
                        <div class="premium-tags">
                            <?php if($popular_tags){
                                foreach($popular_tags as $t=>$tag){
                            ?>
                            <a href="<?php echo get_link('blogListURL');?>?tag=<?php echo $tag->name;?>"><?php echo $tag->name;?></a>
                            <?php } } ?>
                        </div>
                    </div>
                    
                    <div class="premium-sidebar-widget">
                        <h3 class="widget-title"><?php echo __('projectview_view_share','Share');?></h3>
						<div class="social-links-grid d-flex gap-2">
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $blogURL;?>" target="_blank" title="Share on LinkedIn" class="btn btn-light rounded-circle" style="color: #0077b5;"><i class="ri-linkedin-fill"></i></a>
                            <a href="mailto:?body=Check out this site <?php echo $blogURL;?>" target="_blank" title="Share on Mail" class="btn btn-light rounded-circle" style="color: #ea4335;"><i class="ri-mail-send-line"></i></a>
                            <a href="https://twitter.com/home?status=<?php echo $blogURL;?>" target="_blank" title="Share on Twitter" class="btn btn-light rounded-circle" style="color: #1da1f2;"><i class="ri-twitter-fill"></i></a>
						</div>
                    </div>

				</div>
			</div>
  		</div>
	</div>
</section>

<script>
  var main = function(){
    if($('.blog-carousel').length){
        $('.blog-carousel').owlCarousel({
          dots: true,
          loop: true,
          margin: 20,
          responsiveClass: true,
          responsive: {
            0: { items: 1, nav: false },
            768: { items: 2, nav: false },
            992: { items: 3, loop: false, nav: true }
          }
        });
    }
  }
</script>