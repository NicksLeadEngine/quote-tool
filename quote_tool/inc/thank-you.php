<?php

error_reporting(0); // Please comment out this line for debugging/troubleshooting purposes

session_start();

/*Fetch the current email address and assign it to a variable named $notificationEmail*/
	
global $wpdb; // Always create this global variable first
	
$notificationEmail = $wpdb->get_var("SELECT email FROM wp_quotetool_email_notification");

$sendFrom = $wpdb->get_var("SELECT emailfrom FROM wp_quotetool_email_notification WHERE id = 1");

$to = $_SESSION['email'];
$subject = "Quote Tool - Quote Confirmation";
$message = "
<html>
<head>
<h1>Thank you for requesting a Mortgage Quote!</h1>
</head>
<body>
<p>Hi $_SESSION[name]. Thank you for your enquiry, we will be in touch shortly to discuss your requirements.</p>

<p><strong>Just a quick summary from your submission:</strong></p>
<h2>Step 1</h2>
<ul>
	<li>Personal Title: $_SESSION[personalTitle]</li>
	<li>Full Name: $_SESSION[name]</li>
	<li>Telephone: $_SESSION[telephone]</li>
	<li>Email Address: $_SESSION[email]</li>
</ul>
<h2>Step 2</h2>
<ul>
	<li>Citizen: $_SESSION[citizen]</li>
	<li>Date of Birth: $_SESSION[dateOfBirth]</li>
	<li>What Are You Looking For: $_SESSION[option]</li>
	<li>Second Applicant Name: $_SESSION[secondApplicantName]</li>
	<li>Second Applicant Number: $_SESSION[secondApplicantNumber]</li>
	<li>Second Applicant Citizen: $_SESSION[citizenSecond]</li>
	<li>Second Applicant Date of Birth: $_SESSION[dob]</li>
</ul>
<h2>Step 3</h2>
<ul>
	<li>Address Line 1: $_SESSION[addressLine1]</li>
	<li>Address Line 2: $_SESSION[addressLine2]</li>
	<li>Address Line 3: $_SESSION[addressLine3]</li>
	<li>Postcode: $_SESSION[postcode]</li>
	<li>Employment: $_SESSION[employment]</li>
	<li>Annual Salary: $_SESSION[annualSalary]</li>
	<li>Years: $_SESSION[years]</li>
	<li>Employer Name: $_SESSION[employerName]</li>
	<li>Job Title: $_SESSION[jobTitle]</li>
</ul>
<h2>Step 4</h2>
<ul>
	<li>Property Value: $_SESSION[propertyValue]</li>
	<li>Deposit: $_SESSION[deposit]</li>
	<li>Date: $_SESSION[date]</li>
	<li>Time: $_SESSION[time]</li>
</ul>

<p>Thank you for choosing us for your mortgage!</p>

<p>Kind Regards,</p>
<p>The Mortgage Team</p>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From:' . $sendFrom . "\r\n";
$headers .= 'BCc:'. $notificationEmail . "\r\n";

mail($to,$subject,$message,$headers);
?>
<DOCTYPE html>
<html>
<head>


<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>
</body>
</html>
