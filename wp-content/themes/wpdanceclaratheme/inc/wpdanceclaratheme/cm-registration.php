<?php
namespace wpdanceclaratheme\CMRegistration;

# Stop if the plugin is not activated
if (!class_exists('\\com\\cminds\\registration\\App')) return;


if (!function_exists(__NAMESPACE__.'\\login')) {
	/**
	 * replace login link on the the top navigation by ciusan login link
	 *
	 * @param array $str
	 * @return string
	 */
	function login($str) {
		
		$icon = preg_match('#<i class=".+".+/i>#miuU', $str, $m) ? $m[0] : '';

		if (get_option('users_can_register'))
			return sprintf('<li class="cmreg-login">%s</li>', $icon . do_shortcode('[cmreg-login]'.esc_html__("Login / Register", 'wpdanceclaratheme').'[/cmreg-login]'));
		else
			return sprintf('<li class="cmreg-login">%s</li>', $icon . do_shortcode('[cmreg-login]'.esc_html__("Login", 'wpdanceclaratheme').'[/cmreg-login]'));
	}
}
add_filter('wpdanceclaratheme_login_li', __NAMESPACE__.'\\login');



if (!function_exists(__NAMESPACE__.'\\register')) {
	/**
	 * replace register link on the the top navigation by ciusan register link
	 *
	 * @param array $str
	 * @return string
	 */
	function register($str) {
		return '';
	}
}
add_filter('wpdanceclaratheme_register_li', __NAMESPACE__.'\\register');


if (!function_exists(__NAMESPACE__.'\\logout')) {
	/**
	 * replace logout link on the the top navigation by ciusan logout link
	 *
	 * @param array $str
	 * @return string
	 */
	function logout($str) {
		$icon = preg_match('#<i class=".+".+/i>#miuU', $str, $m) ? $m[0] : '';

		return sprintf('<li class="cmreg-logout">%s</li>', $icon . do_shortcode('[cmreg-login]'.esc_html__("Logout", 'wpdanceclaratheme').'[/cmreg-login]'));
	}
}
add_filter('wpdanceclaratheme_logout_li', __NAMESPACE__.'\\logout');

