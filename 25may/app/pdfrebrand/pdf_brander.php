<?php
/*
COPYRIGHT 2009 JAY HINES
ALL RIGHTS RESERVED
http://jayhines.com

The distribution of this collection 
of functions without Jay Hines' direct
permission is strictly disallowed.
*/

class pdf_brander{
  var $readloc;
  var $filename;
  var $read_handle;
  var $linkarray;
  var $defaultvalues;
  var $modifications;
  
  //Construct rebrander
  //$fn is the desired filename
  //$la is an associative array of tag -> refid
  //$dv is the same as $la, but storing the admin defaults
  //$rl is the location of the pdf to rebrand  
  function pdf_brander($fn, $la, $dv, $rl){
    $this->readloc = $rl;
    $this->filename = $fn;
    $this->linkarray = $la;
    $this->defaultvalues = $dv;    
    foreach($this->linkarray as $key => $value){
      if($value=="")
        $this->linkarray[$key] = $this->defaultvalues[$key];
    }
  }

  //Perform Rebranding, saving pdf to the server
  //$path is the path where the file will be saved  
  function rebrand_and_save($path){   
    //Create File To Write To
    $write_file = $path.$this->filename;
    $write_handle = fopen($write_file, 'w') or die("Couldn't create new file. Make sure the \"pdf\" directory is CHMOD to 777");
    
    //Open File to Read From
    $this->read_handle = fopen($this->readloc, "r") or exit("Unable to open file!");
      
    //Begin Reading / Rebranding  
    while (!feof($this->read_handle)) {
      $line = fgets($this->read_handle);
      //Check for xref table
      if(strpos($line, "xref") !== false){
        //Stream remaining portion of PDF, adjusting xref table
        fwrite($write_handle, $line);
        fwrite($write_handle, $this->correct_xref());  
      }
      else{
        fwrite($write_handle, preg_replace_callback("/zzz([a-zA-Z0-9]+)zzz/", array( &$this, 'rebrand_line'), $line));
      }
    }
    
    //Close Files
    fclose($this->read_handle);
    fclose($write_handle);  
  }
 
  //Perform rebranding, streaming pdf to the user
  function rebrand_and_stream(){
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=".$this->filename);
    header("Content-Transfer-Encoding: binary");           
    
    //Open File to Read From
    $this->read_handle = fopen($this->readloc, "r") or exit("Unable to open file!");
      
    //Begin Reading / Rebranding  
    while (!feof($this->read_handle)) {
      $line = fgets($this->read_handle);
      //Check for xref table
      if(strpos($line, "xref") !== false){
        //Stream remaining portion of PDF, adjusting xref table
        echo $line;
        echo $this->correct_xref();  
      }
      else{
        echo preg_replace_callback("/zzz([a-zA-Z0-9]+)zzz/", array( &$this, 'rebrand_line'), $line);
      }
    }
    
    //Close Files
    fclose($this->read_handle);
  
    //End stream
    die();
  }
  
  //Callback function. Keeps track of address changes for xref table
  function rebrand_line($matches){
    if(isset($matches[1]) && isset($this->linkarray[$matches[1]])){
      $this->modifications[ftell($this->read_handle)] = strlen($this->linkarray[$matches[1]])-strlen($matches[0]);
      return $this->linkarray[$matches[1]];
    }
    return $matches[0];     
  }
  
  //Finish reading document, adjust xref table
  function correct_xref(){
    $remains = "";
    //read in remaining portion.
    while (!feof($this->read_handle)){
      $line = fgets($this->read_handle);
      $remains .= preg_replace_callback("/zzz([a-zA-Z0-9]+)zzz/", array( &$this, 'rebrand_line'), $line);  
    }
    $remains = preg_replace_callback("|(\d{10}) (\d{5}) ([fn])|", array( &$this, 'resolve_xref_offsets'), $remains);
    $remains = preg_replace_callback("|startxref\n([0-9]+)\n%%EOF|", array( &$this, 'resolve_xreftable_offset'), $remains);
    return $remains;
  }
  
  //Correct entries in xref table
  function resolve_xref_offsets($match){
    $offset = intval($match[1]);
    if($offset == 0)
      return $match[0];
    else{
      $sum = 0;
      foreach($this->modifications as $address => $change){
        if($address < $match[1])
          $sum += $change;  
      }
      $newoffset = $offset + $sum;
      return sprintf("%010s",$newoffset)." ".$match[2]." ".$match[3];
    }
  }
  
  //Correct offset to xref table
  function resolve_xreftable_offset($match){
    $sum = 0;
    foreach($this->modifications as $address => $change){
      if($address < $match[1])
        $sum += $change;  
    }
    return str_replace($match[1],$match[1]+$sum,$match[0]);    
  }
  
}

  
  //Return an array containing all brandable fields
  function getFields($readloc){
    $linksarray;
    $pdfstring = file_get_contents($readloc);
    preg_match_all("/zzz([a-zA-Z0-9]+)zzz/", $pdfstring, $linksarray);
    $result = array_unique($linksarray[0]);
    foreach($result as $key => $value){
      if($value != "zzzDLBzzz" && $value != "zzzDLBCODEzzz")
        $result[$key] = str_replace("zzz", "", $value);
      else
        unset($result[$key]); 
    }
    return array_fill_keys($result, "");
  }
?>