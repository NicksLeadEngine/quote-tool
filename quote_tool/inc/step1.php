<?php

error_reporting(0);

use Twilio\Rest\Client;

session_start();

$wholeLink = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

global $wpdb;

$pid = $wpdb->get_var("SELECT portalid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");
$guid = $wpdb->get_var("SELECT formguid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");

echo $_SERVER['HTTP_HOST']."/quote-tool/step-2";

if (isset($_POST['personalTitle']))
{
	// Setting up the variables

	$PersonalTitle = $_POST['personalTitle'];
	$Name = $_POST['fullname'];
	$Telephone = $_POST['telephoneNumber'];
	$Email = $_POST['email'];

	$_SESSION['personalTitle'] = $PersonalTitle;
	$_SESSION['name'] = $Name;
	$_SESSION['telephone'] = $Telephone;
	$_SESSION['email'] = $Email;

	// Step 1) Do an SQL lookup to fetch the most recent email address stored in database

	$notificationEmail = $wpdb->get_var("SELECT email FROM {$wpdb->prefix}quotetool_email_notification");

	$sendFrom = $wpdb->get_var("SELECT emailfrom FROM {$wpdb->prefix}quotetool_email_notification WHERE id = 1");

	// Step 2) Create and send out the notification email

	$from = $sendFrom; // Needs changing according to user.

	$to = $notificationEmail;
	$subject = "Stage 1 Lead - New Submission on Quote Tool";
	$message = "
	<html>
	<head>
	<h1>New submission on Quote Tool for Stage 1</h1>
	</head>
	<body>
	<p><strong>Personal Title</strong>: $PersonalTitle</p>
	<p><strong>Full Name</strong>:  $Name</strong></p>
	<p><strong>Telephone Number</strong>: $Telephone</strong></p>
	<p><strong>Email Address</strong>: $Email</p>

	<p>Quote Tool</p>
	</body>
	</html>
	";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From:' . $from . "\r\n";

	mail($to,$subject,$message,$headers);

	// Step 3) Fetch the most recent Twilio details from database

	$twilio = $wpdb->get_results("SELECT sidno, tokenno, phoneno, message FROM {$wpdb->prefix}quotetool_twilio WHERE id = 1");

	// Step 4) Setup and send SMS notification using Twilio and fetched details

	$number = $Telephone;
	$modifiedNumber = substr($number, 1);
	$toMobileNumber = "+44" . $modifiedNumber;

	require_once 'twilio-php-master/Twilio/autoload.php';

	$sid = $twilio[0]->sidno; // Twilio SID Number - Get from the Twilio Console
	$token = $twilio[0]->tokenno; // Twilio API Token - Get from the Twilio Console
	$client = new Client($sid, $token);

	$client->messages->create(
		$toMobileNumber,
		array(
			'from' => $twilio[0]->phoneno,//"+447403925212", // From the Twilio mobile number
			'body' => $twilio[0]->message // Body of SMS text message
		)
	);

	//Step 5) Process a new form submission in HubSpot in order to create a new Contact.

	$hubspotutk      = $_COOKIE['hubspotutk']; //grab the cookie from the visitors browser.
	$ip_addr         = $_SERVER['REMOTE_ADDR']; //IP address too.
	$hs_context      = array(
		'hutk' => $hubspotutk,
		'ipAddress' => $ip_addr,
		'pageUrl' => $wholeLink,
		'pageName' => 'Step 1'
	);
	$hs_context_json = json_encode($hs_context);

	//Need to populate these variable with values from the form.
	$str_post = "&personal_title=" . urlencode($PersonalTitle)
		. "&your_name=" . urlencode($Name)
		. "&your_telephone=" . urlencode($Telephone)
		. "&email=" . urlencode($Email)
		. "&hs_context=" . urlencode($hs_context_json); //Leave this one be

	//replace the values in this URL with your portal ID and your form GUID
	//$endpoint = 'https://forms.hubspot.com/uploads/form/v2/4623185/c18d273c-834e-4b4d-9dae-73e503bf2266';

	$endpoint = 'https://forms.hubspot.com/uploads/form/v2/'.$pid.'/'.$guid.'';

	$ch = @curl_init();
	@curl_setopt($ch, CURLOPT_POST, true);
	@curl_setopt($ch, CURLOPT_POSTFIELDS, $str_post);
	@curl_setopt($ch, CURLOPT_URL, $endpoint);
	@curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded'
	));
	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response    = @curl_exec($ch); //Log the response from HubSpot as needed.
	$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); //Log the response status code
	@curl_close($ch);
	echo $status_code . " " . $response;

	echo '<script type="text/javascript">window.location = "http://mortgageresponse.co.uk/quote-tool/step-2/"</script>';
	//echo '<script type="text/javascript">window.location = "'.$_SERVER['HTTP_HOST']."/quote-tool/step-2".'"</script>';
}

?>

<DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>

<form action="" method="post">

	<div class="container-fluid">
		<div class="progress">
			<div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
			20%
			</div>
		</div>
		<br></br>
			<div class="row">

				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Personal Title: </label>
						<div class="col-8">
						<select class="form-control" id="exampleSelect1" name="personalTitle">
							<option>Select an option</option>
							<option>Mr</option>
							<option>Mrs</option>
							<option>Miss</option>
							<option>Ms</option>
							<option>Dr</option>
						</select>
						</div>
					</div>
					<br>
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Your Name: </label>
						<div class="col-8">
							<input class="form-control" type="text" id="example-text-input" name="fullname">
						</div>
					</div>
					<br>
				</div>

				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Your Telephone: </label>
						<div class="col-8">
							<input class="form-control" type="number" id="example-text-input" name="telephoneNumber">
						</div>
					</div>
					<br>
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Your Email: </label>
						<div class="col-8">
							<input class="form-control" type="email" id="example-text-input" name="email">
						</div>
					</div>

				</div>

				<div class="col-md-12">
					<div class="text-center">
						<br>
						<button type="submit" class="btn btn-info">Next</button>
					</div>
				</div>
			</div>
	</div>
</body>
</html>
