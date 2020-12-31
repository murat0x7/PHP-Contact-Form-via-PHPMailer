<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Pathfile/PHPMailer/src/Exception.php';
require 'Pathfile/PHPMailer/src/PHPMailer.php';
require 'Pathfile/PHPMailer/src/SMTP.php';


/*Suppose that simple contact form you've created which contains input fields like "Name Surname","E-Mail Adress","Phone Number","Message"  and 
their name values are (NameSurname),(EmailAdress),(PhoneNumber),(Message) ---- 
After that, send this form to this php file (contactresult) just as <form action="contactresult.php" method="post"> . 
Since values sent by contact form should be safe, control all of them by using function. To illustrate, 

SecureFields($Data){
	$FirstCheck			=	trim($Data);
	$SecondCheck		        =	strip_tags($SecondCheck);
	$ThirdCheck			=	htmlspecialchars($SecondCheck, ENT_QUOTES);
	$Result				=	$ThirdCheck;
	return $Result;
}   
*/

if(isset($_POST["NameSurname"])){
	$NameSurnameValue		=	SecureFields($_POST["NameSurname"]);
}else{
	$NameSurnameValue		=	"";
}

if(isset($_POST["EmailAdress"])){
	$EmailAdressValue		=	SecureFields($_POST["EmailAdress"]);
}else{
	$EmailAdressValue		=	"";
}

if(isset($_POST["PhoneNumber"])){
	$PhoneNumberValue	        =	SecureFields($_POST["PhoneNumber"]);
}else{
	$PhoneNumberValue	        =	"";
}

if(isset($_POST["Message"])){
	$MessageValue			=	SecureFields($_POST["Message"]);
}else{
	$MessageValue			=	"";
}

if(($NameSurnameValue!="") and ($EmailAdressValue!="") and ($PhoneNumberValue!="") and ($MessageValue!="")){
	$PrepareMail		        =	"Name Surname : " . $NameSurnameValue . "<br />E-Mail Adress : " . $EmailAdressValue . "<br />Phone Number : " . 
	$PhoneNumberValue . "<br />Message : " . $MessageValue;
	
	$SentMail		        =	new PHPMailer(true);
	
	try{
		$SentMail->SMTPDebug			=	0;
		$SentMail->isSMTP();
		$SentMail->Host				=	'smtp.example.com'; // Set the SMTP server to send through
		$SentMail->SMTPAuth			=	true;
		$SentMail->CharSet			=	"UTF-8";
		$SentMail->Username			=	'user@example.com';                     // SMTP username
		$SentMail->Password			=	'secret';                               // SMTP password
		$SentMail->SMTPSecure			=	'tls';
		$SentMail->Port				=	587;
		$SentMail->SMTPOptions		=	array(
												'ssl' => array(
													'verify_peer' => false,
													'verify_peer_name' => false,
													'allow_self_signed' => true
												)
											);
		$SentMail->setFrom('from@example.com', 'Mailer');
		$SentMail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
		$SentMail->addReplyTo('info@example.com', 'Information');
		$SentMail->isHTML(true);
		$SentMail->Subject = 'Here is the subject';
		$SentMail->MsgHTML($PrepareMail);
		$SentMail->send();
		
		/*Mail has been sent successfully*/
		header("Location:index.php?page=success");
		exit();
	}catch(Exception $e){
		/*Any error happened.*/
		header("Location:index.php?page=error");
		exit();
	}
}else{
	/*There are missing fields in contact form.*/
	header("Location:index.php?page=missing");
	exit();
}
?>
