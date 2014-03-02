<?php
    $for_app="";   
    require_once($for_app."authentication.php");   //include facebook configuration
?>
<!doctype html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<title>FbAlbum</title>
	<meta name="description" lang="en" content="Lightbox 2 is a simple, unobtrusive script used to overlay images on the current page. It's a snap to setup and works on all modern browsers." />
    <meta name="author" content="Lokesh Dhakar">
	<meta name="viewport" content="width=device-width">
	<link rel="shortcut icon" type="image/ico" href="images/favicon.gif" />	
	<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
	<link href='http://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div id="sidebar">
    <!--  App Name -->
	<h1 class="logo"><a href="http://www.lokeshdhakar.com/projects/lightbox2/">FbAlbum</a></h1>  
	<?php
        if($session)
        {
    ?> 
	<!--  Logout -->
	<ul id="nav">
		<li><a class="first" href="<?php echo $for_app;?>logout.php">Logout</a></li>   
	</ul>
	<?php
        }
    ?>
</div>

<div id="content">
<div class="section" id="overview">
	<p class="lead"> Facebook Album Viewer </p>   <!--  App Discription -->
    </br>	
    
</div>  <!-- login div tag -->
<hr />

<div class="section" id="example">

	<?php
        if($session)       
        {
    ?>
			<script type="text/javascript">
			var session=1; // define javascript varible if user is logged in and set to 1.
			</script> 
	 
				<!--  User Name -->
				<h2>Hello, <?php echo $me['name'];?></h2> 
	
				<!-- Here comes the download link for album content -->
				 <div id="zip_holder"> </div>  
	
				<div class="imageRow">
				<div class="set">
				<?php      
						// iterate the album array to fetch all album cover url                  
						foreach ($albums['data'] as $album) 
						{
							// generate src for album cover image
							$pics="https://graph.facebook.com/".$album['id']."/picture?type=album&amp;access_token=".$facebook->getAccessToken();                                                                                
				?>
		
					<!-- Album Details-->
		
					<div class="single" align="center">
					<a>		
							<div class="imgcover" align="center">
								<img src='<?php echo $pics;?>' class="imgcover" onclick="fetchalbumimages('<?php echo $album['id'];?>');" />
							</div>
					</a>
					<p class="album-name">
						<a onclick="fetchalbumimages('<?php echo $album['id'];?>');"><?php echo $album['name'];?></a>
					</p>
						<button id="btn_<?php echo $album['id'];?>" onclick="downloadzip('<?php echo $album['id'];?>','<?php //echo $album['count'];?>');"> Download </button>
						
						<form action='picasa album/Photos.php' method='post'>
							<input type='submit' name='button' value='Move to Google+'>
						</form>
						
						
						
					</div> 
				<?php
						}
				?>
				</div>
				</div>
				<div>
					<button id="btn_<?php echo $album['id'];?>" onclick="downloadallalbumzip('<?php echo $album['id'];?>','<?php //echo $album['count'];?>');"> Download all Album </button>
					<button>Move all to Google+ </button>
				</div>
				
  
			<?php
			}
				else
				{
			?>
					<script type="text/javascript">
					var session=0; // define javascript varible if user is logged in and set to 0.
					</script>
					<!--  Login -->
					<p>
						Click <a href="<?php echo $loginUrl;?>"><b>Facebook</b></a> to connect your Account.
					</p> 
			<?php
			}
			?> 		
</div>

			<div id="image_link_holder">
			</div>

			<div class="loading">
				<div align="center">
					<img src="<?php echo $for_app;?>images/loading.gif" alt="Loading" />            
						<p class="laoding-text">
						Loading Album Images,Please Wait....
						</p>
				</div>
			</div> 			
</div><!-- end #content -->
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="js/jquery.smooth-scroll.min.js"></script>
<script src="js/lightbox.js"></script>
<script src="js/scripts.js"></script>
<script>
  jQuery(document).ready(function($) { 
      $('a').smoothScroll({
        speed: 1000,
        easing: 'easeInOutCubic'
      });

      $('.showOlderChanges').on('click', function(e){
        $('.changelog .old').slideDown('slow');
        $(this).fadeOut();
        e.preventDefault();
      }) 
  });
</script>
</body>
</html>
