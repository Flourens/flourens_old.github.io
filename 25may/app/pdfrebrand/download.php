<?php
if(!isset($_GET["file"]) || !isset($_GET["code"])) exit();
include "config.php";
include "functions.php";
$admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".pdf.defaults"));
include "header.php";
$downloadurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/pdf/".$_GET["file"]."_".$_GET["code"].".pdf";
$updateurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/update.php?file=".$_GET["file"]."&code=".$_GET["code"]."&access=".$_GET["access"];
if(strpos($admindefaults[4][5],"#DLBCODE#") === false)
  $dlburl = $admindefaults[4][5]."?file=".$_GET["file"]."&s=".$admindefaults[4][1]."&code=".$_GET["code"];
else
  $dlburl = str_replace("#DLBCODE#", $_GET["code"], $admindefaults[4][5]);
$socialmedia = '
      <script type="text/javascript">
      <!--
      var addthis_share =
      {
         templates: {
               twitter: "'.$admindefaults[6][6].'",
         }
      }
      //-->
      </script>
      <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
      <div class="addthis_toolbox addthis_32x32_style addthis_default_style" addthis:url="'.$downloadurl.'" addthis:title="'.$admindefaults[6][7].'" addthis:description="'.$admindefaults[6][8].'">
          <a class="addthis_button_facebook"></a>
          <a class="addthis_button_twitter"></a>
          <a class="addthis_button_email"></a>
          <a class="addthis_button_compact"></a>
      </div>';
$page = str_replace(array("#SOCIALMEDIA#", "#DOWNLOAD#", "#UPDATE#", "#DOWNLINEBUILDER#"), array($socialmedia, $downloadurl, $updateurl, $dlburl), $admindefaults[6][5]);
echo $page;
include "footer.php";
?>