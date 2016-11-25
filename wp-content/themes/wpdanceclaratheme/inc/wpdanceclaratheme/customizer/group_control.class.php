<?php
namespace wpdanceclaratheme\Customizer;


if (!class_exists(__NAMESPACE__.'\\Group_Control')):
/**
 * Customizer Group Control class
 *
 * Used to group other controls
 */
class Group_Control extends \WP_Customize_Control {

	public $type = 'wpdanceclaratheme_group';

	/**
	 * Enqueue javascripts & styles used in this control
	 */
	public function enqueue() {
		wp_enqueue_script('wpdanceclaratheme-customizer');
		wp_enqueue_style('wpdanceclaratheme-customizer');
	}


	protected function render_content() {
		?>
		
		<?php if (!empty($this->label)): ?>
		<div class="customize-control-title"><?php echo esc_html($this->label); ?></div>
		<?php endif; ?>

		

		<ul class="customize-control-content">
			<?php if (!empty($this->description)): ?>
			<li class="description customize-control-description"><?php echo $this->description; ?></li>
			<?php endif; ?>
		</ul>
		<?php
	}
}
endif;

