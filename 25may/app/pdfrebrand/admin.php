<?php
/*
  DOCUMENTATION: What is $admindefaults?
   - admindefaults[0] contains the admin's default ids
   - admindefaults[1] contains the product descriptions
   - admindefaults[2] contains the join urls
   - admindefaults[4] contains the settings for the brander
        * [4][0] is method
        * [4][1] is secret key
        * [4][2] is powered by link enabled
        * [4][3] is downline builder enabled
        * [4][4] is emailing enabled
        * [4][5] is downline-building url                              
   - admindefaults[5] contains the field display order
   - admindefaults[6] contains template and theme info
        * [6][0] is font
        * [6][1] is text color
        * [6][2] is background color
        * [6][3] is table border color
        * [6][4] is rebrander form header msg
        * [6][5] is download page template
        * [6][6] is twitter msg
        * [6][7] is social media title
        * [6][8] is social media description
        * [6][9] is rebrand button text 
        * [6][10] is rebrander form footer message 
        * [6][11] is email subject
        * [6][12] is email body 
        * [6][13] is from name    
        * [6][14] is from email                                                                                                                                
*/

//Get necessary files, start sessions...
session_start();

//Empty notices
$updatemsg = "";
$themeupdatemsg = "";
$templateupdatemsg = "";
$socialmediaupdatemsg = "";

include "install.php";
include "config.php";
include "functions.php";
include "pdf_brander.php";

//Update basic settings
if(isset($_POST["update_basic_settings"])){
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
  $admindefaults[4][0] = $_POST["method"];
  $admindefaults[4][1] = $_POST["secretkey"];
  $admindefaults[4][2] = $_POST["showpoweredby"];
  $admindefaults[4][3] = $_POST["allowdlb"];
  $admindefaults[4][4] = $_POST["store_email"];
  if(isset($_POST["dlb_url"]))
    $admindefaults[4][5] = $_POST["dlb_url"];  
  file_put_contents(FOLDER."/".$_GET["file"].".defaults", serialize($admindefaults)) 
    or die("Cannot Create Admin Settings File. Be sure to chmod /".FOLDER." to 777");
  $updatemsg = "Basic Settings Updated";
} 

//Update rebranded field settings
if(isset($_POST["update_rebranded_field_settings"])){
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
  //Set up field defaults
  unset($admindefaults[0], $admindefaults[1], $admindefaults[2]);
  foreach($_POST as $key => $value){
    if(strpos($key, "rb_") === 0)
      $admindefaults[0][str_replace("rb_","",$key)]=$value;
    if(strpos($key, "d_") === 0)
      $admindefaults[1][str_replace("d_","",$key)]=$value;
    if(strpos($key, "u_") === 0)
      $admindefaults[2][str_replace("u_","",$key)]=$value;         
  }
  $admindefaults[5] = explode(",",substr($_POST["order"],0,-1));
  file_put_contents(FOLDER."/".$_GET["file"].".defaults", serialize($admindefaults)) 
    or die("Cannot Create Admin Settings File. Be sure to chmod /".FOLDER." to 777");
  $updatemsg = "Rebranded Field Settings Updated";
}

//Update theme
if(isset($_POST["update_theme"])){
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
  $admindefaults[6][0] = ($_POST["font"] != "" ? $_POST["font"] : $_POST["customfont"]);
  $admindefaults[6][1] = $_POST["textcolor"];
  $admindefaults[6][2] = $_POST["backgroundcolor"];
  $admindefaults[6][3] = $_POST["bordercolor"];
  file_put_contents(FOLDER."/".$_GET["file"].".defaults", serialize($admindefaults)) 
    or die("Cannot Create Admin Settings File. Be sure to chmod /".FOLDER." to 777");
  $themeupdatemsg = "Theme Updated";
}

//Update templates
if(isset($_POST["update_templates"])){
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
  $admindefaults[6][4] = $_POST["formheadtemplate"];
  $admindefaults[6][5] = $_POST["dltemplate"];
  $admindefaults[6][9] = $_POST["submittext"];
  $admindefaults[6][10] = $_POST["formfoottemplate"];
  file_put_contents(FOLDER."/".$_GET["file"].".defaults", serialize($admindefaults)) 
    or die("Cannot Create Admin Settings File. Be sure to chmod /".FOLDER." to 777");
  $templateupdatemsg = "Templates Updated";
}
//Update email templates
if(isset($_POST["update_email_templates"])){
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
  $admindefaults[6][11] = $_POST["emailsubject"];
  $admindefaults[6][12] = $_POST["emailbody"];
  $admindefaults[6][13] = $_POST["fromname"];
  $admindefaults[6][14] = $_POST["fromemail"];
  file_put_contents(FOLDER."/".$_GET["file"].".defaults", serialize($admindefaults)) 
    or die("Cannot Create Admin Settings File. Be sure to chmod /".FOLDER." to 777");
  $emailtemplateupdatemsg = "Email Templates Updated";
}

//Update social media templates
if(isset($_POST["update_social_media_templates"])){
  $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
  $admindefaults[6][6] = $_POST["twittertxt"];
  $admindefaults[6][7] = $_POST["smtitle"];
  $admindefaults[6][8] = $_POST["smdescription"];
  file_put_contents(FOLDER."/".$_GET["file"].".defaults", serialize($admindefaults)) 
    or die("Cannot Create Admin Settings File. Be sure to chmod /".FOLDER." to 777");
  $socialmediaupdatemsg = "Social Media Templates Updated";
}

//Clear stats
if(isset($_POST["clear_stats"])){
  fopen(FOLDER."/".$_GET["file"].".stats", 'w');
  $updatemsg = "Stats/Activity Graph Cleared";  
}
          
//Check login
if(isset($_POST["pw"])){
  if($_POST["pw"] == PASSWORD){
    //authenticated...
    $_SESSION["auth"] = "yes";
  }
}

$templatett = 'cssheader=[tth] cssbody=[tt] header=[PDF Templates Folder] body=[Enter the name of the folder in which you are uploading the PDF\'s to be rebranded. Try making it difficult to guess. Do not include trailing slashes. This folder should be located within the folder you are viewing this page from.<br><br>For instance, if I set this to \'as35\', I would upload my pdfs to rebrand into http://'.$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF']).'/as35/]';

