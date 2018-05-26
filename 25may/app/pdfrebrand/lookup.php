<?php
include "functions.php";
include "pdf_brander.php";
include "config.php";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Easy Viral PDF Brander | Rebrand PDFs On The Fly</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="jquery/ui.css" rel="stylesheet" />
</head>
<body>
<div id="wrap">      
      <div id="header">      
      </div>          
      <div id="main">

<?


    if(!empty($_POST["filename"]) && file_exists("refids/".str_replace(".pdf", "", $_POST["filename"]).".rebranderids")){
      $file = str_replace(".pdf", "", $_POST["filename"]).".rebranderids";
      $reportdetails = unserialize(file_get_contents("refids/$file"));
      //get break between reportname_code.rebranderids
      $undpos = strrpos($file,"_");
      //get reportname
      $reportname = substr($file, 0, $undpos);
      //get code, stripping off .rebranderids
      $code = substr($file, $undpos+1,-13);       
      $admindefaults = unserialize(file_get_contents(FOLDER."/".$reportname.".pdf.defaults"));
      if(isset($reportdetails["user_email"])){
        $downloadurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/pdf/".$reportname."_".$code.".pdf";
        $updateurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/update.php?file=$reportname&code=$code&access=".$reportdetails["access_code"];
        $dlburl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php?file=$reportname&s=".$admindefaults[4][1]."&code=$code";
        $email_body = str_replace(array("#DOWNLOAD#", "#UPDATE#", "#DOWNLINEBUILDER#"), array($downloadurl, $updateurl, $dlburl), $admindefaults[6][12]);
        $email_headers = "MIME-Version:1.0\r\n"
                         ."Content-Type: text/plain; charset=iso-8859-1\r\n"
                         ."Content-Transfer-Encoding: base64\r\n"
                         ."From: ".$admindefaults[6][13]." <".$admindefaults[6][14].">"."\r\n";
        mail(trim($reportdetails["user_email"]), $admindefaults[6][11], base64_encode($email_body), $email_headers);
        echo print_success("A confirmation email has been sent to your email address on file");
      }
      else
        echo print_error("Your report was found, but no email is on file. Contact the admin to get your details."); 
    }
    else if(!empty($_POST["filename"]))
      echo print_error("No such report could be found. Contact the admin for help retrieving your details.")


?>
  <p>Forgotten your report update url, downline-building rebrander link, or hosted download url?</p>
  <p>If you still have the filename of your rebranded pdf, enter it below. If you entered your email
  when rebranding the form, we will resend you the download instructions and links.</p>
  <form method="post">
  <p style="text-align: center"><span title="cssheader=[tth] cssbody=[tt] header=[Your Filename] body=[An example filename would be reportname_a7jk329aj.pdf]">Your Filename:</span><br><input type="text" name="filename" size="50" value="<?=$_POST["filename"]?>"><br><input type="submit" value="Send Me My Details" /></p>
  </form>
  <p>Don't know the filename? Contact the site owner and they should be able to help you out.</p>
<?php
echo "<script src=\"boxover.js\"></script>";
include "footer.php";
?>