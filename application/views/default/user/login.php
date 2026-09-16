<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<section class="section">
    <div class="container">
        <div class="row">
            <aside class="col-lg-6">
                
            </aside>
            <aside class="col-lg-6">        
                <div class="general-form">
                    <div class="general-body">
                        <form action="" method="post" accept-charset="utf-8" id="logform" class="form-horizontal" role="form" name="logform" onsubmit="return false;">
                            <input type="hidden" name="ref" value="<?php D(get('ref')); ?>" />
                            <input type="hidden" name="refer" value="<?php D(get('refer')); ?>" readonly />
                            <h1 class="text-center m-0"><?php echo __('user_page_login_header', 'Log In'); ?></h1>
                            <div class="m-lg-3 d-none d-sm-block"> </div>
                            <div id="agree_termsError" class="error-msg5 error alert-error alert alert-danger" style="display:none"></div>
                            <div class="input-with-icon-start">
                                <div>
                                    <i class="icon-feather-mail"></i>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="<?php echo __('user_page_login_email_placeholder', 'Email Address'); ?>" />
                                </div>
                                <span id="emailError" class="rerror"></span>
                            </div>

                            <div class="input-with-icon-start">
                                <div>
                                    <i class="icon-feather-lock"></i>
                                    <input type="password" class="form-control" value="" name="password" id="password" placeholder="<?php echo __('user_page_login_password_placeholder', 'Password'); ?>">
                                </div>
                                <span id="passwordError" class="rerror"></span>
                            </div>

                            <div class="form-group">
                                <label for="" class="control-label" style="display:none;">
                                    <input type="checkbox"> <?php echo __('user_page_login_password_remember', 'Remember Me'); ?> </label>
                                <a href="<?php echo get_link('forgotURL'); ?>"><?php echo __('user_page_login_password_forget', 'Forgot Password'); ?>?</a>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" id="signInBTN"><?php echo __('user_page_login_button', 'Log In'); ?></button>
                            </div>
                            <div class="social-login-separator"><span><?php echo __('user_page_login_separetor', 'OR'); ?></span></div>
                            <div class="social-login-buttons mb-3">
                                <button class="facebook-login ripple-effect fb_login_btn"><i class="icon-brand-facebook-f"></i> <?php echo __('user_page_login_facebook', 'Log In via Facebook'); ?></button>
                                <button class="google-login ripple-effect google_login_btn"><i class="icon-brand-google"></i> <?php echo __('user_page_login_google', 'Log In via Google'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<script type="text/javascript">
    var main = function() {
        $('#signInBTN').click(function() {
            // console.log('ok');
            FormPost(this, 'Login_form');
        });
    }
</script>
<?php $this->layout->view('inc/social-login', '', true); ?>