if(isset($_GET["logout"])){
  unset($_SESSION["auth"]);
  session_destroy();
}
//Not authenticated. Display login form
if((!isset($_SESSION["auth"]) || $_SESSION["auth"] != "yes") && PASSWORD != "changethis"){
  include "adminheader.php";
?>
  <div class="ui-widget ui-widget-content ui-corner-all" style="width: 400px; margin: 0px auto; text-align: center"><div class="ui-widget ui-widget-header ui-corner-all">Administrative Login</div>
    <p>Enter Your Password</p>
    <form action="admin.php" method="POST">
      <p><input name="pw" type="password" value="">
      <INPUT TYPE="SUBMIT" NAME="Submit" VALUE="Login"></p>
    </form>
  </div>
<?php
}
else if(PASSWORD == "changethis" || isset($instmsg)){
  //First Time User
  include "adminheader.php";
?>
    <div class="ui-widget ui-widget-content ui-corner-all" style="width: 500px; margin: 0px auto;"><div class="ui-widget ui-widget-header ui-corner-all">Configure Global Settings</div>
     <?=print_error($instmsg)?>
     <h3>Write the password down... you will not be able to recover it easily.</h3>
    <form action="admin.php" method="post">
    <center>
    <table width="400">
    <tr><td colspan="2"><b>Change Global Settings</b></td></tr>
    <tr><td><span title="cssheader=[tth] cssbody=[tt] header=[Admin Password] body=[Set the password you use to enter the rebrander administration area. Must be alphanumeric.]">Admin Password:</span> </td><td><input type="text" name="setpwfirst" value="<?=PASSWORD?>"></td></tr> 
    <tr><td><span title="cssheader=[tth] cssbody=[tt] header=[Admin Clickbank ID] body=[Enter your clickbank ID. Anywhere a link to EasyViralPDFBrander occurs on the download or rebrander page will automatically have your clickbank id.]">Admin Clickbank ID:</span> </td><td><input type="text" name="cb" value="<?=CLICKBANK?>"></td></tr> 
    <tr><td><span title="<?=$templatett?>">PDF Templates Folder:</span> </td><td><input type="text" name="folder" value="<?=FOLDER?>"></td></tr> 
    <tr><td></td><td><input type="submit" name="cpw" value="Save Global Settings"></td></tr></table></center>
    </form>
    </div>
    
<?php
}
else{    //Authenticated. Display administration panel.
  include "adminheader.php";
?>
  <div class="ui-widget ui-widget-content ui-corner-all inner" style="width:500px; margin: 0px auto">
  <form action="admin.php" method="get">
  <p style="text-align: center; margin: 0px"><span style="margin-right:20px; font-size: 13px"><a href="admin.php">Home</a> | <span style="margin-right:20px; font-size: 13px"><a href="admin.php?page=lookup">Lookup</a> | <a target="_blank" href="http://easyviralpdfbrander.com/tutorials.php">Help</a> | <a href="admin.php?logout">Logout</a></span><select name="file">
<?php
  //Find all pdfs available for rebranding
  $str="";
  $dh = opendir(FOLDER);
    while (($file = readdir($dh)) !== false) {
      if(substr_count($file,".pdf")==1 && substr_count($file,".defaults")==0 && substr_count($file,".stats")==0){
        if($file == $_GET["file"])
          $str.="<option value=\"$file\" selected>$file</option>";
        else
          $str.="<option value=\"$file\">$file</option>";
      }
    }
  closedir($dh);
  
  //Display available pdf's of whose settings we can edit
  echo $str;
?>  
  </select>
  
  <input type="submit" name="manage" value="Manage Rebrander">
   </p>
  </form>
 </div>
<?php  
//Edit a specific pdf's settings
if(isset($_GET["file"]) && file_exists(FOLDER."/".$_GET["file"])){
  //Init flat files
  if(!file_exists(FOLDER."/".$_GET["file"].".stats")){
    $fh = fopen(FOLDER."/".$_GET["file"].".stats", 'w') 
      or die("Cannot Create Stats File. Be sure to chmod /".FOLDER." to 777");
  }
  if(!file_exists(FOLDER."/".$_GET["file"].".defaults")){
    $fields = getFields(FOLDER."/".$_REQUEST["file"]);
    foreach($fields as $key => $value){
      $admindefaults[0][$key] = "";
      $admindefaults[1][$key] = "";
      $admindefaults[2][$key] = "";
      $fields[$key] = "";
    }
    if(!isset($admindefaults[0])){
      echo '<div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Untagged PDF:</div>'.print_error("Your Report Has No Rebrandable Tags").'
      <p class="inner">No zzzSITENAMEzzz tags could be found in '.$_GET["file"].'. If you are unsure why you are seeing this error or need help, please <a href="http://easyviralpdfbrander.com/tutorials.php">click here</a> for more information</p>
      </div>';
      include "footer.php";
      exit();
    }   
    $admindefaults[4][0] = "pseudo-save";
    $admindefaults[4][1] = "";
    $admindefaults[4][2] = "yes";
    $admindefaults[4][3] = "no";
    $admindefaults[4][4] = "no";
    $admindefaults[4][5] = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php";
    $admindefaults[6][0] = "Verdana";
    $admindefaults[6][1] = "#000000";
    $admindefaults[6][2] = "#FFFFFF";
    $admindefaults[6][3] = "#b2b2b2";
    $admindefaults[6][4] = '<h1 style="text-align: center; color:#990000">Rebranding "'.$_REQUEST["file"].'"</h1><h2 style="text-align: center">Enter Your IDs For The Following Websites</h2>';
    $admindefaults[6][5] = '<p style="font-size: 16px; font-weight: bold;">The PDF been successfully rebranded. <a href="#DOWNLOAD#">Click here to download</a> your rebranded copy.</p>
                            <p>Also, we are now hosting your report on our servers for you. You can send people directly to the following link to send them <strong>YOUR</strong> rebranded copy of the report filled with <strong>YOUR ids</strong>.</p>
                            <p><input size="80" type="text" value="#DOWNLOAD#" /></p>
                            <p>You may also update your report if any of your referral ids change by <a href="#UPDATE#">clicking here</a> or visiting the url below. Bookmark this page or save it some other way so you can make changes if necessary.</p>
                            <p><input size="80" type="text" value="#UPDATE#" /></p>
                            <p>Finally, share your rebranded PDF with the world by using the social media tools below.</p>
                            <table style="margin-left: auto; margin-right: auto;" border="0">
                            <tbody>
                            <tr>
                            <td style="text-align: center;">#SOCIALMEDIA#</td>
                            </tr>
                            </tbody>
                            </table>';
    $admindefaults[6][6] = "Check out the awesome secrets revealed in ".str_replace(".pdf","",$_REQUEST["file"])."! {{url}}";
    $admindefaults[6][7] = str_replace(".pdf","",$_REQUEST["file"]); 
    $admindefaults[6][8] = "Enter Your Description";
    $admindefaults[6][9] = "Rebrand It!";
    $admindefaults[6][10] = "";
    $admindefaults[6][11] = "Your Rebranded Copy of ".str_replace(".pdf","",$_REQUEST["file"]);
    $admindefaults[6][12] = 'Please save this email or print it out for your records.

Your PDF been successfully rebranded. Click below to download 
your rebranded copy.

=> #DOWNLOAD#

Also, we are now hosting your report on our servers for you. 
You can send people directly to the above link to send them 
YOUR rebranded copy of the report filled with YOUR ids.

You may also update your report if any of your referral ids
change by visiting the url below.

=> #UPDATE# 

Thanks! Get that report out there and get YOUR links clicked on!
-Your Name';
    $admindefaults[6][13] = "";
    $admindefaults[6][14] = "";         
      
    file_put_contents(FOLDER."/".$_GET["file"].".defaults", serialize($admindefaults)) 
      or die("Cannot Create Admin Settings File. Be sure to chmod /".FOLDER." to 777");
  }
  if(!isset($_GET["page"]))
    $_GET["page"] = "";
  $adminlink = "admin.php?file=".$_GET["file"];
  $selflink = $adminlink."&page=".$_GET["page"];
echo '<div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Managing "'.$_GET["file"].'"</div>
  <div class="buttons" style="margin: 10px">
    <a href="'.$adminlink.'&page=basicsettings"><img src="images/icons/wrench.png" /> Basic Settings</a>
    <a href="'.$adminlink.'&page=fields"><img src="images/icons/textfield.png" /> Fields</a>
    <a href="'.$adminlink.'&page=templates"><img src="images/icons/palette.png" /> Templates</a>
    <a href="'.$adminlink.'&page=integration"><img src="images/icons/page_code.png" /> Integration</a>
    <a href="'.$adminlink.'&page=statistics"><img src="images/icons/chart_line.png" /> Stats</a>
  </div>
   <div class="clear" style="height: 10px"></div>
</div>'; 
  //Stats  
  if($_GET["page"] == "statistics"){
    if(($statsfile = fopen(FOLDER."/".$_GET["file"].".stats", 'r')) !== false){
      while (($data = fgetcsv($statsfile, 60, ",")) !== false) {
        if(!isset($mindate))
          $mindate = $data[1];
        switch($data[0]){
          //Hits to rebrander form
          case "h":
            if(isset($hits[$data[1]]))
              $hits[$data[1]]++;
            else
              $hits[$data[1]] = 1;  
            break;
          //Rebrandings
          case "r":
            if(isset($rebrandings[$data[1]]))
              $rebrandings[$data[1]]++;
            else
              $rebrandings[$data[1]] = 1;  
            break;
          //Downloads
          case "d":
            if(isset($downloads[$data[1]]))
              $downloads[$data[1]]++;
            else
              $downloads[$data[1]] = 1;  
            break;
          //Admin default used
          case "a":
            if(isset($defaultsused[$data[1]]))
              $defaultsused[$data[1]]++;
            else
              $defaultsused[$data[1]] = 1;  
            break;
        }  
      }
    }
    $defaultrows = "";
    if(isset($defaultsused) && is_array($defaultsused))
      foreach($defaultsused AS $key => $value){
        $defaultrows .= "<tr><td>$key</td><td>$value</td></tr>\n";
      }
    $totalhits = 0;
    $totalrebrandings = 0;
    $totaldownloads = 0;
    //Fill empties with zeroes
    $daysecs = 24*60*60;
    $today = gmmktime(0,0,0);
    if(isset($mindate)){
      for($i=$mindate; $i <= $today; $i= $i+$daysecs){
        if(!isset($downloads[$i]))
          $downloads[$i] = 0;
        if(!isset($hits[$i]))
          $hits[$i] = 0;
        if(!isset($rebrandings[$i]))
          $rebrandings[$i] = 0;                
      }
      ksort($rebrandings);
      ksort($hits);
      ksort($downloads);    
      //Prepare hit data
      $hitdata = "[";
      $hitdays = sizeOf($hits);
      $i=1;
      $maxhr= 0;
      foreach($hits AS $day => $number){
        $hitdata .= "[1000*$day,$number]";
        $totalhits += $number;
        if($i < $hitdays)
          $hitdata.=", ";
        if($number > $maxhr)
          $maxhr = $number; 
        $i++;      
      }    
      $hitdata .= "]";
      //Preprare rebranding data
      $rebranddata = "[";
      $rebranddays = sizeOf($rebrandings);
      $i=1;
      foreach($rebrandings AS $day => $number){
        $rebranddata .= "[1000*$day,$number]";
        $totalrebrandings += $number;      
        if($i < $rebranddays)
          $rebranddata.=", ";
        if($number > $maxhr)
          $maxhr = $number;         
        $i++;      
      }
      $rebranddata .= "]";
      //Prepare downloads data
      $dldata = "[";
      $dldays = sizeOf($downloads);
      $i=1;
      foreach($downloads AS $day => $number){
        $dldata .= "[1000*$day,$number]";
        $totaldownloads += $number;
        if($i < $dldays)
          $dldata.=", ";
        if($number > $maxhr)
          $maxhr = $number;         
        $i++;      
      }
      $dldata .= "]";
    }else{
      //No data
      $maxhr=0;
      $hitdata = "[[$today*1000,0]]";
      $dldata = "[[$today*1000,0]]";
      $rebranddata = "[[$today*1000,0]]";      
    }
    echo  ' <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Activity Graph</div>
     '.print_success($updatemsg).'
     <div id="placeholder" style="width:600px;height:400px; margin: 10px auto"></div>
     <p style="text-align: center">Highlight below the portion of the graph you wish to view</p>
     <div id="overview" style="margin: 0px auto;width:400px;height:50px"></div>';
    echo <<<EOT
    <script id="source">
    $(function () {
      var hitdata = $hitdata;  
      var rebranddata = $rebranddata;
      var dldata = $dldata;
      var placeholder = $("#placeholder");
      var today = new Date().getTime()+60*60*1000;
      var d =[ {data: hitdata, label: "Hits to Rebrander Form"}, 
                        {data: rebranddata, label: "Times Rebranded" },
                        {data: dldata, label: "Times Downloaded"}];
      var options = {
          series: { lines: { show: true}, points:{show: true}, shadowSize: 0 },
          xaxis: {mode: "time", min: (today-(1000*24*60*60*20)), max: (today)},
          yaxis: {min: (0), max: (1.3*$maxhr)},
          selection: { mode: "x" },
          grid: { hoverable: true}
      };
  
      var plot = $.plot(placeholder, [ {data: hitdata, label: "Hits to Rebrander Form"}, 
                        {data: rebranddata, label: "Times Rebranded" },
                        {data: dldata, label: "Times Downloaded"} ], options);
    
      var overview = $.plot($("#overview"), [hitdata,rebranddata,dldata], {
          series: {
              lines: { show: true, lineWidth: 1 },
              shadowSize: 0
          },
          xaxis: { ticks: [], mode: "time" },
          yaxis: { ticks: [], min: 0, autoscaleMargin: 0.1 },
          selection: { mode: "x" }
      });
  
      // now connect the two
      
      $("#placeholder").bind("plotselected", function (event, ranges) {
          // do the zooming
          plot = $.plot($("#placeholder"), d,
                        $.extend(true, {}, options, {
                            xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to }
                        }));
  
          // don't fire event on the overview to prevent eternal loop
          overview.setSelection(ranges, true);
      });
      
      $("#overview").bind("plotselected", function (event, ranges) {
          plot.setSelection(ranges);
      });
      
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                    var offset = new Date().getTimezoneOffset();    
                    var day = (new Date(parseInt(x)+60*offset*1000)); 
                    showTooltip(item.pageX, item.pageY,
                                item.series.label + " on " + (day.getMonth() + 1) + "/" + day.getDate() + "/" + day.getFullYear() + ": " + parseInt(y));
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
    });


    });
    </script>
    </div>
    <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Stats</div>
      <div class="ui-state-highlight" style="width: 400px; margin: 10px auto">
        <table style="width: 90%; margin: 10px auto;">
          <tr><td colspan="2"><b>Overall Stats</b></td>
          <tr><td>Total Hits to Rebrander Form:</td><td>$totalhits</td></tr>
          <tr><td>Total Times Rebranded:</td><td>$totalrebrandings</td></tr>
          <tr><td>Total Times Downloaded:</td><td>$totaldownloads</td></tr>
        </table>
      </div>
      <div class="ui-state-highlight" style="width: 400px; margin: 10px auto">
        <table style="width: 90%; margin: 10px auto;">
          <tr><td colspan="2"><b>Defaults Used</b></td>
          <tr><td><i>Field</i></td><td><i>Times</i></td>
          $defaultrows
        </table>      
      </div>
      <form action="$selflink" method="post" onsubmit="return confirm('Are you certain you want to reset the stats for this report?')">
      <div class="buttons" style="width: 50%; margin: 0px auto">
        <button style="width: 100%" type="submit" name="clear_stats" class="negative">
            <img src="images/icons/cog_go.png" alt=""/> 
            Clear Stats & Activity Graph
        </button>
      </div>
      <div class="clear"><br /></div>
      </form>
    </div>
