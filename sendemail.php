<?php

require_once ('../../mysqli_connect.php');

$page_title = 'Verify Email';

include('includes/header.php');

extract($_GET);

echo '
<h1>Thank you!</h1>
<p>Thank you, to complete the process please check your inbox.</p>
<p>An email has been sent to ' . $hyper_email . ', please check your inbox to finish the registration.</p><p><br /></p><hr />';

/* 
The next lines build your email
*/

$sendto= "$hyper_email";
$headers.= "MIME-Version: 1.0\r\n";
$headers.= "Content-type: text/html; ";
$headers.= "charset=iso-8859-1\r\n";
$headers.= "From: Internmap <contact@internmap.com>";
$subject= "Confirm your email - Internmap.com";

// Build the email body text
$emailcontent = "
<html>
<head>
<style type='text/css'>
body {
	background: #000;
	width: 500px;
	height: 800px;
	margin: 10px auto;
	padding: 20px;
	font-family: Georgia, 'Times New Roman', Times, serif;
	font-style: italic;
	font-size: 16px;
	color: #ffffff;
}

a{text-decoration:none; font-style:italic; border:none; color:#fae91a;}
a:visited{}
a:hover{border-bottom: 1px solid #fae91a;}
a:active{}

img#logo{margin: 0 0 -10px 10px;}

#logotext{
	font-family: sans-serif;
	color: #fff; 
	font-size:43px;
	text-decoration: none;
	font-style: normal;
	font-weight: bolder;
}

hr{
	width: 100%;
	height: 1px;
	margin: 18px 0 5px 0;
	background-color: #fae91a;
	border: 0px;
}
</style>
</head>
<body>
<span id='logotext'>INTERNMAP</span><img id='logo' src='http://internmap.alastiarwright.com/images/logo.png' alt='intern map' /><br /><br />
Welcome to InternMap $firstname,<br /><br />
To complete your registration, please click <a href='http://internmap.alastiarwright.com/verify.php?pass=$random&hyper_email=$hyper_email'>here</a>.<br /><br />
Thank you.
<hr />
</body>
</html>";

//  This is the form response page incuding elements of the php code


mail($sendto, $subject, $emailcontent, $headers);


/* *******************************************MAIL STUFF********************************************* */


include('includes/footer.php');
?>

