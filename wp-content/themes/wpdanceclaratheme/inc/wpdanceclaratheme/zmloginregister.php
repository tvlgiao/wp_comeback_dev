<?php
namespace wpdanceclaratheme\ZMLoginRegister;


/**
 * Stop if ZM Login Register plugin is not installed
 */
if (!defined('ZM_ALR_PATH')) return;


add_filter('zm_alr_localized_js', __NAMESPACE__.'\\zm_alr_localized_js');


if (!function_exists(__NAMESPACE__.'\\zm_alr_localized_js')):
/**
 * Ask ZM Login Register plugin handle our custom login/register link's classes
 *
 * @param array $localized
 * @return array
 */
function zm_alr_localized_js($localized) {
	
	if (!empty($localized['login_handle'])) 
		$localized['login_handle'] .= ", ";
	$localized['login_handle'] .= ".wpdanceclaratheme-zm-login-handler";
	
	if (!empty($localized['register_handle'])) 
		$localized['register_handle'] .= ", ";
	$localized['register_handle'] .= ".wpdanceclaratheme-zm-register-handler";
	
	return $localized;

}
endif;


