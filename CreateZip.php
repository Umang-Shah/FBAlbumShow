<?php
	$for_root="../";	// define root path
    $for_app="";		// define app path
    include_once($for_app."authentication.php");	// include facebook config file
	$album_id = $_POST['albumid'];	 	// get album id
	$album_title = $_GET['album_title']; // get album name
	//$count_images=$_POST['count_images'];	 	//get totatl images in the album
	
	$pics = $facebook->api("/{$album_id}/photos?limit=10&offset=0");	
	//build array to get source of album images.
	$images=array();	
	foreach($pics['data'] as $photo)
	{
		$images[]=$photo['source'];
	}
	$files = $images;
	ini_set('max_execution_time', 0);	// set max_execution_time to make zip file even for large number of files.
	
	$zipname = $for_app.'downloads/".$album_title.".zip';	
	
	$zip = new ZipArchive;		//create object of ZipArchive
	$zip->open($zipname, ZipArchive::OVERWRITE);	
	
	foreach ($files as $file) 		//build zip
	{	  	
	  	$file1=file_get_contents($file);
	  	$zip->addFromString(basename($file),$file1);	  	
	}
	$zip->close();
	echo "1";
?>