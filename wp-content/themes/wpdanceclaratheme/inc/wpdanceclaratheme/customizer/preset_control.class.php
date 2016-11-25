<?php
namespace wpdanceclaratheme\Customizer;



if (!class_exists(__NAMESPACE__.'\\Preset_Control')):
/**
 * Theme Preset Control class
 *
 * Display select box for choosing theme preset on Customizer.
 */
class Preset_Control extends \WP_Customize_Control {
	public $type = 'wpdanceclaratheme_preset';

	/**
	 * Enqueue javascripts & styles used in this control
	 */
	public function enqueue() {
		wp_enqueue_script('wpdanceclaratheme-customizer');
		wp_enqueue_style('wpdanceclaratheme-customizer');
	}

	public function __construct($manager, $id, $args = array()) {

		/**
		 * Array of presets available
		 *
		 * Find all files match with scss/preset/_variable_(.*).scss
		 * to populate presets array
		 *
		 * @var array $this->choices
		 */
		$this->choices = array();
		$a = \wpdanceclaratheme\Scss\Preset\get_preset_names();
		foreach ($a as $s)
			$this->choices[$s] = ucwords($s);


		parent::__construct($manager, $id, $args);

	}


	public function to_json() {
		parent::to_json();

		/**
		 * Read all variables in presets scss files to return json
		 *
		 * @var array $this->json['presets_vars']
		 */
		$presets_vars = array();
		foreach ($this->choices as $name => $preset)
			$presets_vars[$name] = \wpdanceclaratheme\Scss\Preset\get_preset_vars($name);
		$this->json['presets_vars'] = $presets_vars;

	}

	public function render_content() {
		if ( empty( $this->choices ) )
			return;
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>

			<select <?php $this->link(); ?>>
				<?php
				foreach ( $this->choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
				?>
			</select>
		</label>
		<?php
	}
}
endif;


