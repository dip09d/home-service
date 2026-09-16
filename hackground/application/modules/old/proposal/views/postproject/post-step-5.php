<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//dd($memberInfo,TRUE);
?>

<div id="dataStep-5"  style="display: none">
  <div class="dashboard-box margin-top-0"> 
    <!-- Headline -->
    <div class="headline">
      <h3> Visibility </h3>
    </div>
    <div class="content with-padding">
      <div class="submit-field myradio">
        <label>Who can see your project?</label>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn">
            <input type="radio" id="defaultInlinePublic" name="projectVisibility" value="public" autocomplete="off">
            <i class="icon-line-awesome-eye"></i><br>
            Anyone </label>
          <label class="btn">
            <input type="radio" id="defaultInlinePrivate" name="projectVisibility" value="private" autocomplete="off">
            <i class="icon-line-awesome-eye-slash"></i><br>
            Private </label>
          <label class="btn">
            <input type="radio" id="defaultInlineInvite" name="projectVisibility" value="invite" autocomplete="off">
            <i class="icon-line-awesome-lock"></i><br>
            Invite </label>
        </div>
        <div class="clearfix"></div>
        <span id="projectVisibilityError" class="rerror"></span> </div>
      <div class="submit-field myradio mb-0">
        <label>How many freelancer you need for your project?</label>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
          <label class="btn">
            <input type="radio" class="no_of_freelancer_radio" id="defaultInlineOne" name="member_required" value="S" autocomplete="off">
            <i class="icon-feather-user"></i><br>
            One freelancer </label>
          <label class="btn">
            <input type="radio" class="no_of_freelancer_radio" id="defaultInlineMulti" name="member_required" value="M" autocomplete="off">
            <i class="icon-feather-users"></i><br>
            More than one freelancer </label>
        </div>
        <div class="clearfix"></div>
        <span id="member_requiredError" class="rerror"></span> </div>
      <div class="row no_of_freelancer_display mt-3" style="display: none">
        <div class="col-xl-6">
          <div class="submit-field mb-0">
            <label>Number of freelancer</label>
            <input type="text" class="form-control" name="no_of_freelancer" id="no_of_freelancer" value=""/>
            <span id="no_of_freelancerError" class="rerror"></span> </div>
        </div>
      </div>
    </div>
    <div class="dashboard-box-footer">
      <button class="btn btn-secondary backbtnproject" data-step="5">Back</button>
      <button class="btn btn-site nextbtnproject" data-step="5">Next</button>
    </div>
  </div>
</div>
