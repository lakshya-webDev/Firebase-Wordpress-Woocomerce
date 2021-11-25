<?php

namespace WFOTP\App;

class AsyncHandler
{
    /**
     * Action hook used by the AJAX class.
     *
     * @var string
     */
    const ACTION = 'wfotp_ajax';
    /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var string
     */
    const NONCE = 'wfotp_ajax_nonce';
    /**
     * AsyncHandler constructor.
     */
    public function __construct()
    {
        add_action('wp_ajax_' . self::ACTION, array($this, 'handle_ajax'));
        add_action('wp_ajax_nopriv_' . self::ACTION, array($this, 'handle_ajax'));
    }
    public function handle_ajax()
    {
        check_ajax_referer(self::NONCE, 'nonce');
        $ajax_data = $_POST;
        unset($ajax_data['nonce']);
        unset($ajax_data['action']);
        switch ($ajax_data['action_type']) {
            case 'firebase_otp_login':
                $phoneNumber = !empty($ajax_data['phone_number']) ? $ajax_data['phone_number'] : false;
                $uid = !empty($ajax_data['uid']) ? $ajax_data['uid'] : false;
                /** check if user is available */
                $user = get_user_by('email', $phoneNumber . '@test.com');
                if (!empty($user)) {
                    /** user available in the db */
                    /** set user auth cookie */
                    wp_clear_auth_cookie();
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);
                } else {
                    $role = '';
                    if (class_exists('woocommerce')) {
                        $role = 'customer';
                    }
                    // user is not available in the db
                    // insert user
                    $wfotp_settings = get_option('wfotp_settings', true);
                    $email_domain = isset($wfotp_settings['phone_name_title']) && !empty($wfotp_settings['phone_name_title']) ? $wfotp_settings['phone_name_title'] : 'test.com';
                    $userdata = array(
                        'user_login' =>  $phoneNumber,
                        'user_email' => $phoneNumber . '@' . $email_domain,
                        'user_pass'  =>  null, // When creating an user, `user_pass` is expected.
                        'role' => $role,
                    );
                    $user_id = wp_insert_user($userdata);
                    // On success.
                    if (!is_wp_error($user_id)) {
                        add_user_meta($user_id, '_firebase_uid', $uid);
                        add_user_meta($user_id, 'phone_number', $phoneNumber);
                        if (class_exists('woocommerce')) {
                            add_user_meta($user_id, 'billing_phone', sanitize_text_field($phoneNumber));
                        }
                        wp_clear_auth_cookie();
                        wp_set_current_user($user_id);
                        wp_set_auth_cookie($user_id);
                    }
                }
                echo json_encode(array('phone_number' => $phoneNumber, 'status' => 200, 'message' => esc_html__('Successful', 'wfotp')));
                break;
        }
        wp_die();
    }
}
