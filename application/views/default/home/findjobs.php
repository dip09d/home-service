<div class="margin-top-50"></div>
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-12">
			<div class="sidebar-container">
				
				<!-- Location -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_location','Location');?></h3>
					<div class="input-with-icon">
						<div id="autocomplete-container">
							<input type="text" class="form-control" id="autocomplete-input" placeholder="Location">
						</div>
						<i class="icon-material-outline-location-on"></i>
					</div>
				</div>

				
				<!-- Keywords -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_keywords','Keywords');?></h3>
					<div class="keywords-container">
						<div class="keyword-input-container">
							<input type="text" class="form-control keyword-input" placeholder="e.g. job title"/>
							<button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
						</div>
						<div class="keywords-list"><!-- keywords go here --></div>
						<div class="clearfix"></div>
					</div>
				</div>
				
				<!-- Category -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_category','Category');?></h3>
					<select class="form-control selectpicker default" multiple data-selected-text-format="count" data-size="7" title="All Categories" >
						<option><?php echo __('home_page_findjob_A_support','Admin Support');?></option>
						<option><?php echo __('home_page_findjob_C_service','Customer Service');?></option>
						<option><?php echo __('home_page_findjob_D_analytics','Data Analytics');?></option>
						<option><?php echo __('home_page_findjob_D_creative','Design & Creative');?></option>
						<option><?php echo __('home_page_findjob_legal','Legal');?></option>
						<option><?php echo __('home_page_findjob_S_developing','Software Developing');?></option>
						<option><?php echo __('home_page_findjob_It','IT & Networking');?></option>
						<option><?php echo __('home_page_findjob_writing','Writing');?></option>
						<option><?php echo __('home_page_findjob_translation','Translation');?></option>
						<option><?php echo __('home_page_findjob_S_marketing','Sales & Marketing');?></option>
					</select>
				</div>
				
				<!-- Job Types -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_job_type','Job Type');?></h3>

					<div class="switches-list">
						<div class="switch-container">
							<label class="switch"><input type="checkbox"><span class="switch-button"></span> <?php echo __('home_page_findjob_freelance','Freelance');?></label>
						</div>

						<div class="switch-container">
							<label class="switch"><input type="checkbox"><span class="switch-button"></span> <?php echo __('home_page_findjob_fulltime','Full Time');?></label>
						</div>

						<div class="switch-container">
							<label class="switch"><input type="checkbox"><span class="switch-button"></span> <?php echo __('home_page_findjob_parttime','Part Time');?></label>
						</div>

						<div class="switch-container">
							<label class="switch"><input type="checkbox"><span class="switch-button"></span> <?php echo __('home_page_findjob_internship','Internship');?></label>
						</div>
						<div class="switch-container">
							<label class="switch"><input type="checkbox"><span class="switch-button"></span> <?php echo __('home_page_findjob_temporary','Temporary');?></label>
						</div>
					</div>

				</div>

				<!-- Salary -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_contact_salary','Salary');?></h3>
					<div class="margin-top-55"></div>

					<!-- Range Slider -->
					<input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="1500" data-slider-max="15000" data-slider-step="100" data-slider-value="[1500,15000]"/>
				</div>

				<!-- Tags -->
				<div class="sidebar-widget">
					<h3><?php echo __('home_page_findjob_tags','Tags');?></h3>

					<div class="tags-container">
						<div class="tag">
							<input type="checkbox" id="tag1"/>
							<label for="tag1"><?php echo __('home_page_findjob_front_dev','front-end dev');?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag2"/>
							<label for="tag2"><?php echo __('home_page_findjob_angular','angular');?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag3"/>
							<label for="tag3"><?php echo __('home_page_findjob_react','react');?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag4"/>
							<label for="tag4"><?php echo __('home_page_findjob_vue','vue js');?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag5"/>
							<label for="tag5"><?php echo __('home_page_findjob_web_app','web apps');?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag6"/>
							<label for="tag6"><?php echo __('home_page_findjob_design','design');?></label>
						</div>
						<div class="tag">
							<input type="checkbox" id="tag7"/>
							<label for="tag7"><?php echo __('home_page_findjob_wordpress','wordpress');?></label>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>

			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-12">

			<h3 class="page-title"><?php echo __('home_page_findjob_S_result','Search Results');?></h3>

        	<div class="sort-by mb-2">
                <span>Sort by:</span>
                <select class="selectpicker hide-tick">
                    <option><?php echo __('home_page_findjob_relevance','Relevance');?></option>
                    <option><?php echo __('home_page_findjob_newest','Newest');?></option>
                    <option><?php echo __('home_page_findjob_oldest','Oldest');?></option>
                    <option><?php echo __('home_page_findjob_random','Random');?></option>
                </select>
            </div>
            <div class="search-box input-group margin-top-15">
				<input type="text" class="form-control" placeholder="Find jobs by title" />
                <button type="button" class="btn btn-primary"><?php echo __('home_page_findjob_search','Search');?></button>
			</div>
            

			<!-- Tasks Container -->
			<div class="tasks-list-container margin-top-35">
				
				<!-- Task -->
				<div class="task-listing">
                	<div class="task-listing-body">
					<!-- Job Listing Details -->
					<div class="task-listing-details">

						<!-- Details -->
						<div class="task-listing-description">
							<h3 class="task-listing-title"><a href="#"><?php echo __('home_page_findjob_food_delivery','Food Delviery Mobile App');?></a></h3>							
							<p class="task-listing-text"><?php echo __('home_page_findjob_high_level','Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster.');?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findjob_ios','iOS');?></a>
								<a href="#"><?php echo __('home_page_findjob_android','Android');?></a>
								<a href="#"><?php echo __('home_page_findjob_M_apps','mobile apps');?></a>
								<a href="#"><?php echo __('home_page_findjob_design','design');?></a>
							</div>
						</div>

					</div>

					<div class="task-listing-bid">
						<div class="task-listing-bid-inner">
							<div class="task-offers">
								<h4><b>$1,000 - $2,500</b></h4>
								<span><?php echo __('home_page_findjob_F_price','Fixed Price');?></span>
							</div>
							<span class="btn btn-primary"><?php echo __('home_page_findjob_bid_now','Bid Now');?> </span>
						</div>
					</div>
                    </div>
                    <div class="task-listing-footer">
						<ul>
							<li><i class="icon-material-outline-location-on"></i> <?php echo __('home_page_findjob_san_franc','San Francissco');?></li>
							<li><i class="icon-material-outline-business-center"></i> <?php echo __('home_page_findjob_full_time','Full Time');?></li>
							<li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
							<li><i class="icon-material-outline-access-time"></i> <?php echo __('home_page_findjob_D_ago','2 days ago');?></li>
						</ul>
					</div>
				</div>

				<!-- Task -->
				<div class="task-listing">
                	<div class="task-listing-body">
					<!-- Job Listing Details -->
					<div class="task-listing-details">

						<!-- Details -->
						<div class="task-listing-description">
							<h3 class="task-listing-title"><a href="#"><?php echo __('home_page_findjob_E_to_ger','2000 Words English to German');?></a></h3>							
							<p class="task-listing-text"><?php echo __('home_page_findjob_rea_time','Bring to the table win-win strategies to ensure domination and user generated content in real-time will have multiple touchpoints.');?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findjob_copywriting','copywriting');?></a>
								<a href="#"><?php echo __('home_page_findjob_translating','translating');?></a>
								<a href="#"><?php echo __('home_page_findjob_editing','editing');?></a>
							</div>
						</div>

					</div>

					<div class="task-listing-bid">
						<div class="task-listing-bid-inner">
							<div class="task-offers">
								<h4><b>$75</b></h4>
								<span><?php echo __('home_page_findjob_F_price','Fixed Price');?></span>
							</div>
							<a href="#" class="btn btn-primary"><?php echo __('home_page_findjob_bid_now','Bid Now');?> </a>
						</div>
					</div>
                    </div>
                    <div class="task-listing-footer">
						<ul>
							<li><i class="icon-material-outline-location-on"></i> <?php echo __('home_page_findjob_san_franc','San Francissco');?></li>
							<li><i class="icon-material-outline-business-center"></i> <?php echo __('home_page_findjob_full_time','Full Time');?></li>
							<li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
							<li><i class="icon-material-outline-access-time"></i><?php echo __('home_page_findjob_D_ago','2 days ago');?> </li>
						</ul>
					</div>
				</div>

				<!-- Task -->
				<div class="task-listing">
					<div class="task-listing-body">
					<!-- Job Listing Details -->                    
					<div class="task-listing-details">

						<!-- Details -->
						<div class="task-listing-description">
							<h3 class="task-listing-title"><a href="#"><?php echo __('home_page_findjob_fix_python','Fix Python Selenium Code');?></a></h3>							
							<p class="task-listing-text"><?php echo __('home_page_findjob_capitalize','Capitalize on low hanging fruit to identify a ballpark value added activity to beta test. Override the digital divide with additional.');?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findjob_python','Python');?></a>
								<a href="#"><?php echo __('home_page_findjob_flask','Flask');?></a>
								<a href="#"><?php echo __('home_page_findjob_api','API Development');?></a>
							</div>
						</div>

					</div>

					<div class="task-listing-bid">
						<div class="task-listing-bid-inner">
							<div class="task-offers">
								<h4><b>$100 - $150</b></h4>
								<span><?php echo __('home_page_findjob_H_rate','Hourly Rate');?></span>
							</div>
							<a href="#" class="btn btn-primary"><?php echo __('home_page_findjob_bid_now','Bid Now');?> </a>
						</div>
					</div>
                    </div>
                    <div class="task-listing-footer">
						<ul>
							<li><i class="icon-material-outline-location-on"></i> <?php echo __('home_page_findjob_san_franc','San Francissco');?></li>
							<li><i class="icon-material-outline-business-center"></i><?php echo __('home_page_findjob_full_time','Full Time');?> </li>
							<li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
							<li><i class="icon-material-outline-access-time"></i> <?php echo __('home_page_findjob_D_ago','2 days ago');?></li>
						</ul>
					</div>
				</div>

				<!-- Task -->
				<div class="task-listing">
                	<div class="task-listing-body">
					<!-- Job Listing Details -->
					<div class="task-listing-details">

						<!-- Details -->
						<div class="task-listing-description">
							<h3 class="task-listing-title"><a href="#"><?php echo __('home_page_findjob_Word_install','WordPress Theme Installation');?></a></h3>							
							<p class="task-listing-text"><?php echo __('home_page_findjob_relationship','Completely synergize resource taxing relationships via premier niche markets. Professionally cultivate customer service with robust ideas.');?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findjob_wordpress','WordPress');?></a>
								<a href="#"><?php echo __('home_page_findjob_T_install','Theme Installation');?></a>
							</div>
						</div>

					</div>

					<div class="task-listing-bid">
						<div class="task-listing-bid-inner">
							<div class="task-offers">
								<h4><b>$100</b></h4>
								<span><?php echo __('home_page_findjob_F_price','Fixed Price');?></span>
							</div>
							<a href="#" class="btn btn-primary"><?php echo __('home_page_findjob_bid_now','Bid Now');?> </a>
						</div>
					</div>
                    </div>
                    <div class="task-listing-footer">
						<ul>
							<li><i class="icon-material-outline-location-on"></i> <?php echo __('home_page_findjob_san_franc','San Francissco');?></li>
							<li><i class="icon-material-outline-business-center"></i> <?php echo __('home_page_findjob_full_time','Full Time');?></li>
							<li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
							<li><i class="icon-material-outline-access-time"></i> <?php echo __('home_page_findjob_D_ago','2 days ago');?></li>
						</ul>
					</div>
				</div>

				<!-- Task -->
				<div class="task-listing">
                	<div class="task-listing-body">
					<!-- Job Listing Details -->
					<div class="task-listing-details">

						<!-- Details -->
						<div class="task-listing-description">
							<h3 class="task-listing-title"><a href="#"><?php echo __('home_page_findjob_php_core','PHP Core Website Fixes');?></a></h3>							
							<p class="task-listing-text"><?php echo __('home_page_findjob_objectively','Objectively innovate empowered manufactured products whereas parallel platforms. Extensible testing procedures for reliable supply.');?></p>
							<div class="task-tags">
								<a href="#"><?php echo __('home_page_findjob_php','PHP');?></a>
								<a href="#"><?php echo __('home_page_findjob_mysql_admin','MySQL Administration');?></a>
								<a href="#"><?php echo __('home_page_findjob_api_develop','API Development');?></a>
							</div>
						</div>

					</div>

					<div class="task-listing-bid">
						<div class="task-listing-bid-inner">
							<div class="task-offers">
								<h4><b>$50 - $80</b></h4>
								<span><?php echo __('home_page_findjob_H_rate','Hourly Rate');?></span>
							</div>
							<a href="#" class="btn btn-primary"><?php echo __('home_page_findjob_bid_now','Bid Now');?> </a>
						</div>
					</div>
                    </div>
                    <div class="task-listing-footer">
						<ul>
							<li><i class="icon-material-outline-location-on"></i><?php echo __('home_page_findjob_san_franc','San Francissco');?> </li>
							<li><i class="icon-material-outline-business-center"></i><?php echo __('home_page_findjob_full_time','Full Time');?> </li>
							<li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
							<li><i class="icon-material-outline-access-time"></i> <?php echo __('home_page_findjob_D_ago','2 days ago');?></li>
						</ul>
					</div>
				</div>
				
				<!-- Pagination -->
                <div class="pagination-container margin-top-30 margin-bottom-60">
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
			<!-- Tasks Container / End -->

		</div>
	</div>
</div>