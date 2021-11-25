<?php


namespace WFOTP\App;

class LoadShortcodes
{
    public function __construct()
    {
        $templates_array = array(
            'FirebaseOtpLogin'
        );
        foreach ($templates_array as $template) {
            $this->require_shortcode_files($template);
        }
    }

    public function require_shortcode_files($template)
    {
        require_once(WFOTP_SHORTCODES_PATH . $template . '.php');
    }
}