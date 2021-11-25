<?php
/*
* Plugin Name: Fire Mobile
* Plugin URI: https://redq.io
* Description: WordPress & WooCommerce firebase mobile OTP authentication.
* Version: 1.0.1
* Author: RedQ, Inc
* Author URI: https://redq.io
* Requires at least: 4.6
* Tested up to: 5.5
*
* Text Domain: wp-firebase-otp
* Domain Path: /languages/
*
* Copyright: © 2012-2020 RedQ,Inc.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*
*/


/**
 * Class WFOTP
 */
class WFOTP
{

    /**
     * @var null
     */
    protected static $_instance = null;

    /**
     * @create instance on self
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function __construct()
    {
        if (!defined('WFOTP_REQUIRED_PHP_VERSION')) {
            define('WFOTP_REQUIRED_PHP_VERSION', 5.6);
        }
        if (!defined('WFOTP_REQUIRED_WP_VERSION')) {
            define('WFOTP_REQUIRED_WP_VERSION', 4.5);
        }
        add_action('admin_init', array($this, 'check_version'));
        if (!self::compatible_version()) {
            return;
        }
        $this->wfotp_load_all_classes();
        $this->wfotp_app_bootstrap();
        add_action('plugins_loaded', array($this, 'wfotp_language_textdomain'), 1);
    }

    public function wfotp_load_all_classes()
    {
        include_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    }

    /**
     *  App Bootstrap
     *  Fire all class
     */
    public function wfotp_app_bootstrap()
    {
        /**
         * Define plugin constant
         */
        define('WFOTP_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
        define('WFOTP_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));
        define('WFOTP_FILE', __FILE__);
        define('WFOTP_CSS', WFOTP_URL . '/assets/css/');
        define('WFOTP_LIB', WFOTP_URL . '/assets/lib/');
        define('WFOTP_IMG', WFOTP_URL . '/assets/img/');
        define('WFOTP_JS', WFOTP_URL . '/assets/js/');
        define('WFOTP_VEN', WFOTP_URL . '/assets/library/');
        define('WFOTP_FONTS', WFOTP_URL . '/assets/fonts/');
        define('WFOTP_SHORTCODES_PATH', plugin_dir_path(__FILE__) . 'Shortcodes/');
        new WFOTP\App\LoadScripts();
        new WFOTP\App\LoadShortcodes();
        new WFOTP\App\AsyncHandler();
        new WFOTP\Admin\Settings();
    }

    /**
     * Get the template path.
     *
     * @return string
     * @since 1.0.0
     */
    public function template_path()
    {
        return apply_filters('wfotp_template_path', 'wfotp/');
    }

    /**
     * Get the plugin path.
     *
     * @return string
     * @since 1.0.0
     */
    public function plugin_path()
    {
        return untrailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * Get the plugin textdomain for multilingual.
     * @return null
     */
    public function wfotp_language_textdomain()
    {
        load_plugin_textdomain('wp-firebase-otp', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    static function compatible_version()
    {
        if (phpversion() < WFOTP_REQUIRED_PHP_VERSION || $GLOBALS['wp_version'] < WFOTP_REQUIRED_WP_VERSION) return false;
        return true;
    }

    // The backup sanity check, in case the plugin is activated in a weird way,
    // or the versions change after activation.
    public function check_version()
    {
        if (!self::compatible_version()) {
            if (is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(plugin_basename(__FILE__));
                add_action('admin_notices', array($this, 'disabled_notice'));
                if (isset($_GET['activate'])) {
                    unset($_GET['activate']);
                }
            }
        }
    }

    public function disabled_notice()
    {
        if (phpversion() < WFOTP_REQUIRED_PHP_VERSION) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e('WP Firebase OTP! WP Firebase OTP requires PHP ' . WFOTP_REQUIRED_PHP_VERSION . ' or higher!', 'wp-firebase-otp'); ?></p>
            </div>
        <?php
        }
        if ($GLOBALS['wp_version'] < WFOTP_REQUIRED_WP_VERSION) { ?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e('WP Firebase OTP! WP Firebase OTP requires Wordpress ' . WFOTP_REQUIRED_WP_VERSION . ' or higher!', 'wp-firebase-otp'); ?></p>
            </div>
<?php
        }
    }
}

/**
 * @return null|WFOTP
 */
function WFOTP()
{

    return WFOTP::instance();
}

$GLOBALS['wfotp'] = WFOTP();
