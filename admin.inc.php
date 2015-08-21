<?php

/**********************************************************************

*			GreenRope Admin Page					*

*********************************************************************/



function addanalytic_options() {

	

	global $wpdb;

    $poststable = $wpdb->posts;



	$addanalytic_settings = addanalytic_read_options();



	if($_POST['addanalytic_save']){

		$addanalytic_settings[enable_plugin] = (($_POST['enable_plugin']) ? true : false);

		$addanalytic_settings[greenrope_acct] = ($_POST['greenrope_acct']);

	

		update_option('byte_addanalytic_settings', $addanalytic_settings);

		

		$str = '<div id="message" class="updated fade"><p>'. __('Options saved successfully.',ADDANALYTIC_LOCAL_NAME) .'</p></div>';

		echo $str;
?>

<style type="text/css">
<!--
div.updated p, div.error p {
    display: none;
}
//-->
</style>

	<?php }

	

	if ($_POST['addanalytic_default']){

		delete_option('byte_addanalytic_settings');

		$addanalytic_settings = addanalytic_default_options();

		update_option('byte_addanalytic_settings', $addanalytic_settings);

		

		$str = '<div id="message" class="updated fade"><p>'. __('Options set to Default.',ADDANALYTIC_LOCAL_NAME) .'</p></div>';

		echo $str;

	}

?>



<div class="wrap">

 	<div id="page-wrap">

	<div id="inside">

	  <div id="side">

		<div class="side-widget helpful">

		<span class="title"><?php _e('Helpful Links',ADDANALYTIC_LOCAL_NAME) ?></span>				

		<?php require_once(ABSPATH . WPINC . '/rss.php'); wp_widget_rss_output('https://feeds.feedburner.com/itegritygroup/greenrope?format=xml', array('items' => 5, 'show_author' => 0, 'show_date' => 1));

		?>

		</div>

		<div class="side-widget news">

		<span class="title"><?php _e('GreenRope News',ADDANALYTIC_LOCAL_NAME) ?></span>				

		<?php require_once(ABSPATH . WPINC . '/rss.php'); wp_widget_rss_output('https://app.greenrope.com/rss.pl?4_116', array('items' => 3, 'show_author' => 0, 'show_date' => 1));

		?>

		</div>

		<div class="side-widget">

			<span class="title"><?php _e('Powered by:',ADDANALYTIC_LOCAL_NAME) ?></span>

			<div id="donate-form">

				<img alt="" id="imgITGByteslogo" border="0" src="http://bytesinc.com/wp-content/uploads/2015/01/Bytes_logo1.png" >

			</div>

		</div>

	

	  </div>



	  <div id="options-div">

	  <div id="headerimage">

		<?php global $addanalytic_url ?>

	  <img src="<?php echo $addanalytic_url ?>/GreenRopeHeader.png" alt="GreenRope logo"/>


	</div>

	 <form method="post" id="addanalytic_options" name="addanalytic_options" style="border: #ccc 1px solid; padding: 10px" onsubmit="return checkForm()">

		<fieldset class="options">

		<table class="form-table">

			<tr style="vertical-align: top; font-size:15px;"><th scope="row" style="background:#<?php if ($addanalytic_settings[enable_plugin]) echo 'cfc'; else echo 'fcc'; ?>"><b><label for="enable_plugin" id="enable"><?php _e('Enable Tracking: ',ADDANALYTIC_LOCAL_NAME); ?></label></b></th>

			<td style="background:#<?php if ($addanalytic_settings[enable_plugin]) echo 'cfc'; else echo 'fcc'; ?>"><input type="checkbox" name="enable_plugin" id="enable_plugin" <?php if ($addanalytic_settings[enable_plugin]) echo 'checked="checked"' ?> /></td>

			</tr>

		</table>

		<br />

		<table class="form-table">

			<tr style="vertical-align: top; font-size:13.2px;"><th scope="row"><b><label for="greenrope_acct"><?php _e('GreenRope Account Number: ',ATA_LOCAL_NAME); ?></label></b></th>

			<td><input type="textbox" name="greenrope_acct" id="greenrope_acct" value="<?php echo attribute_escape(stripslashes($addanalytic_settings[greenrope_acct])); ?>" style="width:167px" /></td>

			</tr>

			<tr style="vertical-align: top; "><td scope="row" colspan="2"><textarea name="addanalytic_other" id="addanalytic_other" rows="8" cols="80" readonly><script language="JavaScript" type="text/javascript"> 
<!--
document.write('<img src="https://app.greenrope.com/wt.pl?a=<?php echo attribute_escape(stripslashes($addanalytic_settings[greenrope_acct])); ?>&r=' + window.document.referrer + '" height="1" width="1">')
//-->
</script></textarea></td>

			</tr>

		</table>

		</fieldset>

		<p>

		  <input type="submit" name="addanalytic_save" id="addanalytic_save" value="Save" style="border:#00CC00 1px solid" />

		 <!-- <input name="addanalytic_default" type="submit" id="addanalytic_default" value="Default Options" style="border:#FF0000 1px solid" onclick="if (!confirm('<?php _e('Do you want to set options to Default?',ADDANALYTIC_LOCAL_NAME); ?>')) return false;" />-->

		</p>

	  </form>

	</div>



	  </div>

	  <div style="clear: both;"></div>

	</div>

</div>

<?php



}



function addanalytic_admin_notice() {

	$plugin_settings_page = '<a href="' . admin_url( 'admin.php?page=options-writing.php?page=addanalytic_options' ) . '">' . __('plugin settings page', ADDANALYTIC_LOCAL_NAME ) . '</a>';




	$addanalytic_settings = addanalytic_read_options();

	if ($addanalytic_settings[enable_plugin]) return;

	if ( !current_user_can( 'manage_options' ) ) return;



    echo '<div class="error">

       <p>'.__('The GreenRope Analytics plugin is disabled. Please visit the ', ADDANALYTIC_LOCAL_NAME ).$plugin_settings_page.__(' to enable.', ADDANALYTIC_LOCAL_NAME ).'</p>

    </div>';

}

add_action('admin_notices', 'addanalytic_admin_notice');



function addanalytic_adminmenu() {

	if (function_exists('current_user_can')) {

		// In WordPress 2.x

		if (current_user_can('manage_options')) {

			$addanalytic_is_admin = true;
   

		}

	} else {

		// In WordPress 1.x

		global $user_ID;

		if (user_can_edit_user($user_ID, 0)) {

			$addanalytic_is_admin = true;

		}

	}



	if ((function_exists('add_options_page'))&&($addanalytic_is_admin)) {

		


$plugin_page = add_menu_page('GreenRope Analytics Admin Page', 'GreenRope', 'add_users', '/options-writing.php?page=addanalytic_options', 'addanalytic_options',   plugins_url('GreenRope/greenrope.com_favicon.jpg'), 76);

		add_action( 'admin_head-'. $plugin_page, 'addanalytic_adminhead' );

	}

}

add_action('admin_menu', 'addanalytic_adminmenu');



function addanalytic_adminhead() {

	global $addanalytic_url;

?>

<link rel="stylesheet" type="text/css" href="<?php echo $addanalytic_url ?>/admin-styles.css" />

<script type="text/javascript" language="JavaScript">

function checkForm() {

answer = true;

if (siw && siw.selectingSomething)

	answer = false;

return answer;

}//

</script>

<script type="text/javascript" language="JavaScript">

	var taddfootertimer = setTimeout("changelogo()",5000);

	

function changelogo()

	{

		if (document.getElementById("imgITGByteslogo").src.indexOf("www.bytesinc.com")>=0)

		{

			document.getElementById("imgITGByteslogo").src = "<?php echo $addanalytic_url ?>/ITegrityLogo.png";

		}

		else

		{

			document.getElementById("imgITGByteslogo").src = "http://bytesinc.com/wp-content/uploads/2015/01/Bytes_logo1.png";

		}

		taddfootertimer = setTimeout("changelogo()",5000);

	}

</script>

<?php }



?>
