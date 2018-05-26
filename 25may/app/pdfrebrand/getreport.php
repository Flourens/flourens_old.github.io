<?php
  include "functions.php";
  include "pdf_brander.php";
  include "config.php";
    
  if(isset($_REQUEST["file"]) && file_exists(FOLDER."/".$_REQUEST["file"].".pdf")){
    //Grab admin defaults/settings
    $admindefaults = unserialize(file_get_contents(FOLDER."/".$_REQUEST["file"].".pdf.defaults"));
    $statsfile = fopen(FOLDER."/".$_REQUEST["file"].".pdf.stats", 'a') or 
      die("Can't update stats file. Ensure templates folder has the correct permissions");
    fwrite($statsfile, "d,".gmmktime(0,0,0)."\n");
    fclose($statsfile);    
    
    if(($admindefaults[4][0] == "pseudo-save" || $admindefaults[4][0] == "stream") && file_exists("refids/".$_GET["file"]."_".$_GET["id"].".rebranderids")){
      //Generate and stream the report
      $rblinks = unserialize(file_get_contents("refids/".$_GET["file"]."_".$_GET["id"].".rebranderids"));
      foreach($admindefaults[0] AS $key => $value)
        if(!isset($rblinks[$key]))
          $rblinks[$key] = "";
      //Alter admin defaults where necessary
      if(!empty($rblinks["uplinerefids"]) && $admindefaults[4][3] == "yes" && file_exists("refids/".$_GET["file"]."_".$rblinks["uplinerefids"].".rebranderids")){
        //Grab upline ids
        $deflinks = unserialize(file_get_contents("refids/".$_REQUEST["file"]."_".$rblinks["uplinerefids"].".rebranderids"));
        foreach($deflinks AS $key => $value)
          if(isset($rblinks[$key]) && $rblinks[$key] == "" && $value != "")
            $admindefaults[0][$key] = $value;     
      }     
      $rebrander = new pdf_brander($_GET["file"]."_".$_GET["id"].".pdf", $rblinks, $admindefaults[0], FOLDER."/".$_GET["file"].".pdf");
      $rebrander->rebrand_and_stream();               
    }
    else if($admindefaults[4][0] == "save" && file_exists("pdf/".$_GET["file"]."_".$_GET["id"].".pdf")){
      //Send the saved file
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: public");
      header("Content-Description: File Transfer");
      header("Content-Type: application/pdf");
      header("Content-Disposition: attachment; filename=".$_GET["file"]."_".$_GET["id"].".pdf");
      header("Content-Transfer-Encoding: binary");
      $fp = fopen("pdf/".$_GET["file"]."_".$_GET["id"].".pdf","r");
      while (!feof($fp)) {
      	$line = fgets($fp);
      	echo $line;
      }         
    }             
  }
?>