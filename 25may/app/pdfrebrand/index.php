<?php

include "functions.php";
include "pdf_brander.php";
include "config.php";

/*
  DOCUMENTATION: What is $admindefaults?
   - admindefaults[0] contains the admin's default ids
   - admindefaults[1] contains the product descriptions
   - admindefaults[2] contains the join urls
   - admindefaults[4] contains the settings for the brander
        * [4][0] is method
        * [4][1] is secret key           
*/

//Getresponse
if(isset($_GET["skey"]) && !isset($_GET["s"]))
  $_GET["s"] = $_REQUEST["s"] = $_GET["skey"];

//Aweber
if(isset($_GET["custom_file"]) && !isset($_GET["file"]))
  $_GET["file"] = $_REQUEST["file"] = $_GET["custom_file"];                  
if(isset($_GET["custom s"]) && !isset($_GET["s"]))
  $_GET["s"] = $_REQUEST["s"] = $_GET["custom_s"];
if(isset($_GET["custom_code"]) && !isset($_GET["code"]))
  $_GET["code"] = $_REQUEST["code"] = $_GET["custom_code"];  


if(isset($_REQUEST["file"]) && file_exists(FOLDER."/".$_REQUEST["file"].".pdf")){
  //Grab admin defaults/settings
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_REQUEST["file"].".pdf.defaults"));
}

$today = gmmktime(0,0,0);

//Track Rebrander Hits
if(file_exists(FOLDER."/".$_REQUEST["file"].".pdf") && $admindefaults[4][1]==$_REQUEST["s"]){
  if(($admindefaults[4][1]==$_REQUEST["s"])){
    $statsfile = fopen(FOLDER."/".$_REQUEST["file"].".pdf.stats", 'a') or 
      die("Can't update stats file. Ensure templates folder has the correct permissions");
    fwrite($statsfile, "h,".$today."\n");
    fclose($statsfile);
  }
}

