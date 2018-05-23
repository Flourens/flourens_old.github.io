<?php

//Set as many urls as you have websites/products that you want to redirect.
//This example shows 4, with 2 assigned based on the values you put in to generate the script.

$url1="https://gouteraser.com/video.html";
$url2="https://gouteraser.com/videonp.html";
$url3="https://gouteraser.com/index.html"; 

//Set your if statements / elseif statements to look in the querystring for the pid value and then route to the appropriate url
// This example shows 4, but you could add as many, or remove any, as you like.
if ($_GET['pid'] == "1")
{
header("Location: $url1".preserve_qs());
}
elseif ($_GET['pid'] == "2")
{
header("Location: $url2".preserve_qs());
}
elseif ($_GET['pid'] == "3")
{
header("Location: $url3".preserve_qs());
}
elseif ($_GET['pid'] == "4")
{
header("Location: $url4".preserve_qs());
}


//This is a default URL, which happens to be URL1, so if they don't pass in pid or query string parameters, they'll go here automatically.
else
{
header("Location: $url1".preserve_qs());
}

//This function keeps existing query-string parameters and appends them to the end of the routed URLs to keep the data that has been sent in
function preserve_qs() {
if (empty($_SERVER['QUERY_STRING']) && strpos($_SERVER['REQUEST_URI'], "?") === false) {
return "";
}
return "?" . $_SERVER['QUERY_STRING'];
}

?>