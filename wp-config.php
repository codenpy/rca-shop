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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'shop' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         'bS9+W^(g4g/z9*Sk[CsSW;$&y%G3(uhETTo[|9*5%8ELX2<#oMo~Y%]$Sw_i/.x8' );
define( 'SECURE_AUTH_KEY',  '=_|tdT6Mj`g,>rejafakNU} BY4e65An<O1!IjX#oT9}3,yQQz!rtt%Is*E;&D{@' );
define( 'LOGGED_IN_KEY',    'KC$ov3j(Zra%H*{{N|#Yjt5VD]p`kPKd8Q0StH|PSo_x*_F>fLYL7__v}R+quQ#N' );
define( 'NONCE_KEY',        'WYcXBX5^{*^Xn<nXj/|kQ}0bC/xY4I<yfmU4%idxRXU_lM:!^<`wubB(IvPk %e_' );
define( 'AUTH_SALT',        '+L&=q<*?@[v+/gW6__YDuB]a[^fs+56!Ko{Ordd39F*bE(S?@8q|x1b8 gs$v670' );
define( 'SECURE_AUTH_SALT', 'Nv?5qo_/diq=qlga2ag6rS.q#er5U.V^fT,z$mTLBvKg)/-c1kT#2[OlV&aP5S_;' );
define( 'LOGGED_IN_SALT',   'q.T7-3n=/$Sg.6)0W{cT)|_tb~FGSbXD)lT]adFk},^AE[D+8-Y/$AM.IrKD@G_P' );
define( 'NONCE_SALT',       'FKj^A*OOY~d_WC4AIK-U3oP232 y7,n<=F~P8SZ_`uLtV>G8-U*H3uEG^$Jj<?nX' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
define('WP_CACHE', false);
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
define( 'WP_DEBUG', false );
	
define( 'WP_AUTO_UPDATE_CORE', false );

define( 'AUTOSAVE_INTERVAL', 300 );
define( 'WP_POST_REVISIONS', 5 );
define( 'EMPTY_TRASH_DAYS', 7 );
define( 'WP_CRON_LOCK_TIMEOUT', 120 );
define( 'WP_MEMORY_LIMIT', '2048M' );
/* Add any custom values between this line and the "stop editing" line. */

#define( 'AUTOSAVE_INTERVAL', 300 );
#define( 'WP_POST_REVISIONS', 5 );
#define( 'EMPTY_TRASH_DAYS', 7 );
#define( 'WP_CRON_LOCK_TIMEOUT', 120 );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
