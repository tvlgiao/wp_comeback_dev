<?php
namespace wpdanceclaratheme\CiusanRegisterLogin;

/**
 * Stop if Ciusan Register Login plugin is not installed
 */
if (!function_exists('ciusan_login') || !function_exists('ciusan_register') || !function_exists('ciusan_logout')) return;



if (!function_exists(__NAMESPACE__.'\\ciusan_login')) {
	/**
	 * replace login link on the the top navigation by ciusan login link
	 *
	 * @param array $str
	 * @return string
	 */
	function ciusan_login($str) {
		return sprintf('<li>%s</li>', \ciusan_login());
	}
}
add_filter('wpdanceclaratheme_login_li', __NAMESPACE__.'\\ciusan_login');



if (!function_exists(__NAMESPACE__.'\\ciusan_register')) {
	/**
	 * replace register link on the the top navigation by ciusan register link
	 *
	 * @param array $str
	 * @return string
	 */
	function ciusan_register($str) {
		return sprintf('<li>%s</li>', \ciusan_register());
	}
}
add_filter('wpdanceclaratheme_register_li', __NAMESPACE__.'\\ciusan_register');


if (!function_exists(__NAMESPACE__.'\\ciusan_logout')) {
	/**
	 * replace logout link on the the top navigation by ciusan logout link
	 *
	 * @param array $str
	 * @return string
	 */
	function ciusan_logout($str) {
		return sprintf('<li>%s</li>', \ciusan_logout(array()));
	}
}
add_filter('wpdanceclaratheme_logout_li', __NAMESPACE__.'\\ciusan_logout');

