<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>
<div id="dataStep-1" style="display: nones">
				<!-- Dashboard Box -->
                <div class="dashboard-box margin-top-0">
                    <!-- Headline -->
                    <div class="headline">
                        <h3> Title </h3>
                    </div>
                    <div class="content with-padding">
                        <label>Name of your project</label>
                        <input type="text"  class="form-control" name="title" id="title">
                        <span id="titleError" class="rerror"></span>
                    </div>
                </div>
				
					
                <div class="dashboard-box">
						<!-- Headline -->
						<div class="headline">
							<h3> Category </h3>
						</div>
						<div class="content with-padding">
								<div class="form-group">
									<select name="category" id="category" class="form-control" title="Category">
									<option value="" >-Select Category-</option>
					            	<?php
				            		if($all_category){
										foreach($all_category as $category_list){
											?>
											<option value="<?php D($category_list->category_id);?>" ><?php D(ucfirst($category_list->category_name));?></option>
											<?php
										}
									}
				            		 ?>
				            		</select>
								
								<span id="categoryError" class="rerror"></span>
                                </div>
							
							<div class="form-group">
									<div id="load_sub_category">
										<select name="sub_category" id="sub_category" data-size="4" class="form-control" title="Sub category">
											<option value="">-Select Sub Category-</option>
										</select>
									</div>
								<span id="sub_categoryError" class="rerror"></span>
							</div>
                        	
						</div>
                        <div class="dashboard-box-footer">
                            <!--<button class="btn btn-secondary backbtnproject" data-step="1">Back</button>-->
                            <button class="btn btn-site nextbtnproject" data-step="1">Next</button>
                        </div>
						
					</div>
</div>