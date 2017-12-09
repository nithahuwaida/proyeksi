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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'hidroponiksragen');

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

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'MW6%[`]UkeY(uilWs`=*;I(W/eK6Jf sD^I<![bz(([>ik+ZMlrl9eucn~=:go4m');
define('SECURE_AUTH_KEY',  'V6EOu+]-yyz}53X~73:kAyECYKmvX<,3P/`M;npO.NhP1Y~TYgo|74U`:8JM%%%w');
define('LOGGED_IN_KEY',    '>23CabFy8)gxI[=Mv^]@K4iBFtL*hx&9oemoenO6gAz=Nc>^dLcLF7{<:N*SVY:>');
define('NONCE_KEY',        '}_~tnQjY^@=voDh}!IVb]48i*4Kg0|/~| &jwB3rl.z)})z59w#`??.iE.SYu~V$');
define('AUTH_SALT',        '9VG|LcDy[VlVX>F8k5z*;8lK(Ufst8SefDTzVj8`i-}6$yD<PFv~3RUPdIWwik2+');
define('SECURE_AUTH_SALT', 'R381z!Q?4[ba)NO]J|.j0$3hZ@S*EeL}[^z}3?+g`&OZ93o8ECRxL&eg/Lyx#9}6');
define('LOGGED_IN_SALT',   'bCMu=!#MQQGk_}7X36aW=;+SB=dC1ajc.&FRl^WLg1h2LCTX$z8H0`)@=4cddFL/');
define('NONCE_SALT',       '.U$T(1@z+nj(f0ww<`x=vF-BZgIZu,O`,_=*]px#`{||1:wN#be+LH!(%bO};s/~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'hs_';

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
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
