<?php

namespace WFOTP\Admin;

class Settings
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'firebase_otp_settings_panel'));
    }

    public function firebase_otp_settings_panel()
    {
        $page_title = esc_html__('Fire Mobile', 'wp-firebase-otp');
        $menu_title = esc_html__('Fire Mobile', 'wp-firebase-otp');
        $capability = 'manage_options';
        $menu_slug  = 'wp-firebase-otp-settings';
        $function   = array($this, 'firebase_otp_settings_func');
        $icon_url   = 'dashicons-media-code';
        $position   = 80;
        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            $position
        );
    }

    public function firebase_otp_settings_func()
    {
        if (!current_user_can('manage_options')) {
            wp_die(
                esc_attr__('You do not have sufficient permissions to access this page.', 'wp-firebase-otp')
            );
        }
        include_once WFOTP_DIR . '/templates/admin/Settings.php';
    }
}
