<?php
error_reporting(0);

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

define( 'DB_NAME', 'triumphantcareso_Dbname23' );



/** MySQL database username */

define( 'DB_USER', 'root' );



/** MySQL database password */

define( 'DB_PASSWORD', '' );



/** MySQL hostname */

define( 'DB_HOST', 'localhost' );



/** Database Charset to use in creating database tables. */

define( 'DB_CHARSET', 'utf8' );



/** The Database Collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', '' );

define('DISABLE_WP_CRON', 'true');



define('WPLOGIN_KEYWORD', 'htav1010');



/* Recatpcha Access Key */

/* Update it using a working google Private key */

define('RECAPTCHA_PRIVATEKEY', '6LdmJ1goAAAAAA7CwvU4yLnmBAGjLcwMxYsu5xSW');

/* Update it using a working google Site key */

define('RECAPTCHA_SITEKEY',  '6LdmJ1goAAAAAHA_qhzK6Vhw6wifmEMCzxKajxWE');



/**#@+

 * Authentication Unique Keys and Salts.

 *

 * Change these to different unique phrases!

 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}

 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define('AUTH_KEY',         'RWrT)jsRYlIPQpdxXCQ/lf(*fMG6_9qkWmwMx+KB-_/B1eV7ve-xX.YGx2?#lfPM');
define('SECURE_AUTH_KEY',  'ME]Zeqf.(#p}I|5J%i^3qCnoMr>a8G#<w4WE%T<wt^L/J>w={F,0K(9G3}n0cHlp');
define('LOGGED_IN_KEY',    'CHdhh2IIcMpLNUoaz)<!-7xe3EW5t;5x+Z4+nH/O^Wc$tX(InT,]w73 &H:Ogp5y');
define('NONCE_KEY',        '6l1NOP~Co-&9r98N_3k9@YRa::bfwQ|>nFdn@cMGrSPQABGRU}IsFk,733aS$I(|');
define('AUTH_SALT',        '0=v&o +h%T[7vlq/hE4wQ-*1[mP$:1(sM+Lco?8|N5_l?h-};fZkkkSUZPj+Q1;`');
define('SECURE_AUTH_SALT', 'Sh2Cq?9&$~ZLF1V2:;8I8Gi!l-G0_{3ES{`fA,Oi$JrvZ.jGQNp*0ZSuF{.RD4Qa');
define('LOGGED_IN_SALT',   '%Bt+%2|YlF-)2x72Wf,Ia/aRCk=MuY+Vf:xM`fFQ-*%USRUZ|m1Tsuji&!&(6=oA');
define('NONCE_SALT',       'd2$+ON0J+ni^M]5/Vj4]=f)}R!]aSvM7]}c;vuC%]8y998m`q;^+BCRTa7q}J?:x');


	

/**#@-*/



/**

 * WordPress Database Table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'triumphantcareky_';



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

define( 'WP_DEBUG', false );

define('DISALLOW_FILE_EDIT', true);

define( 'WP_CACHE', true ); // Added by WP Rocket

/* That's all, stop editing! Happy publishing. */



/** Absolute path to the WordPress directory. */

 if ( ! defined( 'ABSPATH' ) ) {

 	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

 }



/** Sets up WordPress vars and included files. */

require_once( SYSCONF_PATH . 'wp-settings.php' );

