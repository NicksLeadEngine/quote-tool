<?php

error_reporting(0); // Please comment out this line for debugging/troubleshooting purposes

session_start();

$wholeLink = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

global $wpdb;

$pid = $wpdb->get_var("SELECT portalid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");
$guid = $wpdb->get_var("SELECT formguid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");

if (isset($_POST['propertyValue']))
{
	//include_Once('mysqlconnection.php');

	// Index
	$Personal = $_SESSION['personalTitle'];
	$Name = $_SESSION['name'];
	$Telephone = $_SESSION['telephoneNumber'];
	$Email = $_SESSION['email'];

	// Step 1
	$Citizen = $_SESSION['citizen'];
	$DateOfBirth = $_SESSION['dateOfBirth'];
	$Option = $_SESSION['option'];
	$SecondNumber = $_SESSION['secondApplicantNumber'];
	$CitizenSecond = $_SESSION['citizenSecond'];
	$SecondName = $_SESSION['secondApplicantName'];
	$SecondEmail = $_SESSION['secondApplicantEmail'];
	$DOB = $_SESSION['dob'];

	// Step 2
	$Address1 = $_SESSION['addressLine1'];
	$Address2 = $_SESSION['addressLine2'];
	$Address3 = $_SESSION['addressLine3'];
	$Postcode = $_SESSION['postcode'];
	$Employment = $_SESSION['employment'];
	$AnnualSalary = $_SESSION['annualSalary'];
	$Years = $_SESSION['years'];
	$EmployerName = $_SESSION['employerName'];
	$JobTitle = $_SESSION['jobTitle'];

	// Step 3

	$_SESSION['propertyValue'] = $_POST['propertyValue'];
	$_SESSION['deposit'] = $_POST['deposit'];
	$_SESSION['date'] = $_POST['date'];
	$_SESSION['time'] = $_POST['time'];

	$PropertyValue = $_POST['propertyValue'];
	$Deposit = $_POST['deposit'];
	$Date = $_POST['date'];
	$Time = $_POST['time'];

	//Process a new form submission in HubSpot in order to create a new Contact.

	$hubspotutk      = $_COOKIE['hubspotutk']; //grab the cookie from the visitors browser.
	$ip_addr         = $_SERVER['REMOTE_ADDR']; //IP address too.
	$hs_context      = array(
		'hutk' => $hubspotutk,
		'ipAddress' => $ip_addr,
		'pageUrl' => $wholeLink,
		'pageName' => 'Step 4'
	);
	$hs_context_json = json_encode($hs_context);

	//Need to populate these variable with values from the form.
	$str_post = "&property_value=" . urlencode($_SESSION['propertyValue'])
		. "&deposit=" . urlencode($_SESSION['deposit'])
		. "&date=" . urlencode($_SESSION['date'])
		. "&time=" . urlencode($_SESSION['time'])
		. "&hs_context=" . urlencode($hs_context_json); //Leave this one be

	//replace the values in this URL with your portal ID and your form GUID
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

	//echo '<script type="text/javascript">window.location = "'.$_SERVER['HTTP_HOST']."/quote-tool/thank-you".'"</script>';

	echo '<script type="text/javascript">window.location = "/quote-tool/thank-you"</script>';
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
			<div class="progress-bar bg-info" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%;">
			95%
			</div>
		</div>
		<div class="row">
		<div class="col-md-6">
			<br>
			<h2>Property Details</h2>
			<br>
			<div class="form-group row">
				<label for="example-text-input" class="col-4 col-form-label">Value of property: </label>
				<div class="col-8">
					<input class="form-control" type="text" id="example-text-input" placeholder="Value of property" name="propertyValue">
				</div>
			</div>
			<div class="form-group row">
				<label for="example-text-input" class="col-4 col-form-label">Deposit: </label>
				<div class="col-8">
					<input class="form-control" type="text" id="example-text-input" placeholder="Deposit" name="deposit">
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<br>
			<h2>When Is It Best To Give You A Call?</h2>
			<br>
			<div class="form-group row">
				<label class="col-4 col-form-label">Arrange call date: </label>
				<div class="col-8">
					<input class="form-control" type="date" id="example-date-input" name="date">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-4 col-form-label">Time: </label>
				<div class="col-8">
					<input class="form-control" type="time" id="example-time-input" name="time">
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="text-center">
				<br>
				<button class="btn btn-info">Get A Quote</button>
				<br></br>
				<br></br>
			</div>
		</div>
	</div>
</body>
</html>
