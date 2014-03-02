<?php
	$for_root="../";
    $for_app="";
    include_once($for_app."authentication.php");
  	$facebook->destroySession();
  	//setcookie('fbs_'.$facebook->getAppId(), '', time()-100, '/', $_SERVER['HTTP_HOST']."/rt-challenge/albumater");
  	session_destroy();  	
  	header("location:".$for_app."index.php");
?>