EOT;
  }
  //Edit Settings
  else if($_GET["page"] == "integration"){
    $fields = getFields(FOLDER."/".$_REQUEST["file"]);
    $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
    foreach($admindefaults[0] as $key => $value){
      if(isset($fields[$key]))
        $fields[$key] = $value;
    }    
    $secretkey = $admindefaults[4][1];
    $url = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php?file=".str_replace(".pdf","",$_REQUEST["file"])."&s=".$secretkey;
  ?>
    <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Rebrander Integration</div>
    <div class="ui-widget ui-widget-content ui-corner-all inner">
    <div class="ui-widget ui-widget-header ui-corner-all"><span title="cssbody=[tt] cssheader=[tth] body=[This is the direct link to the rebrander. This is the easiest method to use to send folks to the rebander, but it does not allow you smoothly integrate the form into a separate website like the other two methods.] header=[Link to Rebrander]">Direct Link Integration</span></div>
    <p style="text-align: center; font-size: 16px"><a target="_blank" href="<?=$url?>"><?=$url?></a></p>
    <p style="text-align: center;"><input type="text" style="width:70%" value="<?=$url?>"></p>
    </div>
    <div class="ui-widget ui-widget-content ui-corner-all inner">
    <div class="ui-widget ui-widget-header ui-corner-all"><span title="cssbody=[tt] cssheader=[tth] body=[This is the easiest way to put a rebrander form directly into any website. Just paste this code into your page where you want the rebrander to appear. It will also automatically integrate the download page if you elect to use the pseudo-stream or save delivery methods.<br><br>If when using this method the form doesn't totally appear in your site and requires scrolling, simply increase the height and/or width by changing where it says height=400 to a larger number and/or width=80% to a larger number] header=[Iframe integration]">Iframe Integration</span></div>
    <p style="text-align:center">
      <textarea rows="5" cols="50">
<iframe src="<?=$url?>&iframe=yes" width="80%" height="400" frameborder="0">
  <p>Your browser does not support iframes. <a target="_blank" href="<?=$url?>">Click here</a> to rebrand.</p>
</iframe>
      </textarea>
    </div>
    <div class="ui-widget ui-widget-content ui-corner-all inner">
    <div class="ui-widget ui-widget-header ui-corner-all"><span title="cssbody=[tt] cssheader=[tth] header=[Raw Form Code] body=[If you want to customize the rebranding page so that it, say, integrates with the membership area of your website, or it uses a different template on the rebranding page, this is the code you will want to use. Simply copy and paste it into your site, and you've got a custom installation of the rebrander. Easy, huh?]">Raw Form Code</span></div>  
  <p style="text-align:center"><textarea rows="5" cols="50">
   <form class="rebrand_form" action="<?="http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php"?>" method="POST"<?=($admindefaults[4][0] != "stream" ? " target=\"_blank\"" : "")?>>
<? echo '<style type="text/css">
        .rebrand_form{
          color: '.$admindefaults[6][1].';
          font-family: '.$admindefaults[6][0].', Verdana, Arial;
        }
        .field_table, .field_table tr, .field_table td{
          border: 1px solid '.$admindefaults[6][3].';  
        }
        </style>';
  echo $admindefaults[6][4];
 ?> 
    <center>
    <input type="hidden" name="s" value="<?php echo $secretkey; ?>">
    <?php
    if(isset($admindefaults[5]) && is_array($admindefaults[5])){
      foreach($admindefaults[5] AS $value){
        echo  "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"90%\">
              <tr style=\"text-align: left\"><td colspan=\"2\">".$admindefaults[1][$value]."</td></tr>
              <tr><td width=\"55%\">".($admindefaults[2][$value] != "" ? "Click <a target=\"_blank\" href=\"".str_replace("{refid}", $admindefaults[0][$value], $admindefaults[2][$value])."\">here</a> to join" : "")."</td><td><input name=\"rb_$value\" type=\"text\" value=\"\"></td></tr>
              </table><br>";    
      }
    }
    else{
      //Orderless
      foreach($fields as $key => $value){
        echo  "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"90%\">
              <tr style=\"text-align: left\"><td colspan=\"2\">".$admindefaults[1][$key]."</td></tr>
              <tr><td width=\"55%\">".($admindefaults[2][$key] != "" ? "Click <a target=\"_blank\" href=\"".str_replace("{refid}", $admindefaults[0][$key], $admindefaults[2][$key])."\">here</a> to join" : "")."</td><td><input name=\"rb_$key\" type=\"text\" value=\"\"></td></tr>
              </table><br>";
      }
    }
    if($admindefaults[4][4] == "yes")
      echo "<table class=\"field_table\" align=\"center\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" width=\"60%\">
              <tr style=\"text-align: left\"><td>Enter your email address to receive notices regarding your rebranded report. It will not be used for any other purpose.</td></tr>
              <tr><td style=\"text-align: center\"><input name=\"user_email\" type=\"text\" style=\"width: 90%;\"value=\"\"></td></tr>
              </table><br>";     
    ?>
    <input type="hidden" name="file" value="<?php echo str_replace(".pdf","", $_REQUEST["file"]); ?>">
    <input type="submit" name="Rebrand" value="<?=$admindefaults[6][9]?>">
    </form>
    <?=($admindefaults[4][2] != "no" ? '<p style="text-align: right; font-size: 8pt; margin-right: 18px"><a style="color: #898989" href="http://www.EasyViralPDFBrander.com/?r='.urlencode(base64_encode(CLICKBANK)).'" target="_blank">Powered By Easy Viral PDF Brander</a></p>' : "")?>
    <?=$admindefaults[6][10]?>
    <!-- Tracking rebrander form views -->
    <img src="<?php echo $url; ?>" height="1" width="1" border="0" />
    <!-- End view tracking -->
  </center>   
  </textarea></p>
  </div>
  </div>
<?php 
  }else if($_GET["page"] == "basicsettings" || $_GET["page"] == ""){
    $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
?>       
     <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Basic Settings</div>
     <?=print_success($updatemsg)?>  
     <form action="<?=$selflink?>" method="post">
    <table width="500" style="margin: 20px auto">
    <tr style="text-align: left">
      <td><span title="cssbody=[tt] cssheader=[tth] header=[Delivery Method] body=[Choose how you want the newly rebranded report to be delivered.]">PDF Delivery Method</span></td>
      <td>
        <input type="radio" name="method" value="pseudo-save"<? if($admindefaults[4][0] == "pseudo-save") echo "checked"; ?>> 
        <span title="cssbody=[tt] cssheader=[tth] header=[Pseudo-Save] body=[Select 'pseudo-save' if you want all of the benefits of hosting reports for your users without the bloat. After rebranding, users will be presented with a download page explaining how the report has been hosted for them, giving them a link they can promote, but through a bit of magic the file is generated without it being physically saved on your server.]">Pseudo-Save <span style="font-size: 10px">(recommended)</span></span><br>      
        <input type="radio" name="method" value="stream" <? if($admindefaults[4][0] == "stream" || !isset($admindefaults[4][0])) echo "checked"; ?>>
        <span title="cssbody=[tt] cssheader=[tth] header=[Stream] body=[Select 'stream' if you want the file to be automatically downloaded after being rebranded.]">Stream</span><br>
        <input type="radio" name="method" value="save"<? if($admindefaults[4][0] == "save") echo "checked"; ?>> 
        <span title="cssbody=[tt] cssheader=[tth] header=[Save] body=[Select 'save' if you want a copy of the pdf saved on your server and for the user to be presented with a download page with a download link for the report. <br><br>Be aware that this file will reside on your server until you specifically decide to delete it, so if you have large PDF files and limited server space, selecting 'Pseudo-Stream' or 'Stream' would be a much better option.<br><br>The upshot to using save (as with Pseudo-Save) is that you can edit the download page template from the templates menu and instruct the user that they can give away the file directly from your server, even if they don't have their own webhost by using the same link they used to download the pdf.]">Save</span><br>
      </td>
    </tr>
    <tr>
      <td>
        <span title="cssbody=[tt] cssheader=[tth] header=[Secret Key] body=[Enter a string of characters that acts like a password for the rebrander to protect it from people directly accessing the rebrander.]">Secret Key</span>
      </td>
      <td>
        <input type="text" name="secretkey" value="<?=$admindefaults[4][1]?>">
      </td>
    </tr>
    <tr>
      <td><span title="cssbody=[tt] cssheader=[tth] header=[Earn Extra Money] body=[Select yes to earn extra income passively by including a <i>Powered By Easy Viral PDF Brander</i> link on this rebrander's download page and rebrand forms (just like the one at the bottom of this page)]">Earn Extra Money</span></td>
      <td>
        <input type="radio" value="yes" name="showpoweredby" <?=($admindefaults[4][2] == "yes" ? "checked " : "")?>/> Yes  <input type="radio" value="no" name="showpoweredby" <?=($admindefaults[4][2] == "no" ? "checked " : "")?>/> No
      </td>
    </tr>
    <tr>
      <td><span title="cssbody=[tt] cssheader=[tth] header=[Downline Builder] body=[Select yes to allow the rebrander to work like a downline builder, allowing you to let a user who rebrands the report send others to a rebrand form with the join links of that user.<br><br>If you elect to do this, make sure you update the download page template in the 'Templates' toolbar with the #DOWNLINEBUILDER# tag in an appropriate spot.]">Enable "Downline Builder"</span></td>
      <td>
        <input type="radio" value="yes" name="allowdlb" <?=($admindefaults[4][3] == "yes" ? "checked " : "")?>/> Yes  <input type="radio" value="no" name="allowdlb" <?=($admindefaults[4][3] == "no" ? "checked " : "")?>/> No      
      </td>
    </tr>
<?php if($admindefaults[4][3] == "yes"){ 
  $url = "\"http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php?file=".str_replace(".pdf","",$_REQUEST["file"])."&s=".$admindefaults[4][1]."&iframe=yes&code=\".(isset(\$_GET[\"code\"]) ? \$_GET[\"code\"] : \$_GET[\"custom_code\"])";
?>
    <tr>
      <td colspan="2"><span title="cssbody=[tt] cssheader=[tth] header=[Downline-Builder Base URL] body=[<b>Note: Please view the video tutorials (the help link at the top of this page) for further details on how the downline-building rebrander system works.</b><br><br>Enter the full url to the page you want users to advertise as the link to their downline-building rebrander.<br><br>If you are using the basic system, simply enter the url to this rebrander installation, <?="http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php"?><br><br>If you are using one of the autoresponder integrations, enter the full url to the page you have your autoresponder signup form on, placing a ?r=#DLBCODE# at the end of the url, ie http:/example.com/arsignupform.php?r=#DLBCODE#.<br><br>If you are not planning on using an autoresponder integration, but want the rebrand form to match the look and feel of your website, enter the url to the page you installed the iframe rebrander snippet on, ending it with a ?code=#DLBCODE#, ie http://example.com/dlbiframe.php?code=#DLBCODE#.]">Downline-Building Base URL</span><br>      
        <input style="margin-left: 30px" type="text" value="<?=$admindefaults[4][5]?>" name="dlb_url" size="60" /><br>
        <span style="margin-left:30px; font-size: 12px"><a href="" id="snippetlink">View Advanced Snippets</a></span></td>       
    </tr>
<script type="text/javascript">    
$(function(){
  $('#snippetlink').click(function(){
			$('#dialog').remove();
			$('body').append('<div id="dialog" \/>');
			$('#dialog').dialog({	
				autoOpen: false,
				bgiframe: true,
				resizable: false,
				width: 700,
				position: ['center','top'],
				overlay: { backgroundColor: '#000', opacity: 0.5 },			
			});		
			$('#dialog').dialog('option', 'title', 'Advanced Code Snippets');
			$('#dialog').dialog('option', 'modal', true);
			$('#dialog').dialog('option', 'buttons', {
				'Close': function() {
					$(this).dialog('close');
				}
			});
		
			$('#dialog').html('<p>For instructions regarding usage of these snippets, please <a target="_blank" href="http://easyviralpdfbrander.com/advanced_tutorials.php">check out the video tutorials on the downline-building rebrander functionality.</a></p><p><b>Aweber</b></p><textarea class="snippet" rows="4"><input type="hidden" name="custom s" value="<?php echo $admindefaults[4][1]; ?>" />\n<input type="hidden" name="custom file" value="<?php echo str_replace(".pdf","", $_REQUEST["file"]); ?>" />\n<input type="hidden" name="custom code" value="&lt;?php echo $_GET["r"]; ?&gt;" /></textarea>\n\
      <p><b>GetResponse</b></p><textarea rows="4" class="snippet"><input type="hidden" name="custom_skey" id="custom_skey" value="<?php echo $admindefaults[4][1]; ?>" />\n<input type="hidden" name="custom_file" id="custom_file" value="<?php echo str_replace(".pdf","", $_REQUEST["file"]); ?>" />\n<input type="hidden" name="custom_code" id="custom_code" value="&lt;?php echo $_GET["r"]; ?&gt;" /></textarea>\n\
      <p><b>TrafficWave</b></p>Basic Integration Snippet<br><textarea class="snippet" rows="3"><?php echo "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php?file=".str_replace(".pdf","",$_REQUEST["file"])."&s=".$admindefaults[4][1]."&code=&lt;?php echo \$_GET[\"r\"]; ?&gt;"; ?></textarea><br>Iframe Snippet:<textarea class="snippet" rows="1"><?php echo "?code=&lt;?php echo \$_GET[\"r\"]; ?&gt;"; ?></textarea>\n\
      <p><b>Iframe Downline-Building Rebrander Integration</b></p><textarea rows="7" class="snippet">&lt;?php $evpdf_url=<?php echo $url?>; ?&gt;\n<iframe src="&lt;?php echo $evpdf_url; ?&gt;" width="80%" height="400" frameborder="0">\n<p>Your browser does not support iframes. <a target="_blank" href="&lt?php echo $evpdf_url; ?&gt;">Click here</a> to rebrand.</p>\n</iframe></textarea>');
			$('#dialog').dialog('open');
			return false;     		
	});
});	
</script>          
<?php } ?>     
    <tr>
      <td><span title="cssbody=[tt] cssheader=[tth] header=[Ask For Email] body=[Select 'yes' to to ask for the user's email address at the top of the rebranding form. This will enable the system to send them an email with their download link for future reference, and also allow you to resend the user's download links in case they forget them.]">Ask For Email</span></td>
      <td>
        <input type="radio" value="yes" name="store_email" <?=($admindefaults[4][4] == "yes" ? "checked " : "")?>/> Yes  <input type="radio" value="no" name="store_email" <?=($admindefaults[4][4] == "no" ? "checked " : "")?>/> No
      </td>
    </tr>        
    </table>
    <div class="buttons" style="width: 50%; margin: 0px auto">
      <button type="submit" name="update_basic_settings" class="positive" style="width: 100%">
          <img src="images/icons/cog_go.png" alt=""/> 
          Update Basic Settings
      </button>
    </div>
    <div class="clear"><br /></div>
    </form>
    </div>

<?php    
  }else if($_GET["page"] == "fields"){
    $fields = getFields(FOLDER."/".$_REQUEST["file"]);
    $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
    foreach($admindefaults[0] as $key => $value){
        if(isset($fields[$key]))
          $fields[$key] = $value;
    }    
    foreach($fields as $key => $value){
      $str="";
      $str2="";
      if(isset($admindefaults[1][$key]))
        $str = $admindefaults[1][$key];
      if(isset($admindefaults[2][$key]))
        $str2 = $admindefaults[2][$key];  
      $fieldsettings[$key] = "
          <li id=\"".$key."\" class=\"ui-widget ui-widget-content ui-corner-all\" style=\"font-size: .8em\">
            <div class=\"ui-widget-header ui-corner-all handle\"><div style=\"cursor: move; background-image: url(images/icons/move.png); background-repeat: no-repeat; background-position: left center\"><span title=\"cssbody=[tt] cssheader=[tth] header=[TAG: $key] body=[Everywhere zzz".$key."zzz appears in links in the pdf will be rebranded]\">".$key."</span></div></div>
              <table style=\"width: 95%\" border=\"0\" spacing=\"0\">
              <tr><td colspan=\"2\"><a href=\"#\" id=\"mce_d2_$key\" class=\"mce_load\"><span title=\"Edit in WYSIWYG Editor\"><img src=\"images/icons/monitor_edit.png\" border=\"0\" /></span></a> <span title=\"cssbody=[tt] cssheader=[tth] header=[Description] body=[Enter a description for this rebrandable field. HTML tags are allowed. Consider giving instruction on what the referral id to enter actually is (ie an id number, or a username)]\"\>Description:</span><br>
              <textarea style=\"font-size: 9pt; width: 100%\" rows=\"3\" cols=\"50\" id= \"d2_$key\" name=\"d_$key\">".$str."</textarea><br>
              </td></tr>
              <tr><td>
              <span title=\" cssbody=[tt] cssheader=[tth] header=[Join URL] body=[Enter the url which visitors will be presented with on the rebrand page in order to join the affiliate program. Place the tag {refid} where the referral id should go in the link, if applicable. ie http://site.com/?refid={refid}]\">Join URL:</span><br>
              <input style=\"font-size: 9pt\" name=\"u_$key\" type=\"text\" size=\"60\" value=\"".$str2."\"></td>            
              <td><span title=\"cssbody=[tt] cssheader=[tth] header=[Default Referral ID] body=[This is the default referral id for the program. If the user rebranding the book fails to enter an id for a program, this ID will be slipped in where zzzTAGNAMEzzz is in the pdf links.]\">Default Referral Id</span><br /><input style=\"font-size: 9pt\" name=\"rb_$key\" type=\"text\" size=\"20\" value=\"".$value."\"></td></tr></table>
          </li>";
    }
?>
     <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Rebrandable Field Settings</div>
     <?=print_success($updatemsg)?>
     <form action="<?=$selflink?>" method="post" id="fieldsettings">
     <p style="font-size: 14px; margin: 10px">Drag and drop the bars below to reflect the order in which you'd like users to see the fields on the rebranding form.</p>
     <ul style="margin: 20px auto" id="sortable">
<?php
    if(isset($admindefaults[5]) && is_array($admindefaults[5])){
      foreach($admindefaults[5] AS $value){
        if($value != ""){
          $displayed[] = $value;
          echo $fieldsettings[$value];
        }
      }
    } 
    foreach($fields as $key => $value)
      if(!isset($displayed) || array_search($key,$displayed) === false) 
        echo $fieldsettings[$key];
?>      
    </ul> 
        <div class="buttons" style="width: 50%; margin: 0px auto">
          <button style="width: 100%" type="submit" name="update_rebranded_field_settings" class="positive">
              <img src="images/icons/cog_go.png" alt=""/> 
              Update Rebrandable Field Settings
          </button>
        </div>
        <div class="clear"><br /></div>
        <input type="hidden" name="order" id="order" value="">   
      </form> 
     </div>   
<?php
    }else if($_GET["page"] == "templates"){
      $admindefaults = unserialize(file_get_contents(FOLDER."/".$_GET["file"].".defaults"));
      $fontchecked = false;
?>
    <form action="<?=$selflink?>" method="post">
    <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Templates</div>
      <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Theme</div>
        <?=print_success($themeupdatemsg)?>
        <table style="margin: 0px auto">
          <tr><td><span title="cssbody=[tt] cssheader=[tth] header=[Font] body=[Select the default font to use on the rebrand and download pages. Feel free to name one not on the list. Just make sure you spell it correctly.]">Font</span></td>
            <td>
              <input name="font" type="radio" value="Verdana" <?php if($admindefaults[6][0] == "Verdana"){echo "checked"; $fontchecked = true;}?>/><span style="font-family: Verdana">Verdana</span><br />
              <input name="font" type="radio" value="Times New Roman" <?php if($admindefaults[6][0] == "Times New Roman"){echo "checked"; $fontchecked = true;}?>/><span style="font-family: Times New Roman">Times New Roman</span><br />
              <input name="font" type="radio" value="Arial" <?php if($admindefaults[6][0] == "Arial"){echo "checked"; $fontchecked = true;}?>/><span style="font-family: Arial">Arial</span><br />
              <input name="font" type="radio" value="" <?=(!$fontchecked ? "checked" : "" )?>/>Other: <input type="text" size="10" name="customfont" value="<?=(!$fontchecked ? $admindefaults[6][0] : "")?>" /><br />
            </td>
          </tr>
          <tr><td><span title="cssbody=[tt] cssheader=[tth] header=[Text Color] body=[Set the default text color to be used on the download and rebrand pages]">Text Color</span></td><td><input type="color" name="textcolor" value="<?=$admindefaults[6][1]?>" hex="true" /></td></tr>
          <tr><td><span title="cssbody=[tt] cssheader=[tth] header=[Background Color] body=[Set the background color to be used on the download and rebrand pages. This color will only be used if you are using the iframe integration method]">Background Color</span></td><td><input type="color" name="backgroundcolor" value="<?=$admindefaults[6][2]?>" hex="true" /></td></tr>
          <tr><td><span title="cssbody=[tt] cssheader=[tth] header=[Table Border Color] body=[Set the default border color to be used on the rebrand form in the tables which house the description, join link, and input boxes for every rebrandable field]">Table Border Color</span></td><td><input type="color" name="bordercolor" value="<?=$admindefaults[6][3]?>" hex="true" /></td></tr>
        </table>
        <div class="buttons" style="width: 50%; margin: 0px auto">
          <button style="width: 100%" type="submit" name="update_theme" class="positive">
              <img src="images/icons/cog_go.png" alt=""/> 
              Update Theme
          </button>
        </div>
        <div class="clear"><br /></div>
      </div>
      <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Rebrander Templates</div>
       <?=print_success($templateupdatemsg)?>
       <p style="margin: 10px; font-size: 14px"><b><a href="" class="mce_load" id="mce_headertxt"><img title="Edit in WYSIWYG Editor" src="images/icons/monitor_edit.png" border="0" /></a> Rebrander Form Header</b><br />Enter the text/html you want to display above the rebrander form.</p>
       <textarea name="formheadtemplate" id="headertxt" class="tinymce" style="width: 90%; height: 170px; margin-left: 5%; margin-right: 5%; text-align: left" rows="10" cols="100"><?=$admindefaults[6][4]?></textarea><br />
       <p style="margin: 10px; font-size: 14px"><b><a href="" class="mce_load" id="mce_footertxt"><img title="Edit in WYSIWYG Editor" src="images/icons/monitor_edit.png" border="0" /></a> Rebrander Form Footer</b><br />Enter the text/html you want to display below the rebrander form. </p>
       <textarea name="formfoottemplate" id="footertxt" class="tinymce" style="width: 90%; height: 170px; margin-left: 5%; margin-right: 5%; text-align: left" rows="10" cols="100"><?=$admindefaults[6][10]?></textarea><br />
       <p style="margin: 10px; font-size: 14px"><b><a href="" class="mce_load" id="mce_dltext"><img title="Edit in WYSIWYG Editor" src="images/icons/monitor_edit.png" border="0" /></a> Download Page</b><br />Enter the text you want to show on the download page. Place #DOWNLOAD# anywhere you wish the download URL to appear, #DOWNLINEBUILDER# anywhere you want a URL to the user's downline-building rebrander form, #SOCIALMEDIA# to display the social media and bookmarking sharing systems, and #UPDATE# anywhere you want a link to the report update form to appear. Use the default template as a guide.</p>
       <p style="margin: 10px; font-size: 14px; font-style: italic">NOTE: Editing this template is unnecessary if you are using the "Stream" delivery method.</p>
       <textarea style="width: 90%; height: 170px; margin-left: 5%; margin-right: 5%; margin-bottom: 20px; text-align: left" rows="10" cols="100" name="dltemplate" id="dltext"><?=$admindefaults[6][5]?></textarea>    
       <p style="margin: 10px; font-size: 14px"><b><span title="cssbody=[tt] cssheader=[tth] header=[Rebrand Submit Button Text] body=[Enter the text you want to appear on the submit button which a user clicks to rebrand the report]">Rebrand Submit Button Text</span></b></p>
       <input name="submittext" type="text" value="<?=$admindefaults[6][9]?>" size="60" style="margin: 10px; width: 90%" />
       <div class="buttons" style="width: 50%; margin: 0px auto">
          <button style="width: 100%" type="submit" name="update_templates" class="positive">
              <img src="images/icons/cog_go.png" alt=""/> 
              Update Templates
          </button>
       </div>
       <div class="clear"><br /></div> 
      </div>
      <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Email Template</div> 
       <?=print_success($emailtemplateupdatemsg)?>
       <p style="margin: 20px 10px 10px 10px; font-size: 14px"><b><span title="cssbody=[tt] cssheader=[tth] header=[From Name] body=[Enter the name the emails should be sent from.]">From Name</span> <input name="fromname" type="text" value="<?=$admindefaults[6][13]?>" size="20" />&nbsp;&nbsp;<span title="cssbody=[tt] cssheader=[tth] header=[From Email] body=[Enter the email address the emails should be sent from.]">From Email </span><input name="fromemail" type="text" value="<?=$admindefaults[6][14]?>" size="20" /></b></p>
       <p style="margin: 20px 10px 10px 10px; font-size: 14px"><b><span title="cssbody=[tt] cssheader=[tth] header=[Email Subject] body=[Enter the subject for the email you want to send users after they rebrand a report.]">Email Subject</span></b></p>
       <input name="emailsubject" type="text" value="<?=$admindefaults[6][11]?>" size="60" style="margin: 10px; width: 90%" />
       <p style="margin: 10px; font-size: 14px"><b><span title="cssbody=[tt] cssheader=[tth] header=[Email Body] body=[Enter the text of the email you want to send users after they rebrand a report. You may use the #DOWNLOAD#, #DOWNLINEBUILDER#, and #UPDATE# tags.]">Email Body</span></b></p>
       <textarea style="width: 90%; height: 170px; margin-left: 5%; margin-right: 5%; margin-bottom: 20px; text-align: left" rows="10" cols="100" name="emailbody"><?=$admindefaults[6][12]?></textarea>
       <div class="buttons" style="width: 50%; margin: 0px auto">
          <button style="width: 100%" type="submit" name="update_email_templates" class="positive">
              <img src="images/icons/cog_go.png" alt=""/> 
              Update Email
          </button>
       </div>
       <div class="clear"><br /></div>     
      </div>
      <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Social Media Templates</div> 
       <?=print_success($socialmediaupdatemsg)?>
       <p style="margin: 10px 0 0 10px; font-size: 14px"><b><span title="cssbody=[tt] cssheader=[tth] header=[Twitter Share Text] body=[Enter a tweet for users to easily send, using the tag {{url}} to link to a hosted copy of their newly rebranded report]">Twitter Share Text</span></b><br />Place {{url}} anywhere you want the pdf download url to appear</p>
       <input name="twittertxt" type="text" value="<?=$admindefaults[6][6]?>" size="60" style="margin: 10px; width: 90%" />
       <p style="margin: 0 0 0 10px; font-size: 14px"><b><span title="cssbody=[tt] cssheader=[tth] header=[Social Media Title] body=[Enter the default post title for a user who submits a link to their rebranded report to one of the many built-in social media sites.]">Social Media Title</span></b></p>
       <input name="smtitle" type="text" value="<?=$admindefaults[6][7]?>" size="60" style="margin: 10px; width: 90%" />
       <p style="margin: 0 0 0 10px; font-size: 14px"><b><span title="cssbody=[tt] cssheader=[tth] header=[Social Media Description] body=[Enter the default post description for a user who submits a link to their rebranded report to one of the many built-in social media sites.]">Social Media Description</span></b></p>
       <input name="smdescription" type="text" value="<?=$admindefaults[6][8]?>" size="60" style="margin: 10px; width: 90%" />           
       <div class="buttons" style="width: 50%; margin: 0px auto">
          <button style="width: 100%" type="submit" name="update_social_media_templates" class="positive">
              <img src="images/icons/cog_go.png" alt=""/> 
              Update Social Media Templates
          </button>
       </div>
       <div class="clear"><br /></div>
      </div>
    </div>
    </form>
<?php
    }
  } else if(isset($_GET["page"]) && $_GET["page"] == "lookup"){
    //Report lookup
    
    //Form submitted
    if(isset($_POST["report_search"])){
      //Search for reports
      if(!empty($_POST["full_filename"]) && file_exists("refids/".str_replace(".pdf", "", $_POST["full_filename"]).".rebranderids")){
        $file = str_replace(".pdf", "", $_POST["full_filename"]).".rebranderids";
        $rblinks[$file] = unserialize(file_get_contents("refids/$file")); 
      }
      else if(!empty($_POST["report_name"]) && !empty($_POST["recover_email"])){
        $recover_email = strtolower($_POST["recover_email"]);
        $dh = opendir("refids");
        while (($file = readdir($dh)) !== false) {
          if(strpos($file,$_POST["report_name"]) !== false){
            $rblink = unserialize(file_get_contents("refids/$file"));
            if(isset($rblink["user_email"]) && strtolower($rblink["user_email"]) == $recover_email){
              $rblinks[$file] = &$rblink;
              unset($rblink);
            } 
          }
        }
        closedir($dh);
      }
      else if(!empty($_POST["recover_email"])){
        $recover_email = strtolower($_POST["recover_email"]);
        $dh = opendir("refids");
        while (($file = readdir($dh)) !== false) {
          $rblink = unserialize(file_get_contents("refids/$file"));
          if(isset($rblink["user_email"]) && strtolower($rblink["user_email"]) == $recover_email){
            $rblinks[$file] = &$rblink;
            unset($rblink);
          }
        }
        closedir($dh);
      }
      else if(!empty($_POST["report_name"])){
        $dh = opendir("refids");
        while (($file = readdir($dh)) !== false) {
          if(strpos($file,$_POST["report_name"]) !== false){
            $rblink = unserialize(file_get_contents("refids/$file"));
              $rblinks[$file] = &$rblink;
              unset($rblink);
          }
        }
        closedir($dh);  
      }
      
      $results = "";
      $i=0;
      if(isset($rblinks) && is_array($rblinks)){
        foreach($rblinks AS $key => $reportdetails){
          $i++;
          //get break between reportname_code.rebranderids
          $undpos = strrpos($key,"_");
          //get reportname
          $reportname = substr($key, 0, $undpos);
          //get code, stripping off .rebranderids
          $code = substr($key, $undpos+1,-13);
          $downloadpage = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/download.php?file=$reportname&code=$code&access=".$reportdetails["access_code"];
          $downloadurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/pdf/".$reportname."_".$code.".pdf";
          $updateurl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/update.php?file=$reportname&code=$code&access=".$reportdetails["access_code"];
          $dlburl = "http://".$_SERVER["HTTP_HOST"].safe_dirname($_SERVER['PHP_SELF'])."/index.php?file=$reportname&s=".$admindefaults[4][1]."&code=$code";
          $results.= '<p style="margin: 30px"><b>Result '.$i.'</b><br>Report Name:<br><input style="width: 100%" type="text" value="'.$reportname.'" /><br>
                      User Email:<br><input type="text" style="width: 100%" value="'.$reportdetails["user_email"].'" /><br>
                      Download Page:<br><input type="text" style="width: 100%" value="'.$downloadpage.'" /><br>
                      Download URL:<br><input type="text" style="width: 100%" value="'.$downloadurl.'" /><br>
                      Update URL:<br><input type="text" style="width: 100%" value="'.$updateurl.'" /><br>
                      Downline Building URL:<br><input type="text" style="width: 100%" value="'.$dlburl.'" /><br></p>';
        }
      }            
    }
    
    //Find all pdfs available for rebranding
    $str="<select name=\"report_name\">";
    $str.="<option value=\"\">Unknown</option>";
    $dh = opendir(FOLDER);
    while (($file = readdir($dh)) !== false) {
      if(substr_count($file,".pdf")==1 && substr_count($file,".defaults")==0 && substr_count($file,".stats")==0){
        if($file.".pdf" == $_POST["report_name"])
          $str.="<option value=\"".str_replace(".pdf","",$file)."\" selected>$file</option>";
        else
          $str.="<option value=\"".str_replace(".pdf","",$file)."\">$file</option>";
      }
    }
    closedir($dh);
    $str.= "</select>";
?>
  <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Lookup a Report</div>
  <form action="admin.php?page=lookup" method="post">
  <?=(!empty($results) ? print_success("$i Results Found").$results : "").(isset($results) && $results == "" ? print_error("No Results Found") : "")?>
  <p style="margin: 20px">Use this tool to find details for your users' reports if they have lost the link to their report, the link to their affiliate id update page, the link to their download page, or the link to their downline-building rebrander form. Do note that if they entered their email and have the link to their report, they can have their details re-emailed to them by filling out the "forgot your details" page linked to on the index page of the rebrander. This advanced search is not directly available to your users because it can put a performance hit on your server and shouldn't be run too often.</p>
  <p style="margin: 20px">Enter below as much information as you know to aid the search. You are likely out of luck if you do not have either the email address, email address and report name, or full filename.</p>
  <table width="400" class="inner">
  <tr><td><span title="cssheader=[tth] cssbody=[tt] header=[Report Name] body=[The file that they have rebranded]">Report Name:</span> </td><td><?=$str?></td></tr> 
  <tr><td><span title="cssheader=[tth] cssbody=[tt] header=[Full Filename] body=[The full filename of their rebranded report. For example, demo_a3s8ksie.pdf]">Full filename:</span></td><td><input type="text" name="full_filename" value="<?=$_POST["full_filename"]?>"></td></tr> 
  <tr><td><span title="cssheader=[tth] cssbody=[tt] header=[Email Address] body=[The email address the user entered on rebrand form]">Email address:</span> </td><td><input type="text" name="recover_email" value="<?=$_POST["recover_email"]?>"></td></tr> 
  <tr><td></td><td><input type="submit" name="report_search" value="Search For Report"></td></tr></table>
  </form>
  </div>
<?
  }
  else{
  //Basic settings
?>
  <div class="ui-widget ui-widget-content ui-corner-all inner"><div class="ui-widget-header ui-corner-all">Edit Global Settings</div>
  <form action="admin.php" method="post">
  <center>
  <?=print_success($updatemsg)?>
  <table width="400" class="inner">
  <tr><td><span title="cssheader=[tth] cssbody=[tt] header=[Admin Password] body=[Change the password you use to enter the rebrander administration area. Must be alphanumeric.]">Admin Password:</span> </td><td><input type="text" name="setpwfirst" value="<?=PASSWORD?>"></td></tr> 
  <tr><td><span title="cssheader=[tth] cssbody=[tt] header=[Admin Clickbank ID] body=[Enter your clickbank ID. Anywhere a link to EasyViralPDFBrander occurs on the download or rebrander page will automatically have your clickbank id.]">Admin Clickbank ID:</span> </td><td><input type="text" name="cb" value="<?=CLICKBANK?>"></td></tr> 
  <tr><td><span title="<?=$templatett?>">PDF Templates Folder:</span> </td><td><input type="text" name="folder" value="<?=FOLDER?>"></td></tr> 
  <tr><td></td><td><input type="submit" name="cpw" value="Change Global Settings"></td></tr></table></center>
  </form>
  </div>
 <?php 
  }
}
echo "<script src=\"boxover.js\"></script>";
include "footer.php";
?>