<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>

<div id="dataStep-7" style="display: none">
  <div class="dashboard-box margin-top-0"> 
    <!-- Headline -->
    <div class="headline">
      <h3> Review and Post </h3>
    </div>
    <div class="content with-padding">
        <div class="submit-field">
          <h5><b>Title</b> <a href="javascript:void(0)" class="edit-project float-right btn btn-secondary btn-circle" data-popup="1" data-tippy-placement="top" title="Edit title"><i class="icon-feather-edit"></i></a></h5>
          <h5 class="margin-bottom-0">Name of your project</h5>
          <p id="preview_title"></p>
          <h5 class="margin-bottom-0">Project category</h5>
          <p id="preview_category"></p>
        </div>

    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
      <h4><b>Description</b> <a href="javascript:void(0)" class="edit-project float-right btn btn-secondary btn-circle" data-popup="2" data-tippy-placement="top" title="Edit Description"><i class="icon-feather-edit"></i></a></h4>
    </div>
    <div class="content with-padding">
      <div class="submit-field">        
        <h5 class="margin-bottom-0">Description</h5>
        <p id="preview_description">test check</p>
        <div id="preview_attachment_sec" style="display: none">
          <h5 class="margin-bottom-0">Attachment</h5>
          <p id="preview_attachment"></p>
        </div>
      </div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    <h4><b>Details</b> <a href="javascript:void(0)" class="edit-project float-right btn btn-secondary btn-circle" data-popup="3" data-tippy-placement="top" title="Edit Details"><i class="icon-feather-edit"></i></a></h4>
    </div>
    <div class="content with-padding">
      <div class="submit-field">        
        <h5 class="margin-bottom-0">Type of project</h5>
        <p id="preview_projectType"></p>
        <div id="preview_attachment_sec" style="display: none">
          <h5 class="margin-bottom-0">Attachment</h5>
          <p id="preview_attachment"></p>
        </div>
      </div>
      <div class="submit-field">
        <h5><b>Screen question (optional)</b> </h5>
        <div id="preview_question_sec" style="display: none"></div>
        <h5 class="margin-bottom-0">Required cover letter</h5>
        <p id="preview_is_cover_required"></p>
      </div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    	<h4><b>Expertise </b> <a href="javascript:void(0)" class="edit-project float-right btn btn-secondary btn-circle" data-popup="4" data-tippy-placement="top" title="Edit Expertise"><i class="icon-feather-edit"></i></a></h4>
    </div>
    <div class="content with-padding">
		<div class="task-tags" id="preview_skills"></div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    	<h4><b>Visibility </b> <a href="javascript:void(0)" class="edit-project float-right btn btn-secondary btn-circle" data-popup="5" data-tippy-placement="top" title="Edit Visibility"><i class="icon-feather-edit"></i></a></h4>
    </div>
    <div class="content with-padding">
      <div class="submit-field">        
        <h5>Project visibility</h5>
        <p id="preview_projectVisibility"></p>
        <hr />
        <h5>Freelancer need</h5>
        <p id="preview_no_of_freelancer"></p>
      </div>
    </div>
  </div>
  <div class="dashboard-box"> 
    <!-- Headline -->
    <div class="headline">
    <h4><b>Budget </b> <a href="javascript:void(0)" class="edit-project float-right btn btn-secondary btn-circle" data-popup="6" data-tippy-placement="top" title="Edit Budget"><i class="icon-feather-edit"></i></a></h4>
    </div>
    <div class="content with-padding padding-bottom-0">      
      <div class="row">
        <div class="col-xl-6">
          <div class="submit-field">
            <h5 class="margin-bottom-0">Hourly or Fixed price</h5>
            <p id="preview_projectPaymentType"></p>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="submit-field">
            <h5 class="margin-bottom-0">Experience level</h5>
            <p id="preview_experience_level"></p>
          </div>
        </div>
      </div>
      <div class="row hourly_project_display" style="display: none">
        <div class="col-xl-6">
          <div class="submit-field">
            <h5 class="margin-bottom-0">Project Duration</h5>
            <p id="preview_hourly_duration"></p>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="submit-field">
            <h5 class="margin-bottom-0">Project Time</h5>
            <p id="preview_hourly_duration_time"></p>
          </div>
        </div>
      </div>
    </div>
    <div class="dashboard-box-footer">
      <button class="btn btn-secondary backbtnproject" data-step="7">Back</button>
      <button class="btn btn-site nextbtnproject" data-step="7">Post</button>
    </div>
  </div>
</div>
