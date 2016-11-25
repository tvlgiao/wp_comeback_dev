<?php
namespace wpdanceclaratheme\Customizer;


if (!class_exists(__NAMESPACE__.'\\Multiple_Select_Control')):
/**
 * Multiple Select Control class
 *
 * Display select box with ability to select multiple options in Customizer
 */
class Multiple_Select_Control extends \WP_Customize_Control {
	public $type = 'wpdanceclaratheme_multiselect';

	public $size = 10;

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
				<select <?php $this->link(); ?> multiple="multiple" size="<?php echo esc_attr($this->size); ?>">
					<?php
					foreach ( $this->choices as $value => $label ) {
						$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
						echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
					}
					?>
				</select>
			</label>
		<?php
	}
}
endif;
