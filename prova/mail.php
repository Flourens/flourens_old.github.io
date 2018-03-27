<?php
	if(isset($_POST['submit'])){
		$name=$_POST['name'];
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$msg=$_POST['msg'];

		$to='joe@provarinlife.com'; // Receiver Email ID, Replace with your email ID
		$subject='Form Submission';
		$message="Name: ".$name."\n"."Email: ".$email."\n"."Phone :".$phone."\n"."Message: "."\n\n".$msg;
		$headers="From: ".$email;

		if(mail($to, $subject, $message, $headers)){
			echo
			header('Location: contact-thanks.html');
		}
		else{
			echo "Something went wrong!";
		}
	}
?>