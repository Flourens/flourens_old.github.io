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

if(isset($_REQUEST["file"]) && file_exists(FOLDER."/".$_REQUEST["file"].".pdf")){
  //Grab admin defaults/settings
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_REQUEST["file"].".pdf.defaults"));
}

//Display Rebranding Form
if(isset($_GET["file"]) && file_exists(FOLDER."/".$_REQUEST["file"].".pdf")){
   
  if(!empty($_GET["code"]) && file_exists("refids/".$_REQUEST["file"]."_".$_GET["code"].".rebranderids")){
    //Grab defaults/settings
    $selfids = unserialize(file_get_contents("refids/".$_REQUEST["file"]."_".$_GET["code"].".rebranderids"));
    if($_GET["access"] !=  $selfids["access_code"])
      exit("invalid access code");
    if(isset($selfids["uplinerefids"]) && file_exists("refids/".$_REQUEST["file"]."_".$selfids["uplinerefids"].".rebranderids") && $admindefaults[4][3] == "yes")
      $uplineids = unserialize(file_get_contents("refids/".$_REQUEST["file"]."_".$selfids["uplinerefids"].".rebranderids"));    
  }
  else{
    exit("no such report");
  }

     
  //Display the page
  include "header.php";
  
?><div id="rebranderdetails">
  <p style="text-align: center; font-color: red; font-weight: bold; font-size: 20px">Updating Referral Ids For <?=$_REQUEST["file"]."_".$_GET["code"]?>.pdf</p>
  <p style="text-align: justify; font-weight: bold; font-size: 15px">NOTE: All old copies of your report will still function properly, and your customized urls (download link, update link, etc) from the original download page remain the same</p>
  <center>
  <form action="index.php" method="POST">
  <input type="hidden" name="iframe" value="<?=(isset($_GET["iframe"]) ? $_GET["iframe"] : "")?>">
  <input type="hidden" name="s" value="<?=$admindefaults[4][1]?>">
  <?php
  if(isset($admindefaults[5]) && is_array($admindefaults[5])){
    foreach($admindefaults[5] AS $value){
      echo  "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"90%\">
            <tr style=\"text-align: left\"><td colspan=\"2\">".$admindefaults[1][$value]."</td></tr>
            <tr><td width=\"55%\">".($admindefaults[2][$value] != "" ? "Click <a target=\"_blank\" href=\"".str_replace("{refid}", (!isset($uplineids[$value]) || $uplineids[$value] == "" ? $admindefaults[0][$value] : $uplineids[$value]), $admindefaults[2][$value])."\">here</a> to join" : "")."</td><td><input name=\"rb_$value\" type=\"text\" value=\"".$selfids[$value]."\"></td></tr>
            </table><br>";    
    }
  }
  else{
    //Backwards compatability
    foreach($admindefaults[2] as $key => $value){
      echo  "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"90%\">
            <tr style=\"text-align: left\"><td colspan=\"2\">".$admindefaults[1][$key]."</td></tr>
            <tr><td width=\"55%\">".($value != "" ? "Click <a target=\"_blank\" href=\"".str_replace("{refid}", (!isset($uplineids[$key]) || $uplineids[$key] == "" ? $admindefaults[0][$key] : $uplineids[$key]), $value)."\">here</a> to join" : "")."</td><td>Your Affiliate ID:<br><input name=\"rb_$key\" type=\"text\" value=\"".$selfids[$key]."\"></td></tr>
            </table><br>";
    }
  }
  if($admindefaults[4][4] == "yes")
    echo "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"60%\">
            <tr style=\"text-align: left\"><td>Enter your email below to receive notices regarding your rebranded report. It will not be used for any other purpose.</td></tr>
            <tr><td style=\"text-align: center\"><input name=\"user_email\" type=\"text\" style=\"width: 90%;\"value=\"".$selfids["user_email"]."\"></td></tr>
            </table><br>";      
  ?>
  <input type="hidden" name="file" value="<?=$_REQUEST["file"]?>">
  <input type="hidden" name="code" value="<?=(isset($selfids["uplinerefids"]) ? $selfids["uplinerefids"] : "")?>">
  <input type="hidden" name="access" value="<?=$_GET["access"]?>">
  <input type="hidden" name="selfcode" value="<?=$_GET["code"]?>">
  <input type="submit" name="Rebrand" value="Update Your Report">
  </form>
  </center>
  </div>
  <?php
  //end the page
  include "footer.php";
}
else{
  //No such file
  include "header.php";
  echo "<h1>Rebrand PDFs</h1>";
  include "footer.php";
}
?>