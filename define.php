<?php
//Prevent directly browsing to the file
if (function_exists('plugin_dir_url'))
{
	// Prohibit direct script loading.
	defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );
	define( 'TQT_BASENAME','treitusQuote');
	// Define certain plugin variables as constants.
	define( 'TQT_ABSPATH', plugin_dir_path( __FILE__ ) );

	define( 'TQT_URL', get_site_url(). '/wp-content/plugins/treitusQuote/' );
	define( 'TQT_URL_UPLOADS', get_site_url(). '/wp-content/uploads/'.TQT_BASENAME.'/' );
	define( 'TQT_PATH_UPLOADS',ABSPATH . 'wp-content/uploads/'.TQT_BASENAME.'/' );

	define( 'TQT_MINIMUM_WP_VERSION', '3.7' );


}
