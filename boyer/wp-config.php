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
define('DB_NAME', 'boyer');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'efr4932');

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
define('AUTH_KEY',         'D+5]P0*>[2NG:)OQVQh~|/oWRn2yiL2l[0G.:xR|00c7&l<|T~eST !O1=&t.h_I');
define('SECURE_AUTH_KEY',  'n){Cu{PfpJCz3+Q&jvXa;m4DJIlk7FVeI#IIa(4hG}VGG^NR ?k-|Gcx,s79H$0+');
define('LOGGED_IN_KEY',    'SoKYccFZ9-S-]#GT4-[[qA&{R%=kHo?`6YR+m6sJ-yx]`L*9|];iAJ_|EvmW*B59');
define('NONCE_KEY',        'pa;@/F@L$_R0F+`qPI@ixd32M31]8- gJa0)I@D|m#z1[HUM3G6;9vN;MmC%h&:Z');
define('AUTH_SALT',        '&1.;uFa)2~bqpf[_gC (x({-D>GJ[nLtNNhP#jY[5v560-%quac3[fjEdJt?dv]+');
define('SECURE_AUTH_SALT', 'Z[W;4(pOvHjK-TB-qn!f+gyYg4,2|ko+h!66_w^W3F]e2`K&a&acaFm-}RjcMjCl');
define('LOGGED_IN_SALT',   'N[93tz`:izfDF;,?e|g3].>3zQ_Td>HZTT8[?A[x@n6|ByTEL*OeIPDych+aDxHJ');
define('NONCE_SALT',       'iuEqV]Ub*w_GZe;+LzD~JWd,-8d)EY>Nfg1d* fv.v>[[/^,m[(M)F^gDlS:=q {');

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
