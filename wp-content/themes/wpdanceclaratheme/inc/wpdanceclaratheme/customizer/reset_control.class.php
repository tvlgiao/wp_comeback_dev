<?php
namespace wpdanceclaratheme\Customizer;


add_action('customize_register', __NAMESPACE__.'\\Reset_Control_Helper::customize_register');
add_action('customize_controls_print_scripts', __NAMESPACE__.'\\Reset_Control_Helper::controls_print_scripts');


if (!class_exists(__NAMESPACE__.'\\Reset_Control_Helper')):
/**
 * Helper class for processing reset theme settings
 */
class Reset_Control_Helper {

	/**
	 * Holding alert message
	 *
	 * @var string $message
	 */
	static protected $message = '';

	static public function customize_register($wpc) {

		if (current_user_can('edit_theme_options')) {

			if (isset($_REQUEST['wpdanceclaratheme-reset']))
				self::reset($wpc);
		}
	}


	/**
	 * Callback function for hook 'controls_print_scripts'
	 *
	 * Show alert message by javascript
	 */
	static public function controls_print_scripts() {

		if (self::$message) {
			echo '<script>alert("' . esc_js(self::$message) . '");</script>';
		}
	}

	static public function reset($wpc) {

		# Validate nonce
		if (!wp_verify_nonce($_REQUEST['wpdanceclaratheme-reset'], 'wpdanceclaratheme-reset'))
			die("Invalid nonce");

		# Get theme settings
		$mods = (array)get_theme_mods();

		# Clear all theme settings
		remove_theme_mods();

		# Add other settings except setting start with 'wpdanceclaratheme_'
		foreach ($mods as $k => $mod)
			if (!preg_match('/^wpdanceclaratheme_/', $k))
				set_theme_mod($k, $mod);

		self::$message = esc_html__("All theme settings are reset.", 'wpdanceclaratheme');
	}

}
endif;



if (!class_exists(__NAMESPACE__.'\\Reset_Control')):
/**
 * WP Customizer Control for reset theme settings
 */
class Reset_Control extends \WP_Customize_Control {

	public $type = 'wpdanceclaratheme_export';

	protected function render_content() {

		$reset_url = add_query_arg('wpdanceclaratheme-reset', wp_create_nonce('wpdanceclaratheme-reset'), admin_url('customize.php'));
		?>

		<span class="customize-control-title">
			<?php esc_html_e("Restore theme settings to default", 'wpdanceclaratheme'); ?>
		</span>
		
		<span class="description customize-control-description">
			<?php esc_html_e("Factory reset: restore all theme settings to default value.", 'wpdanceclaratheme'); ?>
		</span>

		<a href="<?php echo esc_attr($reset_url); ?>" class="button submit-button"><?php esc_html_e("Restore", 'wpdanceclaratheme'); ?></a>

		<?php
	}
}
endif;
