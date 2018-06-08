<?php
   /*
   Plugin Name: Lead Engine
   Plugin URI:
   description: Lead Generation Tool for your website. Comes with a settings page to configure the Quote Tool. Just connect it to your HubSpot and Twilio account and wait until someone makes an enquiry. It even sends out an SMS text message and email to notify you of a new lead. Testing new update feature.
   Version: 2.0
   Author: Nicholas Wan
   Author URI:
   */


add_action( 'admin_menu', 'extra_post_info_menu');

function extra_post_info_menu(){

  $page_title = 'Lead Engine';
  $menu_title = 'Lead Engine';
  $capability = 'manage_options';
  $menu_slug  = 'lead-engine';
  $function   = 'extra_post_info_page';
  $icon_url   = 'dashicons-admin-tools';
  $position   = 4;

  add_menu_page( $page_title,
                 $menu_title,
                 $capability,
                 $menu_slug,
                 $function,
                 $icon_url,
                 $position );


}

function extra_post_info_page(){
	?>

	<?php

	/*include_once("inc/mysqlconnection.php");

    $sqllookup = $conn->query("SELECT notify FROM le_emailnotification ORDER BY userID DESC LIMIT 1");

  	if($sqllookup->num_rows>0)
  	{
  		while ($row = $sqllookup->fetch_assoc())
  		{
  				$notificationEmail = $row['notify'];
  		}
  	}

    $sqllookup1 = $conn->query("SELECT sid, authtoken, phonenumber FROM le_twilio ORDER BY userID DESC LIMIT 1");

  	if($sqllookup1->num_rows>0)
  	{
  		while ($row = $sqllookup1->fetch_assoc())
  		{
  				$accountSid = $row['sid'];
  				$authToken = $row['authtoken'];
  				$phoneNumber = $row['phonenumber'];
  		}
  	}*/

	/*Coding the WordPress way*/

	/*Fetch the current email address and assign it to a variable named $notificationEmail*/

	global $wpdb; // Always create this global variable first

	$notificationEmail = $wpdb->get_var("SELECT email FROM wp_quotetool_email_notification WHERE id = 1");
	$sendFrom = $wpdb->get_var("SELECT emailfrom FROM wp_quotetool_email_notification WHERE id = 1");

	$pid = $wpdb->get_var("SELECT portalid FROM wp_quotetool_hubspot WHERE id = 1");
	$guid = $wpdb->get_var("SELECT formguid FROM wp_quotetool_hubspot WHERE id = 1");

	$twilio = $wpdb->get_results("SELECT sidno, tokenno, phoneno FROM wp_quotetool_twilio WHERE id = 1");

		if (isset($_POST['email']))
		{

			$email = $_POST['email'];
      		$twilioSid = $_POST['twiliosid'];
      		$twilioToken = $_POST['twiliotoken'];
      		$twilioNumber = $_POST['twilionumber'];
			$send = $_POST['sendmail'];

			$ID = $_POST['portalid'];
			$GUID = $_POST['formGuid'];

			$wpdb->update('wp_quotetool_email_notification', array('email' => $email), array('id' => '1'));
			$wpdb->update('wp_quotetool_email_notification', array('emailfrom' => $send), array('id' => '1'));

			$wpdb->update('wp_quotetool_twilio', array('sidno' => $twilioSid), array('id' => '1'));
			$wpdb->update('wp_quotetool_twilio', array('tokenno' => $twilioToken), array('id' => '1'));
			$wpdb->update('wp_quotetool_twilio', array('phoneno' => $twilioNumber), array('id' => '1'));

			$wpdb->update('wp_quotetool_hubspot', array('portalid' => $ID), array('id' => '1'));
			$wpdb->update('wp_quotetool_hubspot', array('formguid' => $GUID), array('id' => '1'));

      		/*$sql1 = "INSERT INTO `le_twilio` (`sid`, `authtoken`, `phonenumber`) VALUES (?, ?, ?)";
			$stmt1 = $conn->prepare($sql1);
			$stmt1->bind_param('sss', $twilioSid, $twilioToken, $twilioNumber);
			$stmt1->execute();*/

			echo "Thank you, your settings have been saved!";

			echo "<meta http-equiv='refresh' content='0'>";

		}

	?>
	<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

		<script src="https://use.fontawesome.com/47f3f5e1ce.js"></script>

	</head>

	<form action="" method="post">

	<body>
		<div class="container-fluid">
			<h1>Lead Engine Settings</h1>
			<p style="font-size:18px;">Please use this page to manage the settings for your Quote Tool</p>
			<br>
			<h2>Email Settings:</h2>
			<label>Receive email notifications at:</label>
			<input type="text" class="form-control" id="email" name="email" value="<?php echo $notificationEmail; ?>">
      		<label style="color:#ff0000;">(Please Note: Enter an email address followed by a comma if you have more than one email address.)</label>
			<br>
			<label>Send email notifications from:</label>
			<input type="text" class="form-control" id="sendmail" name="sendmail" value="<?php echo $sendFrom; ?>">
			<br>
			<h2>Twilio Account Settings:</h2>
			<label>Your Twilio SID Number:</label>
			<input type="text" class="form-control" id="twiliosid" name="twiliosid" value="<?php echo $twilio[0]->sidno; ?>">
			<br>
			<label>Your Twilio Token Number:</label>
			<input type="text" class="form-control" id="twiliotoken" name="twiliotoken" value="<?php echo $twilio[0]->tokenno; ?>">
			<br>
      		<label>Your Twilio Phone Number:</label>
			<input type="text" class="form-control" id="twiliotoken" name="twilionumber" value="<?php echo $twilio[0]->phoneno; ?>">
			<br>
			<h2>HubSpot Account Settings:</h2>
			<label style="color:#ff0000;">These can be found on your HubSpot Dashboard / Form Page</label>
			<br>
			<label>Your Portal ID:</label>
			<input type="text" class="form-control" id="portalid" name="portalid" value="<?php echo $pid ?>">
			<br>
			<label>Your Form GUID:</label>
			<input type="text" class="form-control" id="formGuid" name="formGuid" value="<?php echo $guid ?>">
			<br>
			<button type="submit" class="btn btn-success">Save Settings!</button>
		</div>
	</body>
	</html>
	<?php
}

