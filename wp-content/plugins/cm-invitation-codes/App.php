<?php

namespace com\cminds\registration;

use com\cminds\registration\model\Labels;

use com\cminds\registration\core\Core;

use com\cminds\registration\controller\SettingsController;

use com\cminds\registration\model\Settings;

require_once dirname(__FILE__) . '/core/Core.php';

class App extends Core {
	
	const VERSION = '1.5.1';
	const PREFIX = 'cmreg';
	const SLUG = 'cm-registration';
	const PLUGIN_NAME = 'CM Registration';
	const PLUGIN_WEBSITE = 'https://www.cminds.com/store/cm-registration-and-invitation-codes-plugin-for-wordpress/';
	const TESTING = false;
	
	
	static function bootstrap($pluginFile) {
		parent::bootstrap($pluginFile);
	}
	
	
	static protected function getClassToBootstrap() {
		$classToBootstrap = array_merge(
			parent::getClassToBootstrap(),
			static::getClassNames('controller'),
			static::getClassNames('model'),
			static::getClassNames('metabox')
		);
		if (static::isLicenseOk()) {
			$classToBootstrap = array_merge($classToBootstrap, static::getClassNames('shortcode'), static::getClassNames('widget'));
		}
		return $classToBootstrap;
	}
	
	
	static function init() {
		parent::init();
		
		wp_register_script('cmreg-utils', static::url('asset/js/utils.js'), array('jquery'), static::VERSION, true);
		wp_register_script('cmreg-backend', static::url('asset/js/backend.js'), array('jquery'), static::VERSION, true);
		wp_register_script('cmreg-recaptcha', 'https://www.google.com/recaptcha/api.js');
		wp_register_script('cmreg_account_verification', static::url('asset/js/account-verification.js'), array('jquery', 'cmreg-utils'), static::VERSION, true);
		wp_register_script('cmreg-logout', static::url('asset/js/logout.js'), array('jquery', 'heartbeat'), static::VERSION, true);
		
		wp_register_style('cmreg-settings', static::url('asset/css/settings.css'), null, static::VERSION);
		wp_register_style('cmreg-backend', static::url('asset/css/backend.css'), null, static::VERSION);
		wp_register_style('cmreg-frontend', static::url('asset/css/frontend.css'), array('dashicons'), static::VERSION);
		
		wp_register_script('cmreg-frontend', static::url('asset/js/frontend.js'), array('jquery', 'cmreg-utils', 'cmreg-recaptcha'), static::VERSION, true);
		
	}
	

	static function admin_menu() {
		parent::admin_menu();
		$name = static::getPluginName(true);
// 		$page = add_menu_page($name, $name, 'manage_options', static::PREFIX,
// 			array(App::namespaced('controller\SettingsController'), 'render'), 'dashicons-admin-users', 5679);
		add_menu_page($name, $name, 'manage_options', static::PREFIX); //, array(App::namespaced('controller\SettingsController'), 'render'));
	}
	
	
}
