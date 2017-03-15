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
define('DB_NAME', 'blog');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123');

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
define('AUTH_KEY',         'smKC&-<.CsfL;0,VW2IywUcjdz@%)xpS.z9Bu`j+<UJHFyr5rm{f}JfEm#M`U!|(');
define('SECURE_AUTH_KEY',  ']<![,[k7?2[9wb[_M]kH|M;mZYR@T:Qr}:%y{9s1ycmyV~k%grOPlb=>U<bgRA+t');
define('LOGGED_IN_KEY',    'MsnJ[c8t:l<N>5T9(gkhrA~OI4{f!bD<1A/r^&wTu|$uEaES4GR]-%v93sAHNf@w');
define('NONCE_KEY',        'pu7Xh}AD&iE}0a&G&0c::;)qH}MaH$%Xu}~,o,J~?W!6[7pQuI=2:6IV@!:,k&ut');
define('AUTH_SALT',        '!d;r$@`MsNFS2]p{fzkZqt)DA~F(K4]b@dNH:R[).35Ki.CNO fC|VeG 7Y*RHw.');
define('SECURE_AUTH_SALT', '>tqgM``PcLp+ v[d(fV !^VUXOvnuYCtrJ7U3{MIo,G5Dp_Pk>8,OJkTUY[eG?^?');
define('LOGGED_IN_SALT',   'nV)zi8#Y]l<Za%#$^CA7J?}!n vaq//qLtP3|D-vgZ(LUnq%r_s<Ft}8$zxMc@~-');
define('NONCE_SALT',       'ro:cggP:#DQ,APcm+@Qh*THPJj2]!L+(X2=A#;uq~E!RsO9*c~aa]dUL/;oj*hQU');

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
