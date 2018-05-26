<?php
//Necessary functions for script

  if(!function_exists('array_fill_keys')){
    function array_fill_keys($arr, $val){
      foreach($arr as $key => $value)
        $newarray[$value] = $val;
      return $newarray;
    }
  }
  
  if(!function_exists('file_get_contents')){
    function file_get_contents($filename){
      $fhandle = fopen($filename, "r");
      $fcontents = fread($fhandle, filesize($filename));
      fclose($fhandle);
      return $fcontents;
    }
  }
  
  if(!function_exists('file_put_contents')){
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
  }
  
  function generateRandom ($length = 10){
    // start with a blank password
    $random = "";
    // define possible characters
    $possible = "0123456789abcdefghijklmnopqrstuvwxyz";  
    // set up a counter
    $i = 0;   
    // add random characters until $length is reached
    while ($i < $length) { 
      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      $random.= $char;
      $i++;
    }
    return $random;
  }
  
  function print_error($errormsg){
    if($errormsg == "")
      return "";
    return '<div class="ui-widget" style="margin-top: 10px">
				<div style="padding: 0pt 0.7em; width: 400px; margin: 0px auto" class="ui-state-error ui-corner-all ui-widget-content"> 
					<p style="margin: 4px"><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span> 
					<strong>Error:</strong> '.$errormsg.'</p>
				</div>
			</div>';
  }
  
  function print_success($alertmsg){
    if($alertmsg == "")
      return "";
    return '<div class="ui-widget" style="margin-top: 10px">
				<div style="padding: 0pt 0.7em; width: 400px; margin: 0px auto" class="ui-state-highlight ui-corner-all ui-widget-content"> 
					<p style="margin: 4px"><span style="float: left; margin-right: 0.3em;"><img src="images/icons/tick.png" /></span> <strong>'.$alertmsg.'</strong></p>
				</div>
			</div>';
  }  
  
  
  //function to help determine link to rebranders
  function safe_dirname($path){
     $dirname = dirname($path);
     return $dirname == '/' ? '' : $dirname;
  }
   
  
  function stripslashes_deep(&$value)
  {
      $value = is_array($value) ?
                  array_map('stripslashes_deep', $value) :
                  stripslashes($value);
  
      return $value;
  }
  
  if((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())    || (ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase'))!="off")) ){
      stripslashes_deep($_GET);
      stripslashes_deep($_POST);
      stripslashes_deep($_COOKIE);
  } 


?>