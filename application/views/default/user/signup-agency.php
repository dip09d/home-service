<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section class="section">
  <div class="container">
    <div class="general-form">
      <div class="row g-0">
        <aside class="col-lg-5 d-none d-lg-block">
          <img src="<?php echo IMAGE; ?>snaphive-agency-registration.jpg" alt="Image" class="w-100 h-100 object-fit-cover">
        </aside>
        <aside class="col-lg-7">
          <div class="general-body">
            <form action="" method="post" accept-charset="utf-8" id="Register_form_agency" class="form-horizontal" role="form" name="regform" onsubmit="return false;">
              <input type="hidden" name="step" value="1" id="step" />

              <input type="hidden" name="ref" value="<?php D(get('ref')); ?>" />
              <input type="hidden" name="refer" value="<?php D(get('refer')); ?>" readonly />

              <div id="agree_termsError" class="error-msg5 error alert-error alert alert-danger" style="display:none"></div>
              <div id="step_1">
                <h2 class="text-center m-0">Sign Up Agency</h2>
                <div class="m-lg-3 d-none d-sm-block"> </div>
                <div class="form-field">
                  <label class="form-label">Agency Name <span class="req">*</span></label>
                  <input type="text" class="form-control" value="" name="agency_name" id="agency_name" placeholder="Enter Agency Name">
                  <span id="agency_nameError" class="rerror"></span>
                </div>
                <div class="form-field">
                  <label class="form-label">Full Name <span class="req">*</span></label>
                  <input type="text" class="form-control" value="" name="name" id="name" placeholder="<?php echo __('user_page_signup_name_placeholder', 'Enter Name'); ?>">
                  <span id="nameError" class="rerror"></span>
                </div>

                <div class="form-field">
                  <label class="form-label">Mobile Number <span class="req">*</span></label>
                  <input type="text" class="form-control" value="" name="phone" id="phone">
                  <span id="phoneError" class="rerror"></span>
                </div>
                <div class="form-field">
                  <label class="form-label">WhatsApp Number </label>
                  <input type="text" class="form-control" value="" name="whatsapp" id="whatsapp">
                  <span id="whatsappError" class="rerror"></span>
                </div>
                <div class="form-field">
                  <label class="form-label">Email Address</label>
                  <input type="text" class="form-control" value="" name="email" id="email">
                  <span id="emailError" class="rerror"></span>
                </div>


                <div class="d-grid">
                  <button class="btn btn-primary mb-3 signUpBTN"><?php echo __('user_page_signup_button', 'Sign Up'); ?></button>
                </div>


              </div>
              <div id="step_2" style="display: none">
                <h2 class="text-center m-0"><?php echo __('user_page_signup_account', 'Complete your account'); ?></h2>
                <div class="m-lg-3 d-none d-sm-block"> </div>
                <div class="form-field">
                  <label class="form-label" for="agency_address">Address <span class="req">*</span></label>
                  <input type="text" class="form-control" name="agency_address" id="agency_address" value="" />
                  <span id="agency_addressError" class="rerror"></span>
                </div>



                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-field">
                      <label class="form-label" for="agency_city">City <span class="req">*</span></label>
                      <input type="text" class="form-control" name="agency_city" id="agency_city" value="" />
                      <span id="agency_cityError" class="rerror"></span>

                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-field">
                      <label class="form-label" for="agency_state">State <span class="req">*</span></label>
                      <select class="form-control" name="agency_state" id="agency_state">
                          <option value="">-Select-</option>
                          <?php print_select_option(get_state('IND'), 'state_id', 'state_name', ''); ?>
                      </select>
                      <span id="agency_stateError" class="rerror"></span>

                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-field">
                      <label class="form-label" for="agency_pincode">Postal Code <span class="req">*</span></label>
                      <input type="text" class="form-control" name="agency_pincode" id="agency_pincode" value="" />
                      <span id="agency_pincodeError" class="rerror"></span>
                    </div>
                  </div>
                </div>

                <div class="form-field">
                  <label class="form-label" for="agency_landmark">Landmark <span class="req">*</span></label>
                  <input type="text" class="form-control" name="agency_landmark" id="agency_landmark" value="" />
                  <span id="agency_landmarkError" class="rerror"></span>
                </div>



                <div class="form-field">
                  <div class="checkbox">
                    <input type="checkbox" name="agree_term" id="agree_term" value="1">
                    <label for="agree_term"><span class="checkbox-icon"></span>I agree to <a href="<?php D(get_link('CMStermsandconditions')) ?>" target="_blank">terms & conditions</a> and <a href="<?php D(get_link('CMSprivacypolicy')) ?>" target="_blank">provider policy</a> </label>
                  </div>
                  <span id="agree_termError" class="rerror"></span>
                </div>

                <div class="d-grid">
                  <button class="btn btn-primary signUpBTN">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </aside>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="avatar-modal-profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index: 10000" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content mycustom-modal">
      <form class="avatar-form" action="<?php D(get_link('signupsavelogo')) ?>" enctype="multipart/form-data" method="post">
        <input type="hidden" value="logo" id="formtype" name="formtype" />
        <div class="modal-header">
          <!-- <button type="submit" class="btn btn-success float-end avatar-save">Done</button>-->
          <button type="button" class="btn btn-dark float-start" data-dismiss="modal"><?php echo __('setting_contact_info_cancel', 'Cancel'); ?></button>
          <h4 class="modal-title"><?php echo __('setting_contact_change_avatar', 'Change Avatar'); ?></h4>
          <button class="btn btn-success float-end avatar-save" type="submit"><?php echo __('setting_contact_Save', 'Save'); ?></button>
        </div>
        <div class="modal-body">
          <div class="avatar-body">
            <!-- Upload image and data -->
            <div class="avatar-upload">
              <input type="hidden" class="avatar-src" name="avatar_src">
              <input type="hidden" class="avatar-data" name="avatar_data">
              <label for="avatarInput"><?php echo __('setting_contact_profile_picture', 'Profile Picture'); ?> </label>

              <div class="uploadButton margin-top-0">
                <input class="uploadButton-input avatar-input" type="file" id="avatarInput" name="avatar_file">
                <label class="uploadButton-button" for="avatarInput"><?php echo __('setting_contact_upload_file', 'Upload Files'); ?></label>
                <span class="uploadButton-file-name" <?php echo __('setting_contact_max_size', 'Maximum file size: 2 MB'); ?>></span>
              </div>

            </div>


            <p class="green-text"><?php echo __('setting_contact_file_format', 'File must be gif, jpg, png, jpeg.'); ?></p>
          </div>

          <!-- Crop and preview -->
          <div class="row">
            <div class="col-md-9">
              <div class="avatar-crop-wrapper"></div>
            </div>
            <div class="col-md-3">
              <div class="avatar-preview preview-lg d-none"></div>
              <div class="avatar-preview preview-md"></div>
              <div class="avatar-preview preview-sm d-none"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
  var SPINNER = '<?php load_view('inc/spinner', array('size' => 30)); ?>';
  var all_service = <?php echo count($all_service) > 0 ? json_encode($all_service) : '[]'; ?>;
  var main = function() {
    $('.signUpBTN').click(function() {
      FormPost(this, 'Register_form_agency');
    });

    var bhtn = new Bloodhound({
      local: all_service,
      datumTokenizer: Bloodhound.tokenizers.obj.whitespace('category_subchild_name'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
    });
    var elts = $('.tagsinput_skill');
    elts.tagsinput({
      itemValue: 'category_subchild_id',
      itemText: 'category_subchild_name',
      typeaheadjs: {
        limit: 25,
        displayKey: 'category_subchild_name',
        hint: false,
        highlight: true,
        minLength: 1,
        source: bhtn.ttAdapter(),
        templates: {
          notFound: [
            "<div class=empty-message>",
            "<?php D('No match found') ?>",
            "</div>"
          ].join("\n"),
          suggestion: function(e) {
            var test_regexp = new RegExp('(' + e._query + ')', "gi");
            return ('<div>' + e.category_subchild_name.replace(test_regexp, '<b>$1</b>') + '</div>');
          }
        }
      }
    });
  }

  function nextstep(res) {
    var formD = $('#Register_form_agency');
    //console.log(res);
    formD.find('#step_2 input').removeClass('is-valid');
    $('#select_email').html(res.email);
    formD.find('#step_1').hide();
    formD.find('#step_2').show();
    formD.find('#step').val(2);
  }
</script>


<?php $this->layout->view('inc/social-login', '', true); ?>