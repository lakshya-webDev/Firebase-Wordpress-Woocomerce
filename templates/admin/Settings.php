<?php
if (!empty($_POST) && check_admin_referer('nonce_wfotp_settings', 'wfotp_settings')) {
  $posted = $_POST;

  unset($posted['submit']);
  unset($posted['wfotp_settings']);
  unset($posted['_wp_http_referer']);
  update_option('wfotp_settings', $posted);
}
$wfotp_settings = get_option('wfotp_settings', true);

?>
<div class="rq-firebase-settings-wrap">
  <h1 class="la-page-title"><?php esc_html_e('Fire Mobile Settings', 'wp-firebase-otp') ?></h1>

  <div class="rq-firebase-instruction-block">
    <p class="la-page-title-notice">
      <?php echo esc_html__('Instructions :', 'wp-firebase-otp'); ?>
    </p>
    <ol type="1">
      <li><?php echo esc_html__('If you do not have a Firebase project, you can create one from here ', 'wp-firebase-otp'); ?>
        <a href="<?php echo esc_url('https://console.firebase.google.com/'); ?>" target="_blank"><?php echo esc_html__('link', 'wp-firebase-otp'); ?>
        </a>
      </li>
      <li><?php echo esc_html__('Now go to your Firebase Project overview->Project settings and copy paste firebase configuration in below fields one by one', 'wp-firebase-otp'); ?>
      </li>
      <li><?php echo esc_html__('In the Firebase console, open the Authentication section.', 'wp-firebase-otp'); ?>
      </li>
      <li><?php echo esc_html__('On the Sign-in Method page, enable the Phone Number sign-in method.', 'wp-firebase-otp'); ?>
      </li>
      <li><?php echo esc_html__("On the same page, if the domain that will host your app isn't listed in the OAuth redirect domains section, add your domain.", 'wp-firebase-otp'); ?>
      </li>
      <li><?php echo esc_html__("Copy this shortcode [firebase_otp_login] and add it to any place on your site you like.", 'wp-firebase-otp'); ?>
      </li>
      <li><?php echo esc_html__("That's it, You should be able to use mobile login in your WordPress and WooCommerce site.", 'wp-firebase-otp'); ?>
      </li>
      <li><?php echo esc_html__("For more details you can check our docs", 'wp-firebase-otp'); ?>
      </li>
    </ol>
  </div>

  <div class="rq-firebase-instruction-block">
    <p class="la-page-title-notice">
      <?php echo esc_html__('Video Guide :', 'wp-firebase-otp'); ?>
    </p>
    <div class="fire-mobile-video-guide">
      <iframe width="560" height="315" src="https://www.youtube.com/embed/v57JAmZAI3A" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
  </div>

  <form method="post">
    <div class="form-table rq-firebase-settings-from">
      <p class="la-page-title-notice">
        <?php echo esc_html__('Settings Form :', 'wp-firebase-otp'); ?>
      </p>

      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Api Key (apiKey)', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="api_key" value="<?php echo (isset($wfotp_settings['api_key']) && !empty($wfotp_settings['api_key'])) ? $wfotp_settings['api_key'] : ''; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Auth Domain (authDomain)', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="auth_domain" value="<?php echo (isset($wfotp_settings['auth_domain']) && !empty($wfotp_settings['auth_domain'])) ? $wfotp_settings['auth_domain'] : ''; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Database URL (databaseURL)', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="database_url" value="<?php echo (isset($wfotp_settings['database_url']) && !empty($wfotp_settings['database_url'])) ? $wfotp_settings['database_url'] : ''; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Project Id (projectId)', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="project_id" value="<?php echo (isset($wfotp_settings['project_id']) && !empty($wfotp_settings['project_id'])) ? $wfotp_settings['project_id'] : ''; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Storage Bucket (storageBucket)', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="storage_bucket" value="<?php echo (isset($wfotp_settings['storage_bucket']) && !empty($wfotp_settings['storage_bucket'])) ? $wfotp_settings['storage_bucket'] : ''; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Messaging Sender Id (messagingSenderId)', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="messaging_sender_id" value="<?php echo (isset($wfotp_settings['messaging_sender_id']) && !empty($wfotp_settings['messaging_sender_id'])) ? $wfotp_settings['messaging_sender_id'] : ''; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('App Id (appId)', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="app_id" value="<?php echo (isset($wfotp_settings['app_id']) && !empty($wfotp_settings['app_id'])) ? $wfotp_settings['app_id'] : ''; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Phone Number error text', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="phone_error_translation" value="<?php echo (isset($wfotp_settings['phone_error_translation']) && !empty($wfotp_settings['phone_error_translation'])) ? $wfotp_settings['phone_error_translation'] : 'Invalid Number!'; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('OTP code error text', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="otp_code_error_translation" value="<?php echo (isset($wfotp_settings['otp_code_error_translation']) && !empty($wfotp_settings['otp_code_error_translation'])) ? $wfotp_settings['otp_code_error_translation'] : 'Invalid Code!'; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Successful verification text', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="successful_text_translation" value="<?php echo (isset($wfotp_settings['successful_text_translation']) && !empty($wfotp_settings['successful_text_translation'])) ? $wfotp_settings['successful_text_translation'] : 'Successful!'; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Login failed text', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="login_failed_text" value="<?php echo (isset($wfotp_settings['login_failed_text']) && !empty($wfotp_settings['login_failed_text'])) ? $wfotp_settings['login_failed_text'] : 'Login failed, please try again!'; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Phone Block title', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="phone_name_title" value="<?php echo (isset($wfotp_settings['phone_name_title']) && !empty($wfotp_settings['phone_name_title'])) ? $wfotp_settings['phone_name_title'] : 'Enter Your Number'; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Verify Block Title', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="verify_code_title" value="<?php echo (isset($wfotp_settings['verify_code_title']) && !empty($wfotp_settings['verify_code_title'])) ? $wfotp_settings['verify_code_title'] : 'Verify Code'; ?>">
          </div>
        </div>
      </div>
      <div class="la-input-group">
        <div class="la-input-wrapper">
          <span class="la-input-title">
            <?php esc_html_e('Enter Email domain', 'wp-firebase-otp') ?>
          </span>
          <div class="la-input-field">
            <input type="text" class="widefat rq-rub-input-field" name="email_domain" value="<?php echo (isset($wfotp_settings['email_domain']) && !empty($wfotp_settings['email_domain'])) ? $wfotp_settings['email_domain'] : 'test.com'; ?>">
          </div>
        </div>
      </div>
    </div>
    <?php
    wp_nonce_field('nonce_wfotp_settings', 'wfotp_settings');
    ?>
    <p class="submit">
      <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e("Save Changes", 'wp-firebase-otp') ?>">
    </p>
  </form>
</div>