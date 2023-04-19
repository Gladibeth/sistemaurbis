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
define('DB_NAME', 'db_sistemaurbis');
/** Database username */
define('DB_USER', 'root');
/** Database password */
define('DB_PASSWORD', '');
/** Database hostname */
define('DB_HOST', 'localhost');
/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');
/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
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
define('AUTH_KEY', 'sCW/wGLqif4ee52UjoZPkYQl26WivzOsOgCed0b5E/TkFAFzZ4NretDQvTe5w12n');
define('SECURE_AUTH_KEY', '3Xi8imIiIJsjQsAVoVJUYctD0Dh+iSsUAaVxuxrKF11t0jxciMV9NYU/NbjtVSAS');
define('LOGGED_IN_KEY', 'tT2z3I1uE+o9WXj69f5EqCgEWf2DSqKpXuC+Yewosfv6IK7jG+DG9r4Wv4Fw4Kxs');
define('NONCE_KEY', 'ZfZQ5Y3pW7v/RPa8pa2WPR5t1eDKKfa28nx/AEyy37YZITJO11O2fVaZm1QyNXFJ');
define('AUTH_SALT', 'aUXqaO2yLKJURoRL27K8SUJR0mIwGHi04vd7h9va/ikAUJfJgKslQ5VLmjUhMAcp');
define('SECURE_AUTH_SALT', 'PDfKWssigpxEIJzbLJVWb7Tyqd8ggB8rMnacrkOvr6hf1zYPTO8r9zjPSNtHbLvo');
define('LOGGED_IN_SALT', 'wDkGa+cRQ3Vz/NOSPO7NczEMThj6a8chwZOu5bCxr86RnF33oRG2bRA1N2SMykrx');
define('NONCE_SALT', '0Ctyg51yEuhAiCyd9O3OaN7rR+pYKV7fRD1dFLdPHloDufPhiYPR4m39mCjLSc2x');
/**
* Other customizations.
*/
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');
/**#@-*/
/**
* WordPress database table prefix.
*
* You can have multiple installations in one database if you give each
* a unique prefix. Only numbers, letters, and underscores please!
*/
$table_prefix = 'dwcn5_';
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
/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
