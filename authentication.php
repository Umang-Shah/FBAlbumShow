<?php
include 'libs/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '307760046024281',
  'secret' => '51df5bcba3bac4d2bb8ad1028ac46543',
  'cookie' => true,
));
$facebook->getAccessToken();

$session = $facebook->getUser();

$me = null;
// echo $session;
if ($session) {
  try {
    $uid = $facebook->getUser();
    $me = $facebook->api('/me');
	//print_r($me);
 // echo "Hello " . $me['name']  . "<br />";  // sample test display name of the user
  } 
  catch (FacebookApiException $e) 
  {
	echo $e->getMessage();
   // error_log($e);
  }
}

if ($me) {

	// it will fetch album cover images
	$albums = $facebook->api('/me/albums'); 
	//iterate the album array
	$logoutUrl = $facebook->getLogoutUrl();
	//echo "<a href='$logoutUrl'>Logout</a>";
}
else 
{
    $params = array("scope" => "user_photos");      // define scope of app
    $loginUrl = $facebook->getLoginUrl($params);
	//echo "<a href='$loginUrl'>Login</a>";	
}
?>