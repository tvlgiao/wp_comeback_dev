<?php
namespace wpdanceclaratheme\Customizer;

class HTMLBlock_Control extends \WP_Customize_Control {


	public $type = 'wpdance_htmlblock';


	/**
	 * Which HTMLBlock to show (header or footer)
	 *
	 * @var string $htmlblock_type 'header' or 'footer'
	 */
	public $htmlblock_type = 'header';


	/**
	 * @var array htmlblock array
	 * @see wpdance\Plugins\htmlblock\get_list()
	 */
	protected $htmlblock_list;

	/**
	 * Constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {

		parent::__construct($manager, $id, $args);

		switch ($this->htmlblock_type) {
			case 'header':
				$filter = '/^(wpdanceclaratheme-)?header/i';
				break;
			
			case 'footer':
				$filter = '/^(wpdanceclaratheme-)?footer/i';
				break;

			default:
				$filter = '/.*/';
				break;
		}

		// Plugin wpdance-htmlblock is activated?
		if (function_exists('\\wpdance\\Plugins\\htmlblock\\get_list'))
			$this->htmlblock_list = \wpdance\Plugins\htmlblock\get_list($filter);
		else
			$this->htmlblock_list = array();

		foreach ($this->htmlblock_list as $slug => $item)
			$this->choices[$slug] = $item['title'];
	}

	/**
	 * Enqueue javascripts & styles used in this control
	 */
	public function enqueue() {
		wp_enqueue_script('wpdanceclaratheme-customizer');
		wp_enqueue_style('wpdanceclaratheme-customizer');
	}

	public function render_content() {

		// parent::render_content();
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
				foreach ($this->htmlblock_list as $slug => $item)
					echo '<option value="' . esc_attr($slug) . '"' . selected($this->value(), $slug, false) . '>' . esc_html($item['title']) . '</option>';
				?>
			</select>
		</label>



		<?php foreach ($this->htmlblock_list as $slug => $item): ?>
		<div class="wpdanceclaratheme-htmlblock-preview" id="<?php echo esc_attr($this->id).'_'.esc_attr($slug); ?>">
			<img src="<?php echo esc_attr($item['preview']); ?>" alt="<?php echo esc_attr($item['title']); ?>" title="<?php echo esc_attr($item['title']); ?>" data-post-id="<?php echo esc_attr($slug); ?>" />
			<div class="note"><?php echo $item['note']; ?></div>
			<a href="<?php echo esc_attr($item['edit_link']); ?>" class="edit"><?php esc_html_e("Edit this block", 'wpdanceclaratheme'); ?></a>
		</div>
		<?php endforeach; ?>


		<?php
	}
}
