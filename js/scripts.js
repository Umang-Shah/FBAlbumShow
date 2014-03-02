        var for_app="";        // define variable for arr root
        
		
        // fetches all images of album by passing album id...
        function fetchalbumimages(albumid,count)
        {
            if(albumid=="")
                alert("Error while fetching album images...");
            else
            {
                // ajax call to fetchalbumimages.php to fetch all the images of specific album by passing album id.
                $.post(for_app+"fetchalbumimages.php",
                {
                    albumid:albumid
                },
                function(data)
                {                      
					var images='';
					for (var i = 0; i < data.length; i++) 
					{
						images=images+'<a href="'+data[i]+'"></a>';
					}
					$("#image_link_holder").html(data);
					$("#image_link_holder a:first").click();
                });
            }
        }

        // generate a zip file which contains all the images of given album id.
        function downloadzip(albumid)
        {
            if(albumid=="")
                alert("Error While downloading album ...");
            else
            {   
                //  make an ajax call to CreateZip.php file to generate zip of all the images of given album id.
                $.post(for_app+"CreateZip.php",
                {
                    albumid:albumid
                },
                function(data)
                {
                    var down=" <div> <button type='button'></button> <strong>Zip Created Sucessfuly.</strong> Click <a href='"+for_app+"downloads/"+$album_title+".zip'>here</a> to download your album.</div>";
                    if(data=="1")
                    {
                           $("#zip_holder").html(down);                    
                    }
                    else
                        alert('Error while generaing zip.');                    
                });
			}
        }  
		
		function downloadallalbumzip(albumid)
        {
            if(albumid=="")
                alert("Error While downloading album ...");
            else
            {   
                //  make an ajax call to CreateZip.php file to generate zip of all the images of given album id.
                $.post(for_app+"CreateZip.php",
                {
                    albumid:albumid
                },
                function(data)
                {
                    var down=" <div> <button type='button'></button> <strong>Zip Created Sucessfuly.</strong> Click <a href='"+for_app+"downloads/"+$album_title+".zip'>here</a> to download your album.</div>";
                    if(data=="1")
                    {
                           $("#zip_holder").html(down);                    
                    }
                    else
                        alert('Error while generaing zip.');                    
                });
			}
        }  