<?php
	$for_root="../";	// define path to root
    $for_app="";		// define path to app
    include_once($for_app."authentication.php");	// include facebook config file
	$album_id = $_POST['albumid'];		// get album id
	//$count_images=$_POST['count_images'];		//get count(total) images in the album
	//$pics = $facebook->api("/{$album_id}/photos?limit=$count_images&offset=0"); 
	$pics = $facebook->api("/{$album_id}/photos?limit=10&offset=0"); 
	
	//build array to get source of album images.	
	$images=array();	
	$image_link="";
	foreach($pics['data'] as $photo)
	{	        
	        $images[]=$photo['source'];
			$image_link=$image_link." <a href='$photo[source]' rel='lightbox[group]'></a> ";
	}	
	// convert php array in to json data.
	//echo json_encode($images);
	echo $image_link;
?>

