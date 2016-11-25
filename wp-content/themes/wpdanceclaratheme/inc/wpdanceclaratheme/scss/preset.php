<?php
namespace wpdanceclaratheme\Scss\Preset;




if (!function_exists(__NAMESPACE__.'\\get_base_varnames')):
/**
 * Retrieve all variables' name declared in preset/_variables_base.scss
 *
 * @return array variables' name
 */
function get_base_varnames() {
	$wp_filesystem = \wpdanceclaratheme\Helper\wp_filesystem();
	$s = $wp_filesystem->get_contents(get_template_directory().'/scss/preset/_variables_base.scss');
	if (preg_match_all('/\$([a-z0-9_]+)\s*:/mUi', $s, $m)) {
		return $m[1];
	}
}
endif;




if (!function_exists(__NAMESPACE__.'\\get_base_vars')):
/**
 * Retrieve variables in preset/_variables_base.scss
 *
 * @return array all variables
 */
function get_base_vars() {

	$varnames = get_base_varnames();
	$vars = array();

	$scss = \wpdanceclaratheme\Scss\compiler();
	$scss->compile("@import 'preset/variables_base';");

	foreach ($varnames as $k) {
		if (array_key_exists($k, $scss->rootEnv->store)) {
			$vars[$k] = $scss->compileValue($scss->rootEnv->store[$k]);
			if ($vars[$k] == 'null') $vars[$k] = null;
		}
	}

	return $vars;
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_preset_vars')):
/**
 * Retrieve preset variables defined in 'presets/_variables_{preset}.scss' 
 * and 'presets/_variables_base.scss'
 *
 * @param string $preset preset name
 * @return array
 */
function get_preset_vars($preset = 'default') {
	if (!$preset)
		$preset = 'default';


	$varnames = get_base_varnames();
	$vars = array();

	$scss = \wpdanceclaratheme\Scss\compiler();
	$scss->compile("
		@import 'preset/variables_{$preset}';
		@import 'preset/variables_base';
	");

	foreach ($varnames as $k) {
		if (array_key_exists($k, $scss->rootEnv->store)) {
			$vars[$k] = $scss->compileValue($scss->rootEnv->store[$k]);
			if ($vars[$k] == 'null') $vars[$k] = null;
		}
	}
	
	return $vars;
}
endif;



if (!function_exists(__NAMESPACE__.'\\get_preset_names')):
/**
 * Get names of all available presets
 *
 * Find all files scss/preset/_variables_XXX.scss and return array of string XXX
 * 
 * @return array array of string, all name of presets
 */
function get_preset_names() {
	$presets = array();

	$fs = \wpdanceclaratheme\Helper\wp_filesystem();
	$dir = get_template_directory().'/scss/preset/';
	$files = $fs->dirlist($dir);
	if (is_array($files))
		foreach ($files as $name => $file)
			if (preg_match('/^_variables_(.+)\.scss$/', $name, $m) && $fs->is_file($dir.$name))
				if ($m[1] != 'base')
					$presets[] = $m[1];

	return $presets;
}
endif;


if (!function_exists(__NAMESPACE__.'\\preset_exists')):
/**
 * Check if a preset exists
 *
 * Return true if file scss/preset/_variables_{NAME}.scss exists
 * 
 * @param  string preset name
 * @return boolean
 */
function preset_exists($name) {

	if (!empty(\wpdanceclaratheme\Config::$presets)) {
		return in_array($name, \wpdanceclaratheme\Config::$presets);
	}
	else {
		$wp_filesystem = \wpdanceclaratheme\Helper\wp_filesystem();
		return $wp_filesystem->is_file(get_template_directory().'/scss/preset/_variables_'.$name.'.scss');
	}
}
endif;


if (!function_exists(__NAMESPACE__.'\\delete_all_presets_css')):
/**
 * Delete all completed preset CSS files (css/preset-*.css) and map files
 */
function delete_all_presets_css() {

	$wp_filesystem = \wpdanceclaratheme\Helper\wp_filesystem();
	
	$a = get_preset_names();
	foreach ($a as $s) {
		if ($wp_filesystem->is_file($f = get_template_directory().'/css/preset-'.$s.'.css'))
			$wp_filesystem->delete($f);

		if ($wp_filesystem->is_file($f = get_template_directory().'/css/preset-'.$s.'.css.map'))
			$wp_filesystem->delete($f);
	}

}
endif;


/**
 * Generate SCSS code defines class name preset for variable name in preset file
 *
 * Example: Variable '$style_general_color1' will has coresponding css class generated:
 *
 * <code>
 * .wpdanceclaratheme_style_general_color1 {
 *     @if $style_general_color1 { color: $style_general_color1; }
 * }
 * .wpdanceclaratheme_style_general_color1_important {
 *     @if $style_general_color1 { color: $style_general_color1 !important; }
 * }
 * </code>
 * 
 * @param  array    $vars preset variables returned from get_base_varnames()
 * @return string   SCSS code
 */
function generate_variable_helper_classes($vars) {
	$css = '';

	foreach ($vars as $var) {

		# Skip variable not start with 'style_'
		if (strpos($var, 'style_') !== 0) continue;

		$attr = '';

		$val = "\$$var";

		if (strpos($var, '_font_family'))               $attr = 'font-family';
		elseif (strpos($var, '_font_size'))             $attr = 'font-size';
		elseif (strpos($var, '_font_weight'))           $attr = 'font-weight';
		elseif (strpos($var, '_font_style'))            $attr = 'font-style';
		elseif (strpos($var, '_line_height'))           $attr = 'line-height';
		elseif (strpos($var, '_text_underline'))        $attr = 'text-decoration';
		elseif (strpos($var, '_text_transform'))        $attr = 'text-transform';
		elseif (strpos($var, '_color'))                 $attr = 'color';
		elseif (strpos($var, '_bgcolor'))               $attr = 'background-color';
		elseif (strpos($var, '_bgimage')) {             $attr = 'background-image'; $val = "url(\$$var)"; }
		elseif (strpos($var, '_bordercolor'))           $attr = 'border-color';
		elseif (strpos($var, '_borderradius'))          $attr = 'border-radius';



		$css .= ".wpdanceclaratheme_$var, %wpdanceclaratheme_$var {\n";
		$css .= "    @if \$$var { $attr: $val; }\n";
		$css .= "}\n";

		$css .= ".wpdanceclaratheme_{$var}_important, %wpdanceclaratheme_{$var}_important {\n";
		$css .= "    @if \$$var { $attr: $val !important; }\n";
		$css .= "}\n";

	}

	return $css;
}


/**
 * Generate SCSS @mixin code for preset variables start with 'style_'
 *
 * Example: Variable '$style_general_color1' will has coresponding scss mixin generated:
 *
 * <code>
 * @mixin style_general_color1 {
 *     @if $style_general_color1 { color: $style_general_color1; }
 * }
 * @mixin style_general_color1_important {
 *     @if $style_general_color1 { color: $style_general_color1 !important; }
 * }
 * </code>
 * 
 * @param  array    $vars preset variables returned from get_base_varnames()
 * @return string   SCSS code
 */
function generate_mixins($vars) {
	$css = '';


	$mixin_fonts = array();

	$known_attrs = array(
		'font_family'    => 'font-family',
		'font_weight'    => 'font-weight',
		'font_size'      => 'font-size',
		'font_style'     => 'font-style',
		'line_height'    => 'line-height',
		'text_underline' => 'text-decoration',
		'text_transform' => 'text-transform',
		'color'          => 'color',
		'bgcolor'        => 'background-color',
		'bgimage'        => 'background-image',
		'bordercolor'    => 'border-color',
		'borderradius'   => 'border-radius',
	);
	

	foreach ($vars as $var) {

		# Skip variable not start with 'style_'
		if (strpos($var, 'style_') !== 0) continue;

		foreach ($known_attrs as $k => $attr) {
			if (strpos($var, $k)) {

				if ($k == 'bgimage')
					$val = "url(\$$var)";
				else
					$val = "\$$var";

				$css .= "@mixin $var {\n";
				$css .= "    @if \$$var { $attr: $val; }\n";
				$css .= "}\n";

				$css .= "@mixin {$var}_important {\n";
				$css .= "    @if \$$var { $attr: $val !important; }\n";
				$css .= "}\n";
			}
		}

	}

	return $css;
}
