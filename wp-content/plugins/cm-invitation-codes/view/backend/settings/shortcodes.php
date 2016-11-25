<?php

use com\cminds\registration\shortcode\RegistrationButtonShortcode;

use com\cminds\registration\App;

use com\cminds\registration\shortcode\RegistrationFormShortcode;

use com\cminds\registration\shortcode\LostPasswordShortcode;

use com\cminds\registration\shortcode\LoginFormShortcode;

use com\cminds\registration\shortcode\LoginAndRegisterButtonShortcode;

?>
<li><kbd>[<?php echo LoginAndRegisterButtonShortcode::SHORTCODE_NAME; ?>]</kbd> - shortcode displays login button, optionally you can pass the button's text: <kbd>[cmreg-login]<strong>Text</strong>[/cmreg-login]</kbd></li>
<?php if (App::isPro()): ?>
	<li><kbd>[<?php echo LoginFormShortcode::SHORTCODE_NAME; ?> registration-url="/registration" registration-link="Click here to register"]</kbd>
		- shortcode displays the login form. Optional attributes will produce a link to the registration page. You can also pass text or HTML that will be displayed
			for the logged-in users instead of the login form by putting it between the shortcode tags:
			[<?php echo LoginFormShortcode::SHORTCODE_NAME; ?>]You are already logged-in[/<?php echo LoginFormShortcode::SHORTCODE_NAME; ?>]</li>
	<li><kbd>[<?php echo RegistrationFormShortcode::SHORTCODE_NAME; ?> login-url="/login" login-link="Click here to login"]</kbd>
		- shortcode displays the registration form. Optional attributes will produce a link to the login page. You can also pass text or HTML that will be displayed
			for the logged-in users instead of the registration form by putting it between the shortcode tags:
			[<?php echo RegistrationFormShortcode::SHORTCODE_NAME; ?>]You are already logged-in[/<?php echo RegistrationFormShortcode::SHORTCODE_NAME; ?>]</li>
	<li><kbd>[<?php echo LostPasswordShortcode::SHORTCODE_NAME; ?>]</kbd> - shortcode displays lost password form.</li>
	<li><kbd>[<?php echo RegistrationButtonShortcode::SHORTCODE_NAME; ?>]</kbd> - shortcode displays the registration button and the registration form is being opened in the overlay after clicking the button.</li>
<?php endif; ?>
<li>You can add the login action on any link using the following href attributes:
	<kbd>#cmreg-login-click</kbd>,
	<kbd>#cmreg-only-login-click</kbd>,
	<kbd>#cmreg-only-registration-click</kbd>
</li>