//Create Rebranding Array
if(isset($_POST["Rebrand"])){
  //Check the secret code
  if($admindefaults[4][1]!=$_POST["s"])
    exit();
    
  //Track times rebranded
  $statsfile = fopen(FOLDER."/".$_REQUEST["file"].".pdf.stats", 'a') or 
    die("Can't update stats file. Ensure templates folder has the correct permissions");
  fwrite($statsfile, "r,".$today."\n");
  
  //Grab Fields to Rebrand, Relabel...
  foreach($_POST as $key => $value){
    if(strpos($key, "rb_") === 0){
      $akey = str_replace("rb_","",$key);
      $rblinks[$akey]=$value;
      if($value == "")
        fwrite($statsfile, "a,".$akey.",".$today."\n");    
    }
  }
  //Close file handle
  fclose($statsfile);
  
  //Alter admin defaults where necessary
  if(!empty($_POST["code"]) && $admindefaults[4][3] == "yes" && file_exists("refids/".$_REQUEST["file"]."_".$_REQUEST["code"].".rebranderids")){
    $rblinks["uplinerefids"] = $_REQUEST["code"];
    //Grab upline ids
    $deflinks = unserialize(file_get_contents("refids/".$_REQUEST["file"]."_".$_POST["code"].".rebranderids"));
    foreach($deflinks AS $key => $value)
      if(isset($rblinks[$key]) && $rblinks[$key] == "" && $value != "")
        $admindefaults[0][$key] = $value;     
  } 
  
  if(empty($_POST["selfcode"])){
    //Get unique code
    do{
      $unique = generateRandom();
    } while(file_exists("pdf/".$_POST["file"]."_".$unique.".pdf") || file_exists("refids/".$_POST["file"]."_".$unique.".rebranderids"));
  }
  else
    $unique = $_POST["selfcode"];  
  
  //Field for "downline builder"
  $rblinks["DLB"] = "?file=".$_REQUEST["file"]."&s=".$_POST["s"]."&code=$unique";
  $rblinks["DLBCODE"] = $unique;
  
  //Get updating access code 
  if(empty($_POST["access"]))
    $access = generateRandom(4);
  else
    $access = $_POST["access"];
  
  //Store updating access code
  $rblinks["access_code"] = $access;
  
  //Store email for later lookup and send notice if enabled
  if(!empty($_POST["user_email"]) && $admindefaults[4][4] == "yes"){
    $rblinks["user_email"] = trim($_POST["user_email"]);
    $downloadurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/pdf/".$_POST["file"]."_".$unique.".pdf";
    $updateurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/update.php?file=".$_POST["file"]."&code=".$unique."&access=".$access;
    if(strpos($admindefaults[4][5],"#DLBCODE#") === false)
      $dlburl = $admindefaults[4][5]."?file=".$_POST["file"]."&s=".$admindefaults[4][1]."&code=".$unique;
    else
      $dlburl = str_replace("#DLBCODE#", $unique, $admindefaults[4][5]);
    $email_body = str_replace(array("#DOWNLOAD#", "#UPDATE#", "#DOWNLINEBUILDER#"), array($downloadurl, $updateurl, $dlburl), $admindefaults[6][12]);
    $email_headers = "MIME-Version:1.0\r\n"
                     ."Content-Type: text/plain; charset=iso-8859-1\r\n"
                     ."Content-Transfer-Encoding: base64\r\n"
                     ."From: ".$admindefaults[6][13]." <".$admindefaults[6][14].">"."\r\n";
    mail(trim($_POST["user_email"]), $admindefaults[6][11], base64_encode($email_body), $email_headers);
  }
  
  //Save ids for later editing
  file_put_contents("refids/".$_POST["file"]."_".$unique.".rebranderids", serialize($rblinks));
  
  //Perform the rebranding
  if($admindefaults[4][0]=="save"){
    //Save the file
    //Perform the rebranding/saving
    $rebrander = new pdf_brander($_POST["file"]."_".$unique.".pdf", $rblinks, $admindefaults[0], FOLDER."/".$_POST["file"].".pdf");
    $rebrander->rebrand_and_save("pdf/");    
    //Redirect the use to the download page.
    header("Location: download.php?file=".$_POST["file"]."&code=$unique&access=$access".($_POST["iframe"] == "yes" ? "&iframe=yes" : "")); exit();
  }
  else if($admindefaults[4][0]=="stream"){
    //Stream the pdf
    $statsfile = fopen(FOLDER."/".$_REQUEST["file"].".pdf.stats", 'a') or 
      die("Can't update stats file. Ensure templates folder has the correct permissions");
    fwrite($statsfile, "d,".$today."\n");
    fclose($statsfile);//Stream the file.
    
    $rebrander = new pdf_brander($_POST["file"].".pdf", $rblinks, $admindefaults[0], FOLDER."/".$_POST["file"].".pdf");
    $rebrander->rebrand_and_stream();
  }
  else{
    //Stream the pdf for later  
    //Redirect the use to the download page.
    header("Location: download.php?file=".$_POST["file"]."&code=$unique&access=$access".($_POST["iframe"] == "yes" ? "&iframe=yes" : "")); exit();       
  }
}