function step_1(){
	require('inc/step1.php');
}

add_shortcode('step1', 'step_1');

function step_2(){
	require('inc/step2.php');
}

add_shortcode('step2', 'step_2');

function step_3(){
	require('inc/step3.php');
}

add_shortcode('step3', 'step_3');

function step_4(){
	require('inc/step4.php');
}

add_shortcode('step4', 'step_4');

function thank_you(){
	require('inc/thank-you.php');
}

add_shortcode('thankyou', 'thank_you');

/*Adding email notification database table during plugin activation*/

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'quotetool_email_notification';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		email tinytext NOT NULL,
		emailfrom tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}

function jal_install_data() {
	global $wpdb;

	$emailto = 'to@example.co.uk';
	$emailfrom = 'from@example.co.uk';

	$table_name = $wpdb->prefix . 'quotetool_email_notification';

	$wpdb->insert(
		$table_name,
		array(
			'email' => $emailto,
			'emailfrom' => $emailfrom,
		)
	);
}

register_activation_hook( __FILE__, 'jal_install' );
register_activation_hook( __FILE__, 'jal_install_data' );

/*Adding Twilio SMS database table during plugin activation*/

global $jal_db_version1;
$jal_db_version1 = '1.0';

function jal_install1() {
	global $wpdb;
	global $jal_db_version1;

	$table_name = $wpdb->prefix . 'quotetool_twilio';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		sidno tinytext NOT NULL,
		tokenno tinytext NOT NULL,
		phoneno tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version1', $jal_db_version1 );
}

function jal_install_data1() {
	global $wpdb;

	$twilioSid = 'ACf4c25105893532a7580393c5b9637e8b';
	$tokenNo = 'ed81000f2462e5dd7d3c1ccebf2532e1';
	$phoneNo = '+441482240481';

	$table_name = $wpdb->prefix . 'quotetool_twilio';

	$wpdb->insert(
		$table_name,
		array(
			'sidno' => $twilioSid,
			'tokenno' => $tokenNo,
			'phoneno' => $phoneNo,
		)
	);
}

register_activation_hook( __FILE__, 'jal_install1' );
register_activation_hook( __FILE__, 'jal_install_data1' );

/*Adding HubSpot database table during plugin activation*/

global $jal_db_version2;
$jal_db_version2 = '1.0';

function jal_install2() {
	global $wpdb;
	global $jal_db_version2;

	$table_name = $wpdb->prefix . 'quotetool_hubspot';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		portalid tinytext NOT NULL,
		formguid tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version2', $jal_db_version2 );
}

function jal_install_data2() {
	global $wpdb;

	$portalId = 'abcd1234';
	$formGuid = 'abcd1234';

	$table_name = $wpdb->prefix . 'quotetool_hubspot';

	$wpdb->insert(
		$table_name,
		array(
			'portalid' => $portalId,
			'formguid' => $formGuid,
		)
	);
}

register_activation_hook( __FILE__, 'jal_install2' );
register_activation_hook( __FILE__, 'jal_install_data2' );
