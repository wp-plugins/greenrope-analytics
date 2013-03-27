<?php

/*

Plugin Name: GreenRope Analytics

Version:     1.0

Plugin URI:  http://itegritygroup.com/wordpress/plugins/greenrope/

Description: Plugin to add GreenRope Analytics to the footer of your WordPress pages

Author:      ITegrity - Bytes, Inc.

Author URI:  http://itegritygroup.com/ , http://bytesinc.com/



*/



if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");



define('BYTE_ADDANALYTIC_DIR', dirname(__FILE__));

define('ADDANALYTIC_LOCAL_NAME', 'addanalytic');



// Pre-2.6 compatibility

if ( ! defined( 'WP_CONTENT_URL' ) )

      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );

if ( ! defined( 'WP_CONTENT_DIR' ) )

      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

if ( ! defined( 'WP_PLUGIN_URL' ) )

      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );

if ( ! defined( 'WP_PLUGIN_DIR' ) )

      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );



// Determine the location

$addanalytic_path = WP_PLUGIN_DIR.'/'.plugin_basename(dirname(__FILE__));

$addanalytic_url = WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__));



/*********************************************************************

*				Main Function (Do not edit)							*

********************************************************************/

add_action('wp_footer','byte_addanalytic');

function byte_addanalytic() {



	$addanalytic_settings = addanalytic_read_options();

	$greenrope_acct = stripslashes($addanalytic_settings[greenrope_acct]);


?>

	
	<!-- Start Greenrope Analytics -->

	<script language="JavaScript" type="text/javascript">	

	document.write('<img src="http://app.greenrope.com/wt.pl?a=<?php echo $greenrope_acct; ?>&r=' + window.document.referrer + '" height="1" width="1">')

	</script>
	<!-- End Greenrope Analytics -->
<?php	}







// Default Options

function addanalytic_default_options() {



	$ga_url = ".".parse_url(get_option('home'),PHP_URL_HOST);



	$addanalytic_settings = 	Array (

						'enable_plugin' => false,		// Enable plugin switch

						'greenrope_acct' => '',			// GreenRope account number

						);

	return $addanalytic_settings;

}



// Function to read options from the database

function addanalytic_read_options() 

{

	$addanalytic_settings_changed = false;

	

	//byte_addanalytic_activate();

	

	$defaults = addanalytic_default_options();

	

	$addanalytic_settings = array_map('stripslashes',(array)get_option('byte_addanalytic_settings'));

	unset($addanalytic_settings[0]); // produced by the (array) when there is nothing in the database

	

	foreach ($defaults as $k=>$v) {

		if (!isset($addanalytic_settings[$k]))

			$addanalytic_settings[$k] = $v;

		$addanalytic_settings_changed = true;	

	}

	if ($addanalytic_settings_changed == true)

		update_option('byte_addanalytic_settings', $addanalytic_settings);

	

	return $addanalytic_settings;



}





// This function adds an Options page in WP Admin

if (is_admin() || strstr($_SERVER['PHP_SELF'], 'wp-admin/')) {

	require_once(BYTE_ADDANALYTIC_DIR . "/admin.inc.php");



		// Add meta links

	function addanalytic_plugin_actions( $links, $file ) {

		static $plugin;

		if (!$plugin) $plugin = plugin_basename(__FILE__);

	 

		// create link

		if ($file == $plugin) {

			$links[] = '<a href="' . admin_url( 'options-general.php?page=addanalytic_options' ) . '">' . __('Settings', ADDANALYTIC_LOCAL_NAME ) . '</a>';

		}

		return $links;

	}

	global $wp_version;

	if ( version_compare( $wp_version, '2.8alpha', '>' ) )

		add_filter( 'plugin_row_meta', 'addanalytic_plugin_actions', 10, 2 ); // only 2.8 and higher

	else add_filter( 'plugin_action_links', 'addanalytic_plugin_actions', 10, 2 );



}





?>