//Display Rebranding Form
if(isset($_GET["file"]) && file_exists(FOLDER."/".$_REQUEST["file"].".pdf")){
  
  if(!isset($_GET["s"])) 
    $_GET["s"] = "";
    
  //Verify secret code
  if($admindefaults[4][1]!=$_GET["s"]){
      //Invalid secret code
      include "header.php";
      echo "<h1>Rebrand PDFs</h1>";
      include "footer.php";
      exit();
  }
  
  if(!empty($_GET["code"]) && $admindefaults[4][3] == "yes" && file_exists("refids/".$_REQUEST["file"]."_".$_GET["code"].".rebranderids")){
    //Grab admin defaults/settings
    $rblinks = unserialize(file_get_contents("refids/".$_REQUEST["file"]."_".$_GET["code"].".rebranderids"));    
  }
  
    
  //Display the page
  include "header.php";
  
  echo '<div id="rebranderdetails">';
  
  //Display header text
  if(!isset($admindefaults[6][4]))
    echo '<h1>Rebranding "'.$_REQUEST["file"].'"</h1>
     <h2>Enter Your IDs For The Following Websites</h2>';
  else
    echo $admindefaults[6][4];
  
  //Display the form
  ?>
  
  <center>
  <form action="index.php" method="POST">
  <input type="hidden" name="iframe" value="<?=(isset($_GET["iframe"]) ? $_GET["iframe"] : "")?>">
  <input type="hidden" name="s" value="<?=$_GET["s"]?>">
  <?php

  if(isset($admindefaults[5]) && is_array($admindefaults[5])){
    foreach($admindefaults[5] AS $value){
      echo  "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"90%\">
            <tr style=\"text-align: left\"><td colspan=\"2\">".$admindefaults[1][$value]."</td></tr>
            <tr><td width=\"55%\">".($admindefaults[2][$value] != "" ? "Click <a target=\"_blank\" href=\"".str_replace("{refid}", (!isset($rblinks[$value]) || $rblinks[$value] == "" ? $admindefaults[0][$value] : $rblinks[$value]), $admindefaults[2][$value])."\">here</a> to join" : "")."</td><td><input name=\"rb_$value\" type=\"text\" value=\"\"></td></tr>
            </table><br>";    
    }
  }
  else{
    //Orderless
    foreach($admindefaults[2] as $key => $value){
      echo  "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"90%\">
            <tr style=\"text-align: left\"><td colspan=\"2\">".$admindefaults[1][$key]."</td></tr>
            <tr><td width=\"55%\">".($value != "" ? "Click <a target=\"_blank\" href=\"".str_replace("{refid}", (!isset($rblinks[$key]) || $rblinks[$key] == "" ? $admindefaults[0][$key] : $rblinks[$key]), $value)."\">here</a> to join" : "")."</td><td>Your Affiliate ID:<br><input name=\"rb_$key\" type=\"text\" value=\"\"></td></tr>
            </table><br>";
    }
  }  
  if($admindefaults[4][4] == "yes")
    echo "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"60%\">
            <tr style=\"text-align: left\"><td>Enter your email address to receive notices regarding your rebranded report. It will not be used for any other purpose.</td></tr>
            <tr><td style=\"text-align: center\"><input name=\"user_email\" type=\"text\" style=\"width: 90%;\"value=\"\"></td></tr>
            </table><br>";      
  ?>
  <input type="hidden" name="file" value="<?=$_REQUEST["file"]?>">
  <input type="hidden" name="code" value="<?=(isset($_REQUEST["code"]) ? $_REQUEST["code"] : "")?>">
  <input type="submit" name="Rebrand" value="<?=$admindefaults[6][9]?>">
  </form>
  </center>
  </div>
  <?php
  //end the page
    if(!(isset($admindefaults[4][2]) && $admindefaults[4][2] == "no"))  	                                                                                               
        echo '<p style="text-align: right; font-size: 8pt; margin-right: 18px"><a style="color: #898989" href="http://www.EasyViralPDFBrander.com/?r='.urlencode(base64_encode(CLICKBANK)).'" target="_blank">Powered By Easy Viral PDF Brander</a></p>';
    echo $admindefaults[6][10];
    if(!isset($_GET["iframe"])){ ?>
    </div>
  <div id="footer">
  </div>
</div>
<?php } ?>
</body>
</html>
<?
}
else{
  //No such file
  include "header.php";
  echo "<h1>Your PDF Rebranding Headquarters</h1>
        <p>Looking for details for a report you rebranded? <a href='lookup.php'>Click here</a>.";
  include "footer.php";
}
?>