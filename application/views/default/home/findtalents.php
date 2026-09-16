<div class="margin-top-50"></div>
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-12">
			<div class="sidebar-container">

				<!-- Location -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_location', 'Location'); ?></h3>
					<div class="input-with-icon">
						<div id="autocomplete-container">
							<input type="text" class="form-control" id="autocomplete-input" placeholder="Location">
						</div>
						<i class="icon-feather-map-pin"></i>
					</div>
				</div>

				<!-- Category -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_category', 'Category'); ?></h3>
					<select class="form-control selectpicker default" multiple data-selected-text-format="count" data-size="7" title="All Categories">
						<option><?php echo __('home_page_findjob_A_support', 'Admin Support'); ?></option>
						<option><?php echo __('home_page_findjob_C_service', 'Customer Service'); ?></option>
						<option><?php echo __('home_page_findjob_D_analytics', 'Data Analytics'); ?></option>
						<option><?php echo __('home_page_findjob_D_creative', 'Design & Creative'); ?></option>
						<option><?php echo __('home_page_findjob_legal', 'Legal'); ?></option>
						<option><?php echo __('home_page_findjob_S_developing', 'Software Developing'); ?></option>
						<option><?php echo __('home_page_findjob_It', 'IT & Networking'); ?></option>
						<option><?php echo __('home_page_findjob_writing', 'Writing'); ?></option>
						<option><?php echo __('home_page_findjob_translation', 'Translation'); ?></option>
						<option><?php echo __('home_page_findjob_S_marketing', 'Sales & Marketing'); ?></option>
					</select>
				</div>

				<!-- Keywords -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_keywords', 'Keywords'); ?></h3>
					<div class="keywords-container">
						<div class="keyword-input-container">
							<input type="text" class="form-control keyword-input" placeholder="e.g. task title" />
							<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
						</div>
						<div class="keywords-list">
							<!-- keywords go here -->
						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<!-- Hourly Rate -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_H_rate', 'Hourly Rate'); ?></h3>
					<div class="margin-top-55"></div>

					<!-- Range Slider -->
					<input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="10" data-slider-max="250" data-slider-step="5" data-slider-value="[10,250]" />
				</div>

				<!-- Tags -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findtalent_skill', 'Skills'); ?></h3>

					<div class="tags-container">
						<div class="tag">
							<input type="checkbox" id="tag1" />
							<label for="tag1"><?php echo __('home_page_findjob_front_dev', 'front-end dev'); ?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag2" />
							<label for="tag2"><?php echo __('home_page_findjob_angular', 'angular'); ?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag3" />
							<label for="tag3"><?php echo __('home_page_findjob_react', 'react'); ?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag4" />
							<label for="tag4"><?php echo __('home_page_findjob_vue', 'vue js'); ?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag5" />
							<label for="tag5"><?php echo __('home_page_findjob_web_app', 'web apps'); ?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag6" />
							<label for="tag6"><?php echo __('home_page_findjob_design', 'design'); ?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag7" />
							<label for="tag7"><?php echo __('home_page_findjob_wordpress', 'wordpress'); ?></label>
						</div>
					</div>
					<div class="clearfix"></div>

					<!-- More Skills -->
					<div class="keywords-container margin-top-20">
						<div class="keyword-input-container">
							<input type="text" class="form-control keyword-input" placeholder="add more skills" />
							<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
						</div>
						<div class="keywords-list">
							<!-- keywords go here -->
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="clearfix"></div>

			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-12">

			<h3 class="page-title"><?php echo __('home_page_findjob_S_result', 'Search Results'); ?></h3>
			<div class="sort-by mb-2">
				<span><?php echo __('home_page_findtalent_sort_by', 'Sort by:'); ?></span>
				<select class="selectpicker hide-tick">
					<option><?php echo __('home_page_findjob_relevance', 'Relevance'); ?></option>
					<option><?php echo __('home_page_findjob_newest', 'Newest'); ?></option>
					<option><?php echo __('home_page_findjob_oldest', 'Oldest'); ?></option>
					<option><?php echo __('home_page_findjob_random', 'Random'); ?></option>
				</select>
			</div>

			<div class="search-box input-group mt-3">
				<input type="text" class="form-control" placeholder="Find talents by name" />
				<button type="button" class="btn btn-primary"><?php echo __('home_page_findjob_search', 'Search'); ?></button>
			</div>



			<!-- Freelancers List Container -->
			<div class="listings-container mt-4">

				<!-- Freelancer -->
				<div class="job-listing">

					<!-- Job Listing Details -->
					<div class="job-listing-details">
						<!-- Logo -->
						<div class="job-listing-company-logo">
							<a href="#"><img src="<?php echo IMAGE; ?>user-avatar-big-01.jpg" alt="">
								<span class="verified-badge"></span></a>
						</div>

						<!-- Details -->
						<div class="job-listing-description">
							<div class="freelancer-about">
								<div class="freelancer-intro">
									<h3 class="job-listing-title"><a href="#"><?php echo __('home_page_findtalent_david_peterson', 'David Peterson'); ?></a></h3>
									<span class="text-muted"><?php echo __('home_page_findtalent_ios_expert', 'iOS Expert + Node Dev'); ?></span>
									<div class="freelancer-rating">
										<div class="star-rating" data-rating="4.5"></div>
									</div>
								</div>

								<div class="freelancer-details-list">
									<ul>
										<li><?php echo __('home_page_findjob_location', 'Location'); ?> <strong><i class="icon-feather-map-pin"></i> <?php echo __(''); ?>London</strong></li>
										<li><?php echo __('home_page_findtalent_rate', 'Rate'); ?> <strong>$60 / hr</strong></li>
										<li><?php echo __('home_page_findtalent_job_success', 'Job Success'); ?> <strong>95%</strong></li>
									</ul>
								</div>

							</div>

							<p class="job-listing-text"><?php echo __('home_page_findtalent_leverge', 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value.'); ?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findtalent_accounting', 'Accounting'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_analytics', 'Analytics'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_licening', 'Brand Licensing'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_development', 'Business Development'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_F_management', 'Financial Management'); ?></a>
							</div>
						</div>
					</div>
				</div>

				<!-- Freelancer -->
				<div class="job-listing">

					<!-- Job Listing Details -->
					<div class="job-listing-details">
						<!-- Logo -->
						<div class="job-listing-company-logo">
							<a href="#"><img src="<?php echo IMAGE; ?>user-avatar-big-01.jpg" alt="">
								<span class="verified-badge"></span></a>
						</div>

						<!-- Details -->
						<div class="job-listing-description">
							<div class="freelancer-about">
								<div class="freelancer-intro">
									<h3 class="job-listing-title"><a href="#"><?php echo __('home_page_findtalent_david_peterson', 'David Peterson'); ?></a></h3>
									<span class="text-muted"><?php echo __('home_page_findtalent_ios_expert', 'iOS Expert + Node Dev'); ?></span>
									<div class="freelancer-rating">
										<div class="star-rating" data-rating="4.5"></div>
									</div>
								</div>

								<div class="freelancer-details-list">
									<ul>
										<li><?php echo __('home_page_findjob_location', 'Location'); ?> <strong><i class="icon-feather-map-pin"></i><?php echo __('home_page_findtalent_london', 'London'); ?> </strong></li>
										<li><?php echo __('home_page_findtalent_rate', 'Rate'); ?> <strong>$60 / hr</strong></li>
										<li><?php echo __('home_page_findtalent_job_success', 'Job Success'); ?> <strong>95%</strong></li>
									</ul>
								</div>

							</div>

							<p class="job-listing-text"><?php echo __('home_page_findtalent_leverge', 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value.'); ?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findtalent_accounting', 'Accounting'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_analytics', 'Analytics'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_licening', 'Brand Licensing'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_development', 'Business Development'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_F_management', 'Financial Management'); ?></a>
							</div>
						</div>
					</div>
				</div>

				<!-- Freelancer -->
				<div class="job-listing">

					<!-- Job Listing Details -->
					<div class="job-listing-details">
						<!-- Logo -->
						<div class="job-listing-company-logo">
							<a href="#"><img src="<?php echo IMAGE; ?>user-avatar-big-01.jpg" alt="">
								<span class="verified-badge"></span></a>
						</div>

						<!-- Details -->
						<div class="job-listing-description">
							<div class="freelancer-about">
								<div class="freelancer-intro">
									<h3 class="job-listing-title"><a href="#"><?php echo __('home_page_findtalent_david_peterson', 'David Peterson'); ?></a></h3>
									<span class="text-muted"><?php echo __('home_page_findtalent_ios_expert', 'iOS Expert + Node Dev'); ?></span>
									<div class="freelancer-rating">
										<div class="star-rating" data-rating="4.5"></div>
									</div>
								</div>

								<div class="freelancer-details-list">
									<ul>
										<li><?php echo __('home_page_findjob_location', 'Location'); ?> <strong><i class="icon-feather-map-pin"></i><?php echo __('home_page_findtalent_london', 'London'); ?> </strong></li>
										<li><?php echo __('home_page_findtalent_rate', 'Rate'); ?> <strong>$60 / hr</strong></li>
										<li><?php echo __('home_page_findtalent_job_success', 'Job Success'); ?> <strong>95%</strong></li>
									</ul>
								</div>

							</div>

							<p class="job-listing-text"><?php echo __('home_page_findtalent_leverge', 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value.'); ?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findtalent_accounting', 'Accounting'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_analytics', 'Analytics'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_licening', 'Brand Licensing'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_development', 'Business Development'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_F_management', 'Financial Management'); ?></a>
							</div>
						</div>
					</div>
				</div>

				<!-- Freelancer -->
				<div class="job-listing">

					<!-- Job Listing Details -->
					<div class="job-listing-details">
						<!-- Logo -->
						<div class="job-listing-company-logo">
							<a href="#"><img src="<?php echo IMAGE; ?>user-avatar-big-01.jpg" alt="">
								<span class="verified-badge"></span></a>
						</div>

						<!-- Details -->
						<div class="job-listing-description">
							<div class="freelancer-about">
								<div class="freelancer-intro">
									<h3 class="job-listing-title"><a href="#"><?php echo __('home_page_findtalent_david_peterson','David Peterson'); ?></a></h3>
									<span class="text-muted"><?php echo __('home_page_findtalent_ios_expert','iOS Expert + Node Dev'); ?></span>
									<div class="freelancer-rating">
										<div class="star-rating" data-rating="4.5"></div>
									</div>
								</div>

								<div class="freelancer-details-list">
									<ul>
										<li><?php echo __('home_page_findjob_location','Location'); ?> <strong><i class="icon-feather-map-pin"></i> <?php echo __('home_page_findtalent_london','London'); ?></strong></li>
										<li><?php echo __('home_page_findtalent_rate','Rate'); ?> <strong>$60 / hr</strong></li>
										<li><?php echo __('home_page_findtalent_job_success','Job Success'); ?> <strong>95%</strong></li>
									</ul>
								</div>

							</div>

							<p class="job-listing-text"><?php echo __('home_page_findtalent_leverge','Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value.'); ?></p>
							<div class="task-tags">
							<a href="#"><?php echo __('home_page_findtalent_accounting', 'Accounting'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_analytics', 'Analytics'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_licening', 'Brand Licensing'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_B_development', 'Business Development'); ?></a>
								<a href="#"><?php echo __('home_page_findtalent_F_management', 'Financial Management'); ?></a>
							</div>
							</div>
						</div>
					</div>
				</div>


			</div>
			<!-- Freelancers List Container / End -->

			<!-- Pagination -->
			<div class="pagination-container margin-top-40 margin-bottom-60">
				<nav class="pagination">
					<ul>
						<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
						<li><a href="#" class="ripple-effect">1</a></li>
						<li><a href="#" class="current-page ripple-effect">2</a></li>
						<li><a href="#" class="ripple-effect">3</a></li>
						<li><a href="#" class="ripple-effect">4</a></li>
						<li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
					</ul>
				</nav>
			</div>
			<!-- Pagination / End -->

		</div>
	</div>
</div>