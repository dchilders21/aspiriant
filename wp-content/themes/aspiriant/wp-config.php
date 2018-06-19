<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'aspiriant_wordpress');

/** MySQL database username */
define('DB_USER', 'catherine');

/** MySQL database password */
define('DB_PASSWORD', 'kailin');

/** MySQL hostname */
define('DB_HOST', 'carpturn.ipowermysql.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'K=Io$T^FPq&L+G}2Sqp8*WbkrQJi85AIKnh00fvSwY mM`+| 7OyC-b~fzZ9<sU1');
define('SECURE_AUTH_KEY',  'XQG7 dt;^J}(.v0([-vip!;M/pClOH,2$H}(RwafAA.4Rqm2t+;D7jrGt)ObH1x$');
define('LOGGED_IN_KEY',    'Kp2h9f#jh^]&+I9?mfyvNJv2QmdCwQ(,WCem_2VLW7z%QrH8$a?NuB6?-pSL4)n-');
define('NONCE_KEY',        'N/s|(/Z$`Y]%Jis}u,+Q+,=wphT.!.nfP+9W<>.!KLG2r~nv oD]m(8v)|>H? }+');
define('AUTH_SALT',        '/dm|jNJT.,8NuF-fQJ>(n,xBkEy^4M4R1xyHoi%,{+${|3+2uFMtIob+8-mPFk`^');
define('SECURE_AUTH_SALT', '66}(Obb&*3r4;,E|tmT$|%>n5m%Om3>P,AMzuw8P&Vw]?wrX]W m$1a4*Mr#6_Wl');
define('LOGGED_IN_SALT',   'X-Q1)zv[YrXPa~FK%N14Wx6HA3&tb0L8C[,-1t--+6s1*^v]wt*Ppyd@+-CjWB]$');
define('NONCE_SALT',       'peYh- n|@t(r#UE4UMg5MssB7Cu&+|yS^,%}Z&a=GkU-IY{T 54TH:o@_x@{<Zpx');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
