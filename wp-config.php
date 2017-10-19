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
define('DB_NAME', 'wifieud6_shcdemo');

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
define('AUTH_KEY',         '&WE0qp}IwgXpj+or1XZ!-v!gXzqNr]`ucSO6?Vebs,EBx89C@fmf])j~.5dd_D`q');
define('SECURE_AUTH_KEY',  'z9C1l}O*y9s]:goY,N;e{bBT_4OJEqhvarwxx5f3-T#Xi~0^#rF;O/=6{ZL!tA}y');
define('LOGGED_IN_KEY',    '9SD^7`4?%j@B{>`/5CDah*=vVxEUucu%mB[pZ.%`J*e%4(lFU!3 q%Avl3WigDln');
define('NONCE_KEY',        '15BYet<?-KD;d_t:wJ@x;8Tk/~k;61f>{VsSIUa* MYasS-Ka%:ut[Zg0kkISb|L');
define('AUTH_SALT',        'K`hjn=B0rVt{2c1*K_XXm7ilfoh||Mq4g&b?fn6E|[a}WXPJ3+k+t>Pcpzs6dhaX');
define('SECURE_AUTH_SALT', 'A8x? vi*6 el#HAilffh[5a/Nm(RhoH]P%M6dUn_`R809#UxUGwY QwStSO0ft>m');
define('LOGGED_IN_SALT',   'GaV91o9({[q]*s6ut$<aXOu}B[u#?8io#AYUxvQ$BJofla},xZzAE&OA}_ot+@&-');
define('NONCE_SALT',       '-[JC|He:ZPXc?2/Q{ZN(cE|$Ng5BC}me=H,)+g}6z4T5;jxmJ:E:kE_3Wj~zl{}1');

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
