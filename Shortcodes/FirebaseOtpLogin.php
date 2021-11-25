<?php
if (!defined('ABSPATH')) {
  exit;
}

add_shortcode('firebase_otp_login', 'renderFirebaseOtpLogin');

function renderFirebaseOtpLogin($atts)
{
  $atts = shortcode_atts(array(
    'phone_button_text'      => 'Send Code',
    'code_button_text'  => 'Verify & Login',
    'phone_toggle_button_text' => 'Continue with Phone',
    'recaptcha'  => 'invisible', // invisible or normal
  ), $atts);
  $atts = apply_filters('firebase_otp_login_atts', $atts);
  extract($atts);
  $wfotp_settings = get_option('wfotp_settings', true);
  $unique_id = uniqid();
?>
  <button type="button" class="rq-otp-btn rq-otp-phone-button-toggle" id="rq-otp-phone-button-toggle-<?php echo $unique_id ?>">
    <span class="rq-otp-btn-icon">
      <svg x="0px" y="0px" viewBox="0 0 503.604 503.604">
        <g>
          <g>
            <path d="M337.324,0H167.192c-28.924,0-53.5,23.584-53.5,52.5v398.664c0,28.916,24.056,52.44,52.98,52.44l170.412-0.184
			c28.92,0,52.58-23.528,52.58-52.448l0.248-398.5C389.908,23.452,366.364,0,337.324,0z M227.68,31.476h49.36
			c4.336,0,7.868,3.52,7.868,7.868c0,4.348-3.532,7.868-7.868,7.868h-49.36c-4.348,0-7.868-3.52-7.868-7.868
			C219.812,34.996,223.332,31.476,227.68,31.476z M198.02,33.98c2.916-2.912,8.224-2.952,11.136,0c1.46,1.456,2.324,3.5,2.324,5.588
			c0,2.048-0.864,4.088-2.324,5.548c-1.452,1.46-3.504,2.32-5.548,2.32c-2.084,0-4.088-0.86-5.588-2.32
			c-1.452-1.456-2.28-3.5-2.28-5.548C195.736,37.48,196.568,35.436,198.02,33.98z M250.772,488.008
			c-12.984,0-23.544-10.568-23.544-23.548c0-12.984,10.56-23.548,23.544-23.548s23.544,10.564,23.544,23.548
			C274.316,477.44,263.752,488.008,250.772,488.008z M365.488,424.908H141.232V74.756h224.256V424.908z" />
          </g>
        </g>
      </svg>

    </span>
    <span class="rq-otp-btn-text">
      <?php echo $phone_toggle_button_text ?>
    </span>
  </button>

  <div class="rq-otp-modal modal" id="rq-otp-modal-<?php echo $unique_id ?>">
    <a href="#close-modal" rel="modal:close" class="rq-otp-close-modal">
      <svg height="329pt" viewBox="0 0 329.26933 329" width="329pt">
        <path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0" />
      </svg>
    </a>
    <!-- end of modal close btn -->

    <div id="rq-otp-phone-wrapper-<?php echo $unique_id ?>" class="rq-otp-phone-wrapper rq-hide">
      <h3 class="rq-otp-modal-title">
        <?php echo (isset($wfotp_settings['phone_name_title']) && !empty($wfotp_settings['phone_name_title'])) ? $wfotp_settings['phone_name_title'] : 'Enter Your Number'; ?>
      </h3>
      <div data-recaptcha="<?php echo $recaptcha ?>" class="rq-otp-recaptcha-section" id="rq-otp-recaptcha-section-<?php echo $unique_id ?>">
        <input class="rq-otp-phone-input" id="rq-otp-phone-input-<?php echo $unique_id ?>" type="tel" name="phone"></input>
        <div id="rq-otp-error-<?php echo $unique_id ?>" class="rq-otp-error-feedback rq-otp-error">
        </div>
        <div class="rq-otp-recaptcha-container" id="rq-otp-recaptcha-container-<?php echo $unique_id ?>"></div>
        <button type="button" class="number-submit rq-otp-btn firebase-otp-phone-button" id="firebase-otp-phone-button-<?php echo $unique_id ?>">
          <span class="rq-otp-btn-text">
            <?php echo $phone_button_text ?>
          </span>
          <span class="rq-otp-mini-loader">
            <span class="dot1"></span>
            <span class="dot2"></span>
            <span class="dot3"></span>
          </span>
        </button>
      </div>
    </div>
    <!-- end of otp phone number input area -->

    <div id="rq-otp-code-<?php echo $unique_id ?>" class="rq-otp-code-wrapper rq-hide">
      <h3 class="rq-otp-modal-title">
        <?php echo (isset($wfotp_settings['verify_code_title']) && !empty($wfotp_settings['verify_code_title'])) ? $wfotp_settings['verify_code_title'] : 'Verify Code'; ?>
      </h3>

      <div class="rq-otp-phone-code-input" id="rq-otp-phone-code-input-<?php echo $unique_id ?>">
        <div class="rq-otp-form-group">
          <input type="text" name="letters[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1" placeholder="○">
          <input type="text" name="letters[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1" placeholder="○">
          <input type="text" name="letters[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1" placeholder="○">
          <input type="text" name="letters[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1" placeholder="○">
          <input type="text" name="letters[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1" placeholder="○">
          <input type="text" name="letters[]" class="letter" pattern="[0-9]*" inputmode="numeric" maxlength="1" placeholder="○">
        </div>
      </div>
      <!-- id rq-otp-verify-section used in js for code letter input  -->
      <div class="rq-otp-verify-section" id="rq-otp-verify-section-<?php echo $unique_id ?>">
        <input type="hidden" name="rq-otp-verify-phone" class="rq-otp-firebase-verify-phone" id="rq-otp-firebase-verify-phone-<?php echo $unique_id ?>"></input>
        <button type="button" class="code-submit rq-otp-btn rq-otp-verify-button" id="rq-otp-verify-button-<?php echo $unique_id ?>">
          <span class="rq-otp-btn-text">
            <?php echo $code_button_text ?>
          </span>
          <span class="rq-otp-mini-loader">
            <span class="dot1"></span>
            <span class="dot2"></span>
            <span class="dot3"></span>
          </span>
        </button>
      </div>
    </div>
    <!-- end of otp code & verify area -->

    <!-- error feedback section -->
    <div id="rq-otp-modal-error-<?php echo $unique_id ?>" class="rq-otp-modal-error rq-otp-error-feedback"></div>

    <!-- success feedback section -->
    <div id="rq-otp-modal-success-<?php echo $unique_id ?>" class="rq-otp-modal-success rq-otp-success-feedback">
      <div class="rq-success-check-icon">
        <span class="icon-line line-tip"></span>
        <span class="icon-line line-long"></span>
        <div class="icon-circle"></div>
        <div class="icon-fix"></div>
      </div>
      <h3>
        <?php echo (isset($wfotp_settings['successful_text_translation']) && !empty($wfotp_settings['successful_text_translation'])) ? $wfotp_settings['successful_text_translation'] : 'Successful!'; ?>
      </h3>
    </div>
    <!-- end of rq otp modal -->
  </div>
<?php
}
