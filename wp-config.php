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
define('DB_NAME', 'disinfect');

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
define('AUTH_KEY',         '7qHi1kfioGX(2M.h>s }/Wg)! Bcd#+wDp`5W^KYzD}[&kpi$W)2Fib.Q:Bv/&B-');
define('SECURE_AUTH_KEY',  'qIc<I].S%L8ib!$YEx8BGCq}gfA#tz=OG4}?wGl>,8Xl?Fhd)=Y3JSSL%WMAm}Ct');
define('LOGGED_IN_KEY',    '~c`-@_ikhBLGHmYN`kq a3l`O !*Ik`I.|570&w}X[R[#%xd& SZ @|qMUF5-wEF');
define('NONCE_KEY',        'sRk*{j.BigAVypn8?oi<}(98Tm<BX[sZa5P,uyE{GQLccLxG,nkL/Gm^R`}[vE(+');
define('AUTH_SALT',        '^9?F;D D .#2V/4ZE%26r?5{&ch!?j?[MwStp74Eh*C4J vP6_92Ypp]^UO7j_Q:');
define('SECURE_AUTH_SALT', 'HD78:dsc?h%@?pc..B8M6pZbG;nz+TAN&dS&DBoVK_:P.O).5W[:t=iUqC;w`fWq');
define('LOGGED_IN_SALT',   'kb%M$v/LacLojjfI/$d;*(>5D[!.aNijW4WPo/#<.!c-C+]G9r8K0DEDZ{lqk{Ny');
define('NONCE_SALT',       ':*~f69Joh/Xu:<)[;QmK_hz1*HWK,lF%yI5_6&w[}<A;-x=T0jD3.{l+oY<RuGa:');

/**#@-*/

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
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
