<?php
namespace wpdanceclaratheme\Customizer;



if (!class_exists(__NAMESPACE__.'\\MacroCustomizer')):
/**
 * Class to help quickly add panel, section, controls, etc to customizer UI
 */
class MacroCustomizer {
	
	static public $instance = null;

	/**
	 * @var WP_Customize_Manager
	 */
	protected $wpc;

	/**
	 * @var array
	 */
	protected $vars = array();
	

	/**
	 * @var string
	 */
	protected $cur_panel;

	/**
	 * @var string
	 */
	protected $cur_section;

	/**
	 * @var int
	 */
	protected $group_inc = 0;

	static function instance() {
		if (is_null(self::$instance))
			self::$instance  = new MacroCustomizer();

		return self::$instance;
	}



	/**
	 * @var WP_Customize_Manager $wpc
	 * @return MacroCustomizer
	 */
	public function set_wp_customize_manager($wpc) {
		$this->wpc = $wpc;

		return $this;
	}


	/**
	 * @var array $vars
	 * @return MacroCustomizer
	 */
	public function set_vars($vars) {
		$this->vars = $vars;

		return $this;
	}


	/**
	 * Add new panel
	 *
	 * @var 	string 		$panel 		Panel ID
	 * @var 	string 		$title 		Panel Title
	 * @return  MacroCustomizer
	 */
	public function add_panel($panel, $title) {
		$this->cur_panel = $panel;
		$this->cur_section = '';

		$this->wpc->add_panel('wpdanceclaratheme_'.$this->cur_panel, array(
			'title' => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
		));

		return $this;
	}


	/**
	 * Add new section
	 *
	 * @var 	string 		$section 	Section ID
	 * @var 	string 		$title 		Section Title
	 * @return  MacroCustomizer
	 */
	public function add_section($section, $title) {
		$this->cur_section = $section;

		$this->wpc->add_section('wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section, array(
			'title'               => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
			'panel'               => 'wpdanceclaratheme_'.$this->cur_panel,
			//'priority'          => 200,
		));

		return $this;
	}


	public function add_group($title) {
		$setting = 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section.'__group'.$this->group_inc++;
		
		$this->wpc->add_setting($setting, array(
			'default' => ''
		));
		$this->wpc->add_control(new Group_Control($this->wpc, $setting, array(
			'label'               => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
			'section'             => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'            => $setting
		)));

		return $this;
	}

	public function add_control_text($control, $title, $desc = '') {
		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control;
		$setting = 'wpdanceclaratheme_'.$name;
		
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control($setting, array(
			'label'           => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
			'type'            => 'text',
			'description'     => $desc ? call_user_func('_x', $desc, 'customizer', 'wpdanceclaratheme') : '',
		));

		return $this;
	}



	public function add_control_image($control, $title, $desc = '') {
		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control;
		$setting = 'wpdanceclaratheme_'.$name;
		
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control(new \WP_Customize_Image_Control($this->wpc, $setting, array(
			'label'           => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
			'description'     => $desc ? call_user_func('_x', $desc, 'customizer', 'wpdanceclaratheme') : '',
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
		)));

		return $this;
	}



	/**
	 * Add new color control
	 *
	 * @var 	string 		$control	Control ID
	 * @var 	string 		$title 		Control Title
	 * @return  MacroCustomizer
	 */
	public function add_control_color($control, $title) {
		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control;
		$setting = 'wpdanceclaratheme_'.$name;
		
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control(new \WP_Customize_Color_Control($this->wpc, $setting, array(
			'label'               => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
			'section'             => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'            => $setting
		)));

		return $this;
	}



	/**
	 * Add new typography controls
	 *
	 * @var 	string 		$control	Control ID
	 * @var 	string 		$title 		Control Title
	 * @return  MacroCustomizer
	 */
	public function add_control_typo($control, $title) {

		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control.'_font_family';
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));

		$this->wpc->add_control(new Font_Control($this->wpc, $setting, array(
			'label'           => call_user_func('_x', $title." Font Family" , 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
		)));


		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control.'_font_size';
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control($setting, array(
			'label'           => call_user_func('_x', $title." Font Size", 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
			'type'            => 'text',
			'description'     => esc_html__("Font size in pixel &#40;px&#41;.", 'wpdanceclaratheme'),
		));

		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control.'_font_weight';
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control($setting, array(
			'label'           => call_user_func('_x', $title." Font Weight", 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
			'type'            => 'select',
			'choices'         => array(
				''            => '',
				'inherit'     => 'inherit',
				'light'       => 'light',
				'lighter'     => 'lighter',
				'normal'      => 'normal',
				'bold'        => 'bold',
				'bolder'      => 'bolder',
				'100'         => '100',
				'200'         => '200',
				'300'         => '300',
				'400'         => '400',
				'500'         => '500',
				'600'         => '600',
				'700'         => '700',
				'800'         => '800',
				'900'         => '900',
			),
		));

		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control.'_font_style';
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control($setting, array(
			'label'           => call_user_func('_x', $title." Font Style", 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
			'type'            => 'select',
			'choices'         => array(
				''            => '',
				'inherit'     => 'inherit',
				'normal'      => 'normal',
				'italic'      => 'italic',
			),
		));

		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control.'_line_height';
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control($setting, array(
			'label'           => call_user_func('_x', $title." Line Height", 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
			'type'            => 'text',
			'description'     => esc_html__("Input number include unit. e.i. 1.5, 1.5em, 30px", 'wpdanceclaratheme'),
		));

		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control.'_text_underline';
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => @$this->vars[$name]
		));
		$this->wpc->add_control($setting, array(
			'label'           => call_user_func('_x', $title." Text Underline", 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
			'type'            => 'select',
			'choices'         => array(
				''            => '',
				'inherit'     => '',
				'none'        => 'none',
				'underline'   => 'underline',
			),
		));

		return $this;
	}


	/**
	 * Add select control for Preset option
	 *
	 * @var string $control
	 * @var string $title
	 *
	 * @return MacroCustomizer
	 */
	public function add_control_preset($control, $title) {

		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control;
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => 'default',
		));
		$this->wpc->add_control(new Preset_Control($this->wpc, $setting, array(
			'label'           => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
		)));

		return $this;
	}



	/**
	 * Add multiple select control for selecting Google font subsets
	 *
	 * @var string $control
	 * @var string $title
	 *
	 * @return MacroCustomizer
	 */
	public function add_control_google_font_subsets($control, $title) {

		$name = $this->cur_panel.'_'.$this->cur_section.'_'.$control;
		$setting = 'wpdanceclaratheme_'.$name;
		$this->wpc->add_setting($setting, array(
			'default' => array('latin', 'latin-ext')
		));
		$this->wpc->add_control(new Multiple_Select_Control($this->wpc, $setting, array(
			'label'           => call_user_func('_x', $title, 'customizer', 'wpdanceclaratheme'),
			'section'         => 'wpdanceclaratheme_'.$this->cur_panel.'_'.$this->cur_section,
			'settings'        => $setting,
			'choices'         => array(
				'cyrillic'        => 'cyrillic',
				'vietnamese'      => 'vietnamese',
				'greek'           => 'greek',
				'latin-ext'       => 'latin-ext',
				'cyrillic-ext'    => 'cyrillic-ext',
				'latin'           => 'latin',
				'greek-ext'       => 'greek-ext',
			),
		)));

		return $this;
	}

}
endif;




