<?php
namespace wpdanceclaratheme\Customizer;


if (!class_exists(__NAMESPACE__.'\\Font_Control')):
/**
 * Customizer Font Control class
 *
 * Display fonts combobox on WP Customizer
 */
class Font_Control extends \WP_Customize_Control {
	
	public $type = 'wpdanceclaratheme_font';

	/**
	 * Enqueue javascripts & styles used in this control
	 */
	public function enqueue() {
		wp_enqueue_script('wpdanceclaratheme-customizer');
		wp_enqueue_style('wpdanceclaratheme-customizer');
	}

	public function to_json() {
		parent::to_json();
		$this->json['google_fonts'] = \wpdanceclaratheme\Helper\get_google_fonts();
	}

	public function render_content() {}

	public function content_template() {
		?>

		<# if (data.label) { #>
			<span class="customize-control-title">{{{ data.label }}}</span>
		<# } #>
		<# if (data.description) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<select>
			<optgroup label="<?php esc_html_e("Web Fonts", 'wpdanceclaratheme'); ?>">
				<option value=""></option>
				<option value="inherit">inherit</option>
				<option value="Arial, Helvetica, sans-serif">Arial, Helvetica, sans-serif</option>
				<option value='"Arial Black", Gadget, sans-serif'>"Arial Black", Gadget, sans-serif</option>
				<option value='"Comic Sans MS", cursive, sans-serif'>"Comic Sans MS", cursive, sans-serif</option>
				<option value="Impact, Charcoal, sans-serif">Impact, Charcoal, sans-serif</option>
				<option value='"Lucida Sans Unicode", "Lucida Grande", sans-serif'>"Lucida Sans Unicode", "Lucida Grande", sans-serif</option>
				<option value="Tahoma, Geneva, sans-serif">Tahoma, Geneva, sans-serif</option>
				<option value='"Trebuchet MS", Helvetica, sans-serif'>"Trebuchet MS", Helvetica, sans-serif</option>
				<option value="Verdana, Geneva, sans-serif">Verdana, Geneva, sans-serif</option>
				<option value="Georgia, serif">Georgia, serif</option>
				<option value='"Palatino Linotype", "Book Antiqua", Palatino, serif'>"Palatino Linotype", "Book Antiqua", Palatino, serif</option>
				<option value='"Times New Roman", Times, serif'>"Times New Roman", Times, serif</option>
				<option value='"Courier New", Courier, monospace'>"Courier New", Courier, monospace</option>
				<option value='"Lucida Console", Monaco, monospace'>"Lucida Console", Monaco, monospace</option>
			</optgroup>
			<# if (data.google_fonts && data.google_fonts instanceof Array) { #>
				<optgroup label="<?php esc_html_e("Google Fonts", 'wpdanceclaratheme'); ?>">
				<# for (var i = 0; i < data.google_fonts.length; i++) { #>
					<option value="{{{ data.google_fonts[i].family }}}">{{{ data.google_fonts[i].family }}}</option>
				<# } #>
				</optgroup>
			<# } #>

		</select>

		<?php
	}
}
endif;

