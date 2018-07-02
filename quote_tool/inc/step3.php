<?php

error_reporting(0); // Please comment out this line for debugging/troubleshooting purposes

session_start();

$wholeLink = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

global $wpdb;

$pid = $wpdb->get_var("SELECT portalid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");
$guid = $wpdb->get_var("SELECT formguid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");

if (isset($_POST['addressLine1']))
{
	//include_Once('mysqlconnection.php');

	$_SESSION['addressLine1'] = $_POST['addressLine1'];
	$_SESSION['addressLine2'] = $_POST['addressLine2'];
	$_SESSION['addressLine3'] = $_POST['addressLine3'];
	$_SESSION['postcode'] = $_POST['postcode'];
	$_SESSION['employment'] = $_POST['employment'];
	$_SESSION['annualSalary'] = $_POST['annualSalary'];
	$_SESSION['years'] = $_POST['years'];
	$_SESSION['employerName'] = $_POST['employerName'];
	$_SESSION['jobTitle'] = $_POST['jobTitle'];

	//Process a new form submission in HubSpot in order to create a new Contact.

	$hubspotutk      = $_COOKIE['hubspotutk']; //grab the cookie from the visitors browser.
	$ip_addr         = $_SERVER['REMOTE_ADDR']; //IP address too.
	$hs_context      = array(
		'hutk' => $hubspotutk,
		'ipAddress' => $ip_addr,
		'pageUrl' => $wholeLink,
		'pageName' => 'Step 3'
	);
	$hs_context_json = json_encode($hs_context);

	//Need to populate these variable with values from the form.
	$str_post = "&address_line_1=" . urlencode($_SESSION['addressLine1'])
		. "&address_line_2=" . urlencode($_SESSION['addressLine2'])
		. "&address_line_3=" . urlencode($_SESSION['addressLine3'])
		. "&postcode=" . urlencode($_SESSION['postcode'])
		. "&employment=" . urlencode($_SESSION['employment'])
		. "&annual_salary=" . urlencode($_SESSION['annualSalary'])
		. "&years=" . urlencode($_SESSION['years'])
		. "&employer_name=" . urlencode($_SESSION['employerName'])
		. "&job_title=" . urlencode($_SESSION['jobTitle'])
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

	echo '<script type="text/javascript">window.location = "'.$_SERVER['HTTP_HOST']."/quote-tool/step-4".'"</script>';
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
			<div class="progress-bar bg-info" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
			70%
			</div>
		</div>
		<br>
			<div class="row">
				<div class="col-md-12">
					<h2>Current Address</h2>
					<br>
				</div>

				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Address line 1: </label>
						<div class="col-8">
							<input class="form-control" type="text" id="example-text-input" name="addressLine1">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Address line 2: </label>
						<div class="col-8">
							<input class="form-control" type="text" id="example-text-input" name="addressLine2">
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Address line 3: </label>
						<div class="col-8">
							<input class="form-control" type="text" id="example-text-input" name="addressLine3">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-4 col-form-label">Postcode: </label>
						<div class="col-8">
							<input class="form-control" type="text" id="example-text-input" name="postcode">
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<br>
					<h2>Employment & affordability</h2>
					<br>
				</div>

				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-date-input" class="col-3 col-form-label">Employment</label>
						<div class="col-9">
							<select class="form-control" id="exampleSelect1" name="employment">
								<option>Select an option</option>
								<option>Employed</option>
								<option>Self-employed</option>
								<option>Unemployed</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Annual Salary: </label>
						<div class="col-9">
							<input class="form-control" type="number" id="example-text-input" name="annualSalary">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Years: </label>
						<div class="col-9">
							<input class="form-control" type="number" id="example-text-input" name="years">
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Employer: </label>
						<div class="col-9">
							<input class="form-control" type="text" id="example-text-input" name="employerName">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-3 col-form-label">Job title: </label>
						<div class="col-9">
							<input class="form-control" type="text" id="example-text-input" name="jobTitle">
						</div>
					</div>
				</div>

				<div class="col-md-12">

					<p><strong>Do you have adverse credit?</strong></p>

				</div>

				<div class="col-md-6">
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="CCJs"> CCJs
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="Bankruptcy"> Bankruptcy
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="IVA"> IVA
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="Secured loan arrears"> Secured loan arrears
						</label>
					</div>
				</div>
				<div class="col-md-6">

				</div>

				<div class="col-md-12">
					<div class="text-center">
					<br>
					<button class="btn btn-info">Next</button>
					<br></br>
					<br></br>
					</div>
				</div>
			</div>
</body>
</html>
