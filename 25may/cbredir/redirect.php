<?php

/*add all the redirect links you are using below.  Make sure each line ends with a comma except the last one. 

To send to one of the redirect links below, you make your hoplink look like this:
http://affiliateid.yourclickbankid.hop.clickbank.net?rd=product1
substituting your own clickbank ID, the actual product ID, and the affilate ID in the appropriate places.
 */
$number_of_redirects=4;

$redirects = array(
"v" => "http://www.sciaticasos.com/videonpna.html",
"t1" => "http://www.sciaticasos.com/index.html",
"t2" => "http://www.sciaticasos.com/indexnp.html",
"v2" => "http://www.sciaticasos.com/videonp.html"
);

$rd = $_GET["rd"];

if (array_key_exists($rd, $redirects)) {
    $go = $redirects[$rd];
    header("Location:$go");
    die();
}
?>