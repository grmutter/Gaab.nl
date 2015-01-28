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
define('DB_NAME', 'gaab');

/** MySQL database username */
define('DB_USER', 'grmutter');

/** MySQL database password */
define('DB_PASSWORD', 'grmutter51987');

/** MySQL hostname */
define('DB_HOST', 'localhost:3351');

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
define('AUTH_KEY',         'f!TR|?4non|KG11?B Sh$hO?lxo1`CT/s{SkjX]vt+aR@D{A@t|&-Q@N`_ttEvQB');
define('SECURE_AUTH_KEY',  'by_jN.AZl[<[/<.90_=>0yl4$-jA%K`%Cud*cj1`|g0RaaD-9sqnn(pzNJkQ%hqy');
define('LOGGED_IN_KEY',    'RHGf:Ba%JLe_nDrqFE[AA<`i5hyI(-xR1Y[)K EYrCVeAavO[w*&6LPUM]|GjcXg');
define('NONCE_KEY',        'YS4>4jWY4SB2<xR>GdT;+ %4z)!DsrU<0Cao4)aDztyHD]]pE*?Sr#[TwG&@TCRh');
define('AUTH_SALT',        'LtQYEkph1SEGAJ5CJv;jLF%qC)T8H;vS+b{:d]-K[N#cr}b+~~>^^<#ZO-FVbT8:');
define('SECURE_AUTH_SALT', 'v-Z;{n_iu!hdbE*;hN%iE(kPSH<-<pokwt{h<q=fyd|k*qT+Z4+?oEt}O`~+WrR ');
define('LOGGED_IN_SALT',   '9moEqSD<By@+@(8,|^v|Y?t&,;-~2zcrv]G<Y*bF/k>q@nbEMaN^-,2iyySSUede');
define('NONCE_SALT',       'J~:^|Dz f9J .S-{X:Kx*4[0)*fPkVX]&O8es*3M+5sA{(xn}o 5u||_Znxv}B=n');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'gr_';

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
