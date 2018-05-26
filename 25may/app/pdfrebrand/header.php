<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Easy Viral PDF Brander | Rebrand PDFs On The Fly</title>
<?php if(!isset($_GET["iframe"])){ ?>
<link href="layout.css" rel="stylesheet" type="text/css" />
<?php }
echo '<style type="text/css">
body{
  color: '.$admindefaults[6][1].';
  font-family: '.$admindefaults[6][0].', Verdana, Arial;
  '.(isset($_GET["iframe"]) ? "background-color: ".$admindefaults[6][2].";" : "").'
}
.field_table, .field_table tr, .field_table td{
  border: 1px solid '.$admindefaults[6][3].';  
}
</style>';
?>   
</head>
<body>
<?php if(!isset($_GET["iframe"])){ ?>
		<div id="wrap">      
      <div id="header">      
      </div>          
      <div id="main">
<?php } ?>      