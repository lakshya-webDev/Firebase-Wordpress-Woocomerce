<?php


namespace WFOTP\App;

class LoadScripts
{
  public function __construct()
  {
    add_action('wp_enqueue_scripts', array($this, 'loadFrontEndScript'));
    add_action('admin_enqueue_scripts', array($this, 'loadBackendScript'));
  }

  public function loadFrontEndScript()
  {
    wp_enqueue_script('firebase-app', WFOTP_VEN . 'firebase/firebase-app.js', array('jquery'), false, true);
    wp_enqueue_script('firebase-auth', WFOTP_VEN . 'firebase/firebase-auth.js', array('jquery'), false, true);
    wp_enqueue_script('input-letter', WFOTP_VEN . 'input-lettering/inputLettering.min.js', array('jquery'), false, true);
    wp_enqueue_script('intl-tel-input', WFOTP_VEN . 'intl-tel-input/js/intlTelInput.min.js', array('jquery'), false, true);
    wp_enqueue_script('jquery-modal', WFOTP_VEN . 'jquery-modal/jquery.modal.min.js', array('jquery'), false, true);
    wp_enqueue_style('intl-tel-input', WFOTP_VEN . 'intl-tel-input/css/intlTelInput.min.css', array(), false, 'all');
    wp_enqueue_style('jquery-modal', WFOTP_VEN . 'jquery-modal/jquery.modal.min.css', array(), false, 'all');
    wp_enqueue_script('wfotp-core', WFOTP_JS . 'wfotp-core.js', array('jquery', 'underscore'), false, true);
    wp_localize_script('wfotp-core', 'WFOTP_DATA', array(
      'action'        => 'wfotp_ajax',
      'nonce'         => wp_create_nonce('wfotp_ajax_nonce'),
      'ajaxUrl'      => admin_url('admin-ajax.php'),
      'library_path' => WFOTP_VEN,
      'settings'     => get_option('wfotp_settings', true),
    ));
    wp_enqueue_style('wfotp-Core', WFOTP_CSS . 'wfotp-core.css', array(), false, 'all');
  }
  public function loadBackendScript()
  {
    wp_enqueue_style('wfotp-Admin', WFOTP_CSS . 'wfotp-admin.css', array(), false, 'all');
    wp_localize_script('jquery', 'WFOTP_ADMIN', array('is_active' => true));
  }
}
