<?php
namespace wpdanceclaratheme\Customizer;

add_action('customize_register', __NAMESPACE__.'\\Import_Control_Helper::customize_register', 99999); // Make sure it runs after all settings registered
add_action('customize_controls_print_scripts', __NAMESPACE__.'\\Import_Control_Helper::controls_print_scripts');

# =================================================================================================



if (!class_exists(__NAMESPACE__.'\\Import_Control_Helper')):
/**
 * Helper class to process importing theme settings
 */
class Import_Control_Helper {


	/**
	 * Holding alert message
	 *
	 * @var string $message
	 */
	static protected $message = '';


	/**
	 * Callback function for hook 'customize_register'
	 * 
	 * This function is called when current page is customizer preview 
	 * and user has permission 'edit_theme_options'
	 *
	 * @param WP_Customize_Manager $wpc
	 */
	static public function customize_register($wpc) {

		if (current_user_can('edit_theme_options')) {

			if (isset($_REQUEST['wpdanceclaratheme-import']) && isset($_FILES['wpdanceclaratheme-import-file']))
				self::import($wpc);
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

	/**
	 * Import theme settings
	 * 
	 * @param WP_Customize_Manager $wpc
	 */
	static public function import($wpc) {
		
		# Validate nonce
		if (!wp_verify_nonce($_REQUEST['wpdanceclaratheme-import'], 'wpdanceclaratheme-import'))
			die("Invalid nonce");

		# Make sure WP support file uploading
		if (!function_exists('wp_handle_upload'))
			require_once(ABSPATH.'wp-admin/includes/file.php');

		# Get uploaded file
		$file = $_FILES['wpdanceclaratheme-import-file'];


		# Check if file uploaded got error
		if (isset($file['error']) && $file['error'] != UPLOAD_ERR_OK) {
			self::$message = $file['error'];
			return;
		}

		$wp_filesystem = \wpdanceclaratheme\Helper\wp_filesystem();

		$raw = @$wp_filesystem->get_contents($file['tmp_name']);
		$data = @json_decode($raw, true);

		

		# Check data
		if (!is_array($data)) {
			self::$message = esc_html__("Error settings file. Please check the uploaded file is settings file.", 'wpdanceclaratheme');
			return;
		}


		#
		# TODO: save import settings to database
		#

		$mods = (array)get_theme_mods();
		foreach ($mods as $k => &$mod) {

			# remove all theme mod start with 'wpdanceclaratheme_'
			if (preg_match('/^wpdanceclaratheme_/', $k))
				unset($mods[$k]);

		}

		# Update theme mods variables which defined in WP Setting
		$settings = $wpc->settings();
		foreach ($settings as $setting) {
			if (preg_match('/^wpdanceclaratheme_/', $setting->id) 
				&& array_key_exists($setting->id, $data) 
				&& $setting->type == 'theme_mod' 
				&& $setting->check_capabilities())
				$mods[$setting->id] = $setting->sanitize($data[$setting->id]);
		}

		# Remove all theme mods and update new
		remove_theme_mods();
		foreach ($mods as $k => $mod) {
			set_theme_mod($k, $mod);
		}

		self::$message = esc_html__("Imported theme settings successfully.", 'wpdanceclaratheme');

	}

}
endif; /* class Import_Control_Helper */



# =================================================================================================



if (!class_exists(__NAMESPACE__.'\\Import_Control')):
/**
 * Customizer Control for importing theme settings
 */
class Import_Control extends \WP_Customize_Control {

	public $type = 'wpdanceclaratheme_import';


	/**
	 * Enqueue javascripts & styles used in this control
	 */
	public function enqueue() {
		wp_enqueue_script('wpdanceclaratheme-customizer');
		wp_enqueue_style('wpdanceclaratheme-customizer');
	}

	/**
	 * Render the control HTML markup
	 */
	protected function render_content() {
		?>

		<span class="customize-control-title">
			<?php esc_html_e("Import theme settings", 'wpdanceclaratheme'); ?>
		</span>
		
		<span class="description customize-control-description">
			<?php esc_html_e('Upload a file to import settings for this theme.', 'wpdanceclaratheme'); ?>
		</span>
		
		<div class="form-fields">
			<?php wp_nonce_field('wpdanceclaratheme-import', 'wpdanceclaratheme-import'); ?>
			<input type="file" name="wpdanceclaratheme-import-file" />
		</div>
		<button class="button submit-button"><?php esc_html_e("Import", 'wpdanceclaratheme'); ?></button>
		<div class="uploading-msg"><?php esc_html_e("Uploading...", 'wpdanceclaratheme'); ?></div>

		<?php
	}
}

endif; /** class Import_Control */

