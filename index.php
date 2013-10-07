<?php 
require 'facebook/facebook.php';
require 'config/fbconfig.php';

//Always place this code at the top of the Page
session_start();

if(isset($_SESSION['uid']))
				   {
					   
$no=rand(1,5);				   
$main_img 		= "1.jpg"; 
$pro_bike	= "car".$no.".jpg"; 
$pro_pic="https://graph.facebook.com/".$_SESSION['uid']."/picture?width=158&height=168";


$opacity		= 100;	// image opacity

$main 		= imagecreatefromjpeg($main_img); // create main graphic
$bike 	= imagecreatefromjpeg($pro_bike); 
$pic 		= imagecreatefromjpeg($pro_pic); 

if(!$main || !$bike || !$pic) die("Error: main image could not be loaded!");

imagecopymerge($main, $pic, 50, 100, 0, 0, 158, 168, $opacity);
imagecopymerge($main, $bike, 250, 100, 0, 0, 408, 387, $opacity);
// print image to screen
header("content-type: image/jpeg"); 
imagejpeg($main);
$file = $_GET['img'];
$name = rand(5, 15).md5($file).".jpg";
 
imagejpeg($main, $name);

$facebook = new Facebook(array(
            'appId' => APP_ID,
            'secret' => APP_SECRET,
			'fileUpload' => true,
            ));
//$facebook->setFileUploadSupport(true);
        $response = $facebook->api(
          '/me/photos/',
          'post',
          array(
            'message' => 'https://apps.facebook.com/seemycar/',
            'source' => '@'.$name
          )
        );
 
imagedestroy($main);  
imagedestroy($bike);  
imagedestroy($pic); 
					   
					   unset($_SESSION['uid']);
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    session_destroy();
	
	

	 
}
else
{
$facebook = new Facebook(array(
            'appId' => APP_ID,
            'secret' => APP_SECRET,
			'fileUpload' => true,
            ));

$user = $facebook->getUser();

if ($user) 
{
  try 
  {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } 
  catch (FacebookApiException $e) 
  {
    error_log($e);
    $user = null;
  }
      if (!empty($user_profile )) {
      
  
        $username = $user_profile['name'];
       	$_SESSION['uid'] = $uid;
echo "<script>window.top.location = 'https://apps.facebook.com/seemycar/';</script>";
        
    } else {
        # For testing purposes, if there was an error, let's kill the script
        die("There was an error.");
    }
  
}
else {
    # There's no active session, let's generate one
	$login_url = $facebook->getLoginUrl(array( 'scope' => 'email,publish_actions,photo_upload'));
   //header("Location: " . $login_url);
  echo "<script>window.top.location = '".$login_url."';</script>";
   

	
}
 
}
?>

      
