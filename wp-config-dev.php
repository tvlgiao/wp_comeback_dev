<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * $link https://codex.wordpress.org/Editing_wp-config.php
 *
 * $package WordPress
 */

define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/Volumes/HDD2/work/wp_comeback_dev/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_comeback_dev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#$+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {$link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * $since 2.6.0
 */
define('AUTH_KEY',         '_KIgN46rOR<g(CmJB!e?Uts.*jl+>2/d`:`g`#LBQ#b1xd*j6:mo+e>vSM,CseDh');
define('SECURE_AUTH_KEY',  'p;>gG56VlA)wc<{ZDRE;28fElG_5<|xYPkK/-,R/7/>8?N9f[8r!T3G|-6n0*b}9');
define('LOGGED_IN_KEY',    ';6>)WMK78MB--d{OB_Pd|k^h.z$<?FN5,)Lb%pBha_I-%$|d9RjCUphjaKgcN0]x');
define('NONCE_KEY',        '(B`mY{! N&$ABydC!d#s0}_7/i}R|# [)O1.j.3[M-G&pp_OvAb7;5T&2YgtE2GS');
define('AUTH_SALT',        'H6_0tJ|%&i5SjCXF tK!tayY0(uT6|Tk6d>_)|lgX.-m:_v=|1z+&Aspk&?AbvAG');
define('SECURE_AUTH_SALT', 'v]fP#0/]lKc.: p!vw ( ufp$,YPU2=wD(N~LSnzx9270:SZ_a+IbZ2{hHTQ5.aO');
define('LOGGED_IN_SALT',   '2<PZp+DFNE^H2wt^CxG(9=xv.sw mB3PIqI3<e3+v)whXMU%E$3m%&E/9;|fa-=U');
define('NONCE_SALT',       'Ys.5b&U9xnc*Vgl|y6p/|tlEP+@@k$*K-Isv%va:VP@IOL%`WAo1U>T<f,ZK|SF0');

/**#$-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * $link https://codex.wordpress.org/Debugging_in_WordPress
 */
// define('WP_DEBUG', false);
// define('WP_DEBUG', true);
// define('SCRIPT_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
