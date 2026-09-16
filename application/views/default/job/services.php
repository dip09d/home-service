<style>
.premium-service-card {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-align: center;
    padding: 3rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    text-decoration: none !important;
    border: 1px solid rgba(43, 170, 177, 0.08);
    z-index: 1;
    height: 100%;
}

.premium-service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(43, 170, 177, 0.03) 0%, rgba(43, 170, 177, 0) 100%);
    z-index: -1;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.premium-service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 48px rgba(43, 170, 177, 0.12);
    border-color: rgba(43, 170, 177, 0.4);
}

.premium-service-card:hover::before {
    opacity: 1;
}

.psc-icon-wrapper {
    width: 90px;
    height: 90px;
    background: #f8fafc;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 4px 6px rgba(0,0,0,0.02);
    transition: all 0.4s ease;
    transform: rotate(-3deg);
}

.premium-service-card:hover .psc-icon-wrapper {
    transform: scale(1.1) rotate(0deg);
    background: rgba(43, 170, 177, 0.1);
    box-shadow: 0 10px 20px rgba(43, 170, 177, 0.15);
}

.psc-icon-wrapper img {
    transition: transform 0.4s ease;
    object-fit: contain;
}

.premium-service-card:hover .psc-icon-wrapper img {
    transform: scale(1.05);
}

.psc-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    transition: color 0.3s ease;
}

.premium-service-card:hover .psc-title {
    color: #2BAAB1;
}

.psc-arrow {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #2BAAB1;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: translateY(15px);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    margin-top: auto;
    font-size: 1.25rem;
}

.premium-service-card:hover .psc-arrow {
    opacity: 1;
    transform: translateY(0);
}

.section-headline.centered h2 {
    font-weight: 800;
    font-size: 2.75rem;
    margin-bottom: 1rem;
}

.section-headline.centered h2 span {
    background: linear-gradient(135deg, #1e293b 0%, #2BAAB1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>

<section class="section">
	<div class="container">
		<div class="section-headline centered mb-5">
			<h2>Popular <span>Services</span></h2>
			<p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 1.1rem; line-height: 1.6;"><?php echo __('home_page_job_categories_p_tag', 'Discover trusted, top-rated professionals for all your home needs. Book seamlessly and get your chores done in no time. Explore our most popular services and enjoy a spotless home today.') ?></p>
		</div>

		<div class="row g-4">
		<?php
		if ($popular_category) {
			foreach ($popular_category as $k => $category) {

			$icon_thumb = $icon = NO_IMAGE;
			if ($category['category_icon'] && file_exists(UPLOAD_PATH . 'category_icons/' . $category['category_icon'])) {
				$icon = UPLOAD_HTTP_PATH . 'category_icons/' . $category['category_icon'];
			}
			if ($category['category_thumb'] && file_exists(UPLOAD_PATH . 'category_icons/thumb/' . $category['category_thumb'])) {
				$icon_thumb = UPLOAD_HTTP_PATH . 'category_icons/thumb/' . $category['category_thumb'];
			}
		?>
			<div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">				
				<a href="<?php echo URL::get_link('servicecategorydetailsUrl'); ?>/<?php echo $category['category_key']; ?>" class="premium-service-card">
					<div class="psc-icon-wrapper">
                        <img src="<?php echo $icon; ?>" alt="<?php echo $category['category_name']; ?>" height="48" width="48" />
                    </div>
                    <h3 class="psc-title"><?php echo $category['category_name']; ?></h3>
                    <div class="psc-arrow"><i class="ri-arrow-right-line"></i></div>
				</a>									
			</div>
			
		<?php
			}
		}
		?>
		</div>		

		<?php /*?>
		<div class="service-grid-container">
			<div class="row g-4">
				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">
						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>woman-wearing-chefs-apron.jpg" alt="Image" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Cooking worker</span>
								<p><i class="ri-star-fill text-warning"></i> 3.5</p>
							</div>
							<h4 class="card-title"><a href="">Chef for home made food</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $29</p>
						</div>
					</div>
				</article>

				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">

						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>physiotherapist-is-rehabilitating.jpg" alt="Image" class="card-img-top"></a>

						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Physiotherapist</span>
								<p><i class="ri-star-fill text-warning"></i> 4.5</p>
							</div>
							<h4 class="card-title"><a href="">Physiotherapist examining mans back</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $19</p>
						</div>
					</div>
				</article>

				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">

						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>woman-is-laying-bed-with-bottle-lotion.jpg" alt="Image" class="card-img-top"></a>

						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Body Massage</span>
								<p><i class="ri-star-fill text-warning"></i> 5.0</p>
							</div>
							<h4 class="card-title"><a href="">Woman is laying bed with bottle lotion</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $15</p>
						</div>
					</div>
				</article>

				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">
						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>cab-driver.jpg" alt="Image" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Driver</span>
								<p><i class="ri-star-fill text-warning"></i> 5.0</p>
							</div>
							<h4 class="card-title"><a href="">Driver for you personal car</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $15</p>
						</div>
					</div>
				</article>

				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">

						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>physiotherapist-is-rehabilitating.jpg" alt="Image" class="card-img-top"></a>

						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Physiotherapist</span>
								<p><i class="ri-star-fill text-warning"></i> 4.5</p>
							</div>
							<h4 class="card-title"><a href="">Physiotherapist examining mans back</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $19</p>
						</div>
					</div>
				</article>

				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">
						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>cab-driver.jpg" alt="Image" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Driver</span>
								<p><i class="ri-star-fill text-warning"></i> 5.0</p>
							</div>
							<h4 class="card-title"><a href="">Driver for you personal car</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $15</p>
						</div>
					</div>
				</article>

				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">
						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>woman-wearing-chefs-apron.jpg" alt="Image" class="card-img-top"></a>
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Cooking worker</span>
								<p><i class="ri-star-fill text-warning"></i> 3.5</p>
							</div>
							<h4 class="card-title"><a href="">Chef for home made food</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $29</p>
						</div>
					</div>
				</article>

				<article class="col-xl-3 col-lg-4 col-sm-6">
					<div class="card border-0 shadow-sm card-featured">

						<a class="card-image" href=""><img src="<?php echo IMAGE; ?>woman-is-laying-bed-with-bottle-lotion.jpg" alt="Image" class="card-img-top"></a>

						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Body Massage</span>
								<p><i class="ri-star-fill text-warning"></i> 5.0</p>
							</div>
							<h4 class="card-title"><a href="">Woman is laying bed with bottle lotion</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $15</p>
						</div>
					</div>
				</article>
			</div>
			<div class="text-center" style="margin: 100px"><?php load_view('inc/spinner', array('size' => 30)); ?></div>
		</div>

		<div class="service-list-container">
			<div class="row g-4">
			<aside class="col-12">
				<div class="card border-0 shadow-sm card-featured">
					<div class="row align-items-center g-0">
						<article class="col-xl-3 col-lg-4">

							<a class="card-image" href=""><img src="<?php echo IMAGE; ?>woman-wearing-chefs-apron.jpg" alt="Image" class="card-img-start"></a>

						</article>
						<article class="col-xl-9 col-lg-8">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<span class="badge bg-warning-subtle text-warning">Cooking worker</span>
									<p><i class="ri-star-fill text-warning"></i> 3.5</p>
								</div>
								<h4 class="card-title"><a href="">Chef for home made food</a></h4>
								<p>Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
								<p class="text-muted">Services start at $29</p>
							</div>
						</article>
					</div>
				</div>
			</aside>
			<aside class="col-12">
				<div class="card border-0 shadow-sm card-featured">
					<div class="row align-items-center g-0">
						<article class="col-xl-3 col-lg-4">
							<a class="card-image" href=""><img src="<?php echo IMAGE; ?>physiotherapist-is-rehabilitating.jpg" alt="Image" class="card-img-start"></a>
						</article>
						<article class="col-xl-9 col-lg-8">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<span class="badge bg-warning-subtle text-warning">Physiotherapist</span>
									<p><i class="ri-star-fill text-warning"></i> 4.5</p>
								</div>
								<h4 class="card-title"><a href="">Physiotherapist examining mans back</a></h4>
								<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
								<p class="text-muted">Services start at $19</p>
							</div>
						</article>
					</div>
				</div>
			</aside>
			<aside class="col-12">
				<div class="card border-0 shadow-sm card-featured">
					<div class="row align-items-center g-0">
						<article class="col-xl-3 col-lg-4">
							<a class="card-image" href=""><img src="<?php echo IMAGE; ?>woman-is-laying-bed-with-bottle-lotion.jpg" alt="Image" class="card-img-start"></a>
						</article>
						<article class="col-xl-9 col-lg-8">
							<div class="card-body">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="badge bg-warning-subtle text-warning">Body Massage</span>
								<p><i class="ri-star-fill text-warning"></i> 5.0</p>
							</div>
							<h4 class="card-title"><a href="">Woman is laying bed with bottle lotion</a></h4>
							<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
							<p class="text-muted">Services start at $15</p>
						</div>
						</article>
					</div>
				</div>
			</aside>
			<aside class="col-12">
				<div class="card border-0 shadow-sm card-featured">
					<div class="row align-items-center g-0">
						<article class="col-xl-3 col-lg-4">
							<a class="card-image" href=""><img src="<?php echo IMAGE; ?>cab-driver.jpg" alt="Image" class="card-img-start"></a>
						</article>
						<article class="col-xl-9 col-lg-8">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center mb-2">
									<span class="badge bg-warning-subtle text-warning">Driver</span>
									<p><i class="ri-star-fill text-warning"></i> 5.0</p>
								</div>
								<h4 class="card-title"><a href="">Driver for you personal car</a></h4>
								<p>Lorem ipsum is simply dummy text of the printing and typesetting industry.</p>
								<p class="text-muted">Services start at $15</p>
							</div>
						</article>
					</div>
				</div>
			</aside>
			
			</div>
		</div>
		<?php */?>
		
	</div>
</section>