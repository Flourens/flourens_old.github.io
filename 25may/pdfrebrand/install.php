<?php
if(isset($_POST["setpwfirst"])){
  if($_POST["setpwfirst"] == "changethis")
    $instmsg = "Your password cannot be \"changethis\"";
  if(ctype_alnum(str_replace("_", "", $_POST["setpwfirst"])) && ctype_alnum(str_replace("_", "", $_POST["folder"]))){
    $fh = fopen("config.php", 'w');
    $write = fwrite($fh, "<?php\ndefine(FOLDER, \"".$_POST["folder"]."\");\ndefine(PASSWORD,\"".$_POST["setpwfirst"]."\");\ndefine(CLICKBANK,\"".$_POST["cb"]."\");\n?>");
    if($write === false)
      die("Cannot Create Admin Settings File. Be sure to chmod config.php to 777");
    else{
      fclose($fh);
      if(!file_exists($_POST["folder"]))
        mkdir($_POST["folder"], 0777);
      $_SESSION["auth"] = "yes";
      $updatemsg = "Settings Updated";
    }
  }
  else{
    $instmsg = "Password and Folder must contain letters, numbers, and underscores only.";
  }
}
?>