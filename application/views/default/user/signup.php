<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section class="section">
    <div class="container">
        <div class="general-form">
            <div class="row g-0">
                <aside class="col-lg-5 d-none d-lg-block">
                    <img src="<?php echo IMAGE; ?>snaphive-worker-registration.jpg" alt="Image" class="w-100 h-100 object-fit-cover">
                </aside>
                <aside class="col-lg-7">
                    <div class="general-body">
                        <form action="" method="post" accept-charset="utf-8" id="Register_form" class="form-horizontal" role="form" name="regform" onsubmit="return false;">
                            <input type="hidden" name="step" value="1" id="step" />

                            <input type="hidden" name="ref" value="<?php D(get('ref')); ?>" />
                            <input type="hidden" name="refer" value="<?php D(get('refer')); ?>" readonly />

                            <div id="agree_termsError" class="error-msg5 error alert-error alert alert-danger" style="display:none"></div>
                            <div id="step_1">
                                <h2 class="text-center mb-3"><?php echo __('user_page_signup_header', 'Sign Up'); ?> Worker</h2>
                                <div class="form-field">
                                    <label class="form-label">Full Name <span class="req">*</span></label>
                                    <input type="text" class="form-control" value="" name="name" id="name" placeholder="<?php echo __('user_page_signup_name_placeholder', 'Enter Name'); ?>">
                                    <span id="nameError" class="rerror"></span>
                                </div>
                                <div class="form-field">
                                    <label class="form-label">Gender <span class="req">*</span></label>
                                    <div class="btn-group d-flex gap-3" role="group">
                                        <input type="radio" name="gender" id="male" value="M" class="btn-check">
                                        <label class="btn btn-outline-light rounded-2 pe-4" for="male">
                                            <img src="<?php echo IMAGE; ?>icon-male.png" alt="Male" height="48" width="48" /> Male
                                        </label>

                                        <input type="radio" name="gender" id="female" value="F" class="btn-check">
                                        <label class="btn btn-outline-light rounded-2 pe-4" for="female">
                                            <img src="<?php echo IMAGE; ?>icon-female.png" alt="Male" height="48" width="48" /> Female
                                        </label>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label">Date of Birth <span class="req">*</span></label>
                                            <input type="date" class="form-control" value="" name="dob" id="dob" placeholder="YYYY-MM-DD">
                                            <span id="dobError" class="rerror"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label">Religion <span class="req">*</span></label>
                                            <select name="religion" id="religion" class="selectpicker" title="Select Religion" data-live-search="true">
                                                <?php
                                                if ($religion) {
                                                    foreach ($religion as $religion_list) {
                                                ?>
                                                        <option value="<?php echo $religion_list['religion_id'] ?>"><?php echo ucfirst($religion_list['religion_name']); ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <span id="religionError" class="rerror"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <div class="d-flex justify-content-between align-items-end">
                                                <label class="form-label">Mobile Number <span class="req">*</span></label>
                                                <div class="form-check small">
                                                    <input class="form-check-input" type="checkbox" value="" id="checkWhatsapp">
                                                    <label class="form-check-label small" for="checkWhatsapp">
                                                        Same for Whatsapp
                                                    </label>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" value="" name="phone" id="phone">
                                            <span id="phoneError" class="rerror"></span>
                                        </div>
                                        
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label">Alternate Mobile Number </label>
                                            <input type="text" class="form-control" value="" name="alt_phone" id="alt_phone">
                                            <span id="alt_phoneError" class="rerror"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label">Email Address</label>
                                            <input type="text" class="form-control" value="" name="email" id="email">
                                            <span id="emailError" class="rerror"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label">WhatsApp Number </label>
                                            <input type="text" class="form-control" value="" name="whatsapp" id="whatsapp">
                                            <span id="whatsappError" class="rerror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary signUpBTN"><?php echo __('user_page_signup_button', 'Sign Up'); ?></button>
                                </div>
                            </div>
                            <div id="step_2" style="display: none">
                                <h2 class="text-center m-3"><?php echo __('user_page_signup_account', 'Complete your account'); ?></h2>
                                <div class="row gx-3">
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label">Service Category</label>
                                            <select name="service[]" id="service" class="selectpicker" title="Select Service" data-size="5" data-live-search="true">
                                                <?php
                                                if ($all_service) {
                                                    foreach ($all_service as $service) {
                                                ?>
                                                        <option value="<?php echo $service['category_subchild_id'] ?>"><?php echo ucfirst($service['category_subchild_name']); ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <span id="serviceError" class="rerror"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label" for="worker_address">Address <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="worker_address" id="worker_address" value="" />
                                            <span id="worker_addressError" class="rerror"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label" for="worker_flat">House/Flat No.</label>
                                            <input type="text" class="form-control" name="worker_flat" id="worker_flat" value="" />
                                            <span id="worker_flatError" class="rerror"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label" for="worker_street">Street/Area </label>
                                            <input type="text" class="form-control" name="worker_street" value="" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-field">
                                            <label class="form-label" for="worker_city">City <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="worker_city" id="worker_city" value="" />
                                            <span id="worker_cityError" class="rerror"></span>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-field">
                                            <label class="form-label" for="worker_state">State <span class="req">*</span></label>
                                            <select class="form-control" name="worker_state" id="worker_state">
                                                <option value="">-Select-</option>
                                                <?php print_select_option(get_state('IND'), 'state_id', 'state_name', ''); ?>
                                            </select>
                                            <span id="worker_stateError" class="rerror"></span>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-field">
                                            <label class="form-label" for="worker_pincode">Postal Code <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="worker_pincode" id="worker_pincode" value="" />
                                            <span id="worker_pincodeError" class="rerror"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-lg-6">
                                        <div class="form-field">
                                            <label class="form-label" for="worker_landmark">Landmark <span class="req">*</span></label>
                                            <input type="text" class="form-control" name="worker_landmark" id="worker_landmark" value="" />
                                            <span id="worker_landmarkError" class="rerror"></span>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="form-field">
                                    <div class="avatar-wrapper rounded-circle" id="crop-avatar-dashboard" style="height: 100px; width: 100px;">
                                        <input type="hidden" name="logo" id="logo" class="replceLogoVal">
                                        <img src="<?php D(getMemberLogo(0)); ?>" alt="">
                                        <a href="javascript:void(0)" class="edit_logo_btn btn btn-light btn-circle" data-popup="logo" data-tippy-placement="top" title="Upload avatar"><i class="icon-feather-edit-2"></i></a>
                                    </div>
                                </div>
                                <div class="form-field">
                                    <div class="checkbox small">
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
                    <button type="button" class="btn btn-dark float-start" data-bs-dismiss="modal"><?php echo __('setting_contact_info_cancel', 'Cancel'); ?></button>
                    <h4 class="modal-title"><?php echo __('setting_contact_change_avatar', 'Change Avatar'); ?></h4>
                    <button class="btn btn-success float-end avatar-save" type="submit"><?php echo __('setting_contact_Save', 'Save'); ?></button>
                </div>
                <div class="modal-body">
                    <div class="avatar-body">
                        <!-- Upload image and data -->
                        <div class="avatar-upload">
                            <input type="hidden" class="avatar-src" name="avatar_src">
                            <input type="hidden" class="avatar-data" name="avatar_data">
                            <label for="avatarInput" class="form-label"><?php echo __('setting_contact_profile_picture', 'Profile Picture'); ?> </label>

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
            FormPost(this, 'Register_form');
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
        var formD = $('#Register_form');
        //console.log(res);
        formD.find('#step_2 input').removeClass('is-valid');
        $('#select_email').html(res.email);
        formD.find('#step_1').hide();
        formD.find('#step_2').show();
        formD.find('#step').val(2);
    }

   
    document.getElementById('checkWhatsapp').addEventListener('change', function () {
        const phone     = document.getElementById('phone');
        const whatsapp  = document.getElementById('whatsapp');
        const checkbox  = this;

        // If checkbox is checked
        if (checkbox.checked) {

            // If mobile is empty → show error and uncheck
            if (phone.value.trim() === '') {
                alert('Please enter Mobile Number first.');
                checkbox.checked = false;
                phone.focus();
                return;
            }

            // Copy mobile to whatsapp
            whatsapp.value = phone.value;
        } 
        else {
            // Optional: clear whatsapp when unchecked
            whatsapp.value = '';
        }
    });
    
    const phone    = document.getElementById('phone');
    const whatsapp = document.getElementById('whatsapp');
    const checkbox = document.getElementById('checkWhatsapp');

    // When checkbox changes
    checkbox.addEventListener('change', function () {
        if (this.checked) {
            if (phone.value.trim() === '') {
                alert('Please enter Mobile Number first.');
                this.checked = false;
                phone.focus();
                return;
            }
            whatsapp.value = phone.value;
        } else {
            whatsapp.value = '';
        }
    });

// When phone changes & checkbox is checked
phone.addEventListener('input', function () {
    if (checkbox.checked) {
        whatsapp.value = phone.value;
    }
});


</script>
<?php $this->layout->view('inc/social-login', '', true); ?>