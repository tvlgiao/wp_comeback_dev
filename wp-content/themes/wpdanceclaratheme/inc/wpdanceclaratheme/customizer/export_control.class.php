<?php
namespace wpdanceclaratheme\Customizer;


add_action('customize_register', __NAMESPACE__.'\\Export_Control_Helper::customize_register');


if (!class_exists(__NAMESPACE__.'\\Export_Control_Helper')):
/**
 * Helper class for processing export theme settings
 */
class Export_Control_Helper {

	static public function customize_register($wpc) {

		if (current_user_can('edit_theme_options')) {

			if (isset($_REQUEST['wpdanceclaratheme-export']))
				self::export($wpc);
		}
	}

	static public function export($wpc) {

		# Validate nonce
		if (!wp_verify_nonce($_REQUEST['wpdanceclaratheme-export'], 'wpdanceclaratheme-export'))
			die("Invalid nonce");

		# Get theme settings
		$mods = (array)get_theme_mods();
		$mods = json_encode($mods);

		$theme = get_stylesheet();
		$charset = get_option('blog_charset');

		# Set header for download file
		header('Content-disposition: attachment; filename=' . $theme . '-settings-export.txt');
		header('Content-Type: application/octet-stream; charset=' . $charset);

		echo $mods;
		die();
	}

}
endif;





if (!class_exists(__NAMESPACE__.'\\Export_Control')):
/**
 * WP Customizer Control for exporting theme settings
 */
class Export_Control extends \WP_Customize_Control {

	public $type = 'wpdanceclaratheme_export';

	protected function render_content() {

		$export_url = add_query_arg('wpdanceclaratheme-export', wp_create_nonce('wpdanceclaratheme-export'), admin_url('customize.php'));
		?>

		<span class="customize-control-title">
			<?php esc_html_e("Export theme settings", 'wpdanceclaratheme'); ?>
		</span>
		
		<span class="description customize-control-description">
			<?php esc_html_e("Export all themes settings for back up or upgrade site. You can import back this file when needed. Note: It only exports settings belong to the theme, exclude site's other settings.", 'wpdanceclaratheme'); ?>
		</span>

		<a href="<?php echo esc_attr($export_url); ?>" class="button submit-button"><?php esc_html_e("Export", 'wpdanceclaratheme'); ?></a>

		<?php
	}
}
endif;



