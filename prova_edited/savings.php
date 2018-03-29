<?php
	if(isset($_POST['submit'])){
		$name=$_POST['name'];
		$email=$_POST['email'];
		$opt1=$_POST['opt1'];
		$opt2=$_POST['opt2'];
		$opt3=$_POST['opt3'];
		$opt4=$_POST['opt4'];

		$to='8rf3gy2s@robot.zapier.com'; // Receiver Email ID, Replace with your email ID
		$subject='Savings Card';
		$message="Name: ".$name."\n"."Email: ".$email."\n"."1. ".$opt1."\n"."2. ".$opt2."\n"."3. ".$opt3."\n"."4. ".$opt4;
		$headers="From: ".$email;

		if(mail($to, $subject, $message, $headers)){
			header('Location: ./starter-kit-b.html');
		}
		else{
			echo "Something went wrong!";
		}
	}
?>