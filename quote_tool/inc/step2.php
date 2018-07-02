<?php

error_reporting(0); // Please comment out this line for debugging/troubleshooting purposes

session_start();

$wholeLink = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

global $wpdb;

$pid = $wpdb->get_var("SELECT portalid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");
$guid = $wpdb->get_var("SELECT formguid FROM {$wpdb->prefix}quotetool_hubspot WHERE id = 1");

if (isset($_POST['citizen']))
{
	//include_Once('mysqlconnection.php');

	$_SESSION['citizen'] = $_POST['citizen'];
	$_SESSION['dateOfBirth'] = $_POST['dateOfBirth'];
	$_SESSION['option'] = $_POST['option'];
	$_SESSION['secondApplicantNumber'] = $_POST['secondApplicantNumber'];
	$_SESSION['citizenSecond'] = $_POST['citizenSecond'];
	$_SESSION['secondApplicantName'] = $_POST['secondApplicantName'];
	$_SESSION['secondApplicantEmail'] = $_POST['secondApplicantEmail'];
	$_SESSION['dob'] = $_POST['dob'];

	//Process a new form submission in HubSpot in order to create a new Contact.

	$hubspotutk      = $_COOKIE['hubspotutk']; //grab the cookie from the visitors browser.
	$ip_addr         = $_SERVER['REMOTE_ADDR']; //IP address too.
	$hs_context      = array(
		'hutk' => $hubspotutk,
		'ipAddress' => $ip_addr,
		'pageUrl' => $wholeLink,
		'pageName' => 'Step 2'
	);
	$hs_context_json = json_encode($hs_context);

	//Need to populate these variable with values from the form.
	$str_post = "&citizen=" . urlencode($_SESSION['citizen'])
		. "&date_of_birth=" . urlencode($_SESSION['dateOfBirth'])
		. "&second_applicant_number=" . urlencode($_SESSION['secondApplicantNumber'])
		. "&citizen_second=" . urlencode($_SESSION['citizenSecond'])
		. "&second_applicant_name=" . urlencode($_SESSION['secondApplicantName'])
		. "&second_applicant_email=" . urlencode($_SESSION['secondApplicantEmail'])
		. "&second_applicant_date_of_birth=" . urlencode($_SESSION['dob'])
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

	echo '<script type="text/javascript">window.location = "'.$_SERVER['SERVER_NAME'].$_SERVER['HTTP_HOST']."/quote-tool/step-3".'"</script>';
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

<script type="text/javascript">
$(document).ready(function() {

		$("#applicant1").hide();
		$("#applicant2").hide();
		$("#applicant3").hide();

  	$("#no").click(function(){
		$("#applicant1").hide();
		$("#applicant2").hide();
		$("#applicant3").hide();
	});

	$("#yes").click(function(){
		$("#applicant1").show();
		$("#applicant2").show();
		$("#applicant3").show();
	});
});
</script>

<style>
	.card-block img{
		padding-top:10px!important;
		width:150px!important;
		height: 150px!important;
	}

	input[type="radio"]:checked ~ label{
		background-color: lightgreen;
	}

	input[type="radio"]{
		display: none;
	}

	label{
		margin-bottom: 0%;
	}

	label:hover{
		background-color: #abc8d9!important;
		transition: .5s ease;
	}

</style>
</head>
<body>

<form action="" method="post">

	<div class="container-fluid">
		<div class="progress">
			<div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
			40%
			</div>
		</div>
		<br>
			<div class="row">

				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-date-input" class="col-4 col-form-label">Citizen</label>
						<div class="col-8">
							<select class="form-control" id="exampleSelect1" name="citizen">
								<option>Select an option</option>
								<option>British</option>
								<option>EU National</option>
								<option>Non-EU National</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label for="example-date-input" class="col-4 col-form-label">Date of birth</label>
						<div class="col-8">
							<input class="form-control" type="date" id="example-date-input" name="dateOfBirth">
						</div>
					</div>
				</div>


				<div class="col-md-4">
					<div class="text-center">
					<div class="card">
						<input type="radio" name="option" id="Buymyfirsthouse" value="Buy my first house">
						<label for="Buymyfirsthouse">
						<div class="overlay">
							<div class="card-block">
								<img class="card-img-top" src="http://mortgages.contractors/wp-content/uploads/2018/06/Home.png">

								<h4 class="card-title">Buy my first house</h4>
							</div>
						</div>
						</label>
					</div>
					<br>
					<div class="card">
						<input type="radio" name="option" id="Buytolet" value="Buy to let">
						<label for="Buytolet">
						<div class="overlay">
							<div class="card-block">
								<img class="card-img-top" src="http://mortgages.contractors/wp-content/uploads/2018/06/Sign.png">

								<h4 class="card-title">Buy to let</h4>
							</div>
						</div>
						</label>
					</div>
					<br>
					</div>
				</div>
				<div class="col-md-4">
					<div class="text-center">
						<div class="card">
							<input type="radio" name="option" id="Movehome" value="Move Home">
							<label for="Movehome">
							<div class="overlay">
								<div class="card-block">
									<img class="card-img-top" src="http://mortgages.contractors/wp-content/uploads/2018/06/Moving.png">

									<h4 class="card-title">Move Home</h4>
								</div>
							</div>
							</label>
						</div>
						<br>
						<div class="card">
							<input type="radio" name="option" id="remortgage" value="Remortgage">
							<label for="remortgage">
							<div class="overlay">
								<div class="card-block">
									<img class="card-img-top" src="http://mortgages.contractors/wp-content/uploads/2018/06/Remortgage.png">

									<h4 class="card-title">Remortgage</h4>
								</div>
							</div>
							</label>
						</div>
						<br>
					</div>
				</div>
				<div class="col-md-4">
					<div class="text-center">
						<div class="card">
							<input type="radio" name="option" id="Releaseequity" value="Release Equity">
							<label for="Releaseequity">
							<div class="overlay">
								<div class="card-block">
									<img class="card-img-top" src="http://mortgages.contractors/wp-content/uploads/2018/06/Equity.png">

									<h4 class="card-title">Release Equity</h4>
								</div>
							</div>
							</label>
						</div>
						<br>
						<div class="card">
							<input type="radio" name="option" id="Consolidatedebts" value="Consolidate Debts">
							<label for="Consolidatedebts">
							<div class="overlay">
								<div class="card-block">
									<img class="card-img-top" src="http://mortgages.contractors/wp-content/uploads/2018/06/Debts.png">

									<h4 class="card-title">Consolidate Debts</h4>
								</div>
							</div>
							</label>
						</div>
						<br>
					</div>
				</div>

				<div class="col-md-12">
					<br>
					<h2>Joint Application?</h2>
					<br>
					<p><strong>Add A Second Applicant?</strong></p>

					<button class="btn btn-info" type="button" id="yes">Yes</button>
					<button class="btn btn-info" type="button" id="no">No</button>
				</div>

				<div class="col-md-6">
				<br>
					<div class="form-group row" id="applicant1">
						<label for="example-text-input" class="col-6 col-form-label">Second applicant number: </label>
						<div class="col-6">
							<input class="form-control" type="number" id="example-text-input" name="secondApplicantNumber">
						</div>
					</div>

					<div class="form-group row" id="applicant2">
						<label for="example-date-input" class="col-3 col-form-label">Citizen</label>
						<div class="col-9">
							<select class="form-control" id="exampleSelect1" name="citizenSecond">
								<option>Select an option</option>
								<option>British</option>
								<option>EU National</option>
								<option>Non-EU National</option>
							</select>
						</div>
					</div>
				</div>

				<div class="col-md-6" id="applicant3">
				<br>
					<div class="form-group row">
						<label for="example-text-input" class="col-6 col-form-label">Second applicant name: </label>
						<div class="col-6">
							<input class="form-control" type="text" id="example-text-input" name="secondApplicantName">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-text-input" class="col-6 col-form-label">Second applicant email: </label>
						<div class="col-6">
							<input class="form-control" type="email" id="example-text-input" name="secondApplicantEmail">
						</div>
					</div>
					<div class="form-group row">
						<label for="example-date-input" class="col-6 col-form-label">Date of birth: </label>
						<div class="col-6">
							<input class="form-control" type="date" id="example-date-input" name="dob">
						</div>
					</div>
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
