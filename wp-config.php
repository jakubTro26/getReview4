<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'woo' );

/** Database username */
define( 'DB_USER', 'woo' );

/** Database password */
define( 'DB_PASSWORD', 'eS1zU4xK4f' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'yAr+g9cT#OC|7$T*_qQ^UPma`1C-|aO9^Z0CA>(k:ZBI!E74lW_UYtHkT=kX+[eU' );
define( 'SECURE_AUTH_KEY',  '2kU[r_:Oh]z#BD8FV@5oy/Ayv=6,>w0uxnp)qmH+S4ai;$dAG)5b0S6-#(:>QAh6' );
define( 'LOGGED_IN_KEY',    'O}2+/H2$f*+6Tj?$qvl x6X8e}h!sk5Z{3h8nKY>h S2!O.yxva,Zwo_#KXBUK.I' );
define( 'NONCE_KEY',        'L~h?x-qX38P{2Pkl)rC_AI|K>@f9B?Ch6a/dpO$}bMkot7GNv-q|H!}q/00*5S#^' );
define( 'AUTH_SALT',        '$UC.pK0XnRkPG/?GE!5~v1rMnF[,&Foi{6CN,pt;7#O#uTGNyWcye4C%nwMi?qJ5' );
define( 'SECURE_AUTH_SALT', 'jgNGjC+EaP#TTrUL/DTni)DHTtt0>{k7LM,`f_JPPd=v}0^BjQT#~),/nJBTl5?K' );
define( 'LOGGED_IN_SALT',   'YqZgZFipz)Nt7qzgLzM3A3][,IW1|])PHL{#K(4NP8B#QyCP+y$;)b7Ja`wX`60r' );
define( 'NONCE_SALT',       '`nI(WQ2x_0g)z@d$T}zUghGMNSl~J)uu7,YA^zxM~F7nm=T~n<1)5td7`7=%kje3' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );
error_reporting( E_ERROR | E_WARNING | E_PARSE );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
