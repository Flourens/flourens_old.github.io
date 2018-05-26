<?php
if($_GET['hop']){
	$affID = $_GET['hop'];
	$cookieMonths = 12;
	$cookieExpires = $cookieMonths * 30 * 60 * 1000 * 24 * 60 + time();
	setcookie('cbAffiliate', $affID, $cookieExpires);
	//echo $_COOKIE['cbAffiliate'];
	//echo $affID;
} else {
	//echo "no affiliate data set.";
}
?>