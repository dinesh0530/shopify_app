<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('crop_center'))
{	
	//function resize_your_image($file_name,$width,$height,$productId,$thumb_marker)
	function crop_center($file_name, $crop_width, $crop_height,$productId,$thumb_marker) {
	
			$old_name = "./uploads/product$productId/" . $file_name;
			$new_name = "./uploads/product$productId/" . $thumb_marker.$file_name;
			$t = getimagesize($old_name);
	 if(in_array($t[2] , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        		
		if(copy($old_name, $new_name)) { 
		
			// READ WIDTH & HEIGHT OF ORIGINAL IMAGE
			list($current_width, $current_height) = getimagesize($new_name);
			
			// CENTER OF GIVEN IMAGE, WHERE WE WILL START THE CROPPING
			$left	=	($current_width / 2) - ($crop_width / 2);
			$top 	=	($current_height / 2) - ($crop_height / 2);
			
			// BUILD AN IMAGE WITH CROPPED PART
			$new_canvas = imagecreatetruecolor($crop_width, $crop_height);
			//imagefilledrectangle($new_canvas, 0, 0, 99, 99, 0xFFFFFF);
			/* $bgcolor = imagecolorallocate($new_canvas, 255, 255, 255);
			imagefill($new_canvas, 0, 0, $bgcolor); */
			switch ($t['mime']) { 
				case "image/gif": 
					$new_image = imagecreatefromgif($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagegif($new_canvas, $new_name);
					break; 
					
				case "image/jpeg": 
				
					$new_image = imagecreatefromjpeg($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagejpeg($new_canvas, $new_name);
					break; 
					
				case "image/png":
				
					$new_image = imagecreatefrompng($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagepng($new_canvas, $new_name);
					break;
					
				case "image/bmp": 
				
					$new_image = imagecreatefromwbmp($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagewbmp($new_canvas, $new_name, 100);
					break; 
			} 
			
			
		
		} else {
			// Do Nothing
		}
	}
		return true;
	}
	
}


if (!function_exists('user_crop_center'))
{	
	//function resize_your_image($file_name,$width,$height,$productId,$thumb_marker)
	function user_crop_center($file_name, $crop_width, $crop_height,$thumb_marker) {
	
			$old_name = "./uploads/users/" . $file_name;
			$new_name = "./uploads/users/" . $thumb_marker.$file_name;
			$t = getimagesize($old_name);
	 if(in_array($t[2] , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
    {
        		
		if(copy($old_name, $new_name)) {
		
			// READ WIDTH & HEIGHT OF ORIGINAL IMAGE
			list($current_width, $current_height) = getimagesize($new_name);
			
			// CENTER OF GIVEN IMAGE, WHERE WE WILL START THE CROPPING
			$left	=	($current_width / 2) - ($crop_width / 2);
			$top 	=	($current_height / 2) - ($crop_height / 2);
			
			// BUILD AN IMAGE WITH CROPPED PART
			$new_canvas = imagecreatetruecolor($crop_width, $crop_height);
			//imagefilledrectangle($new_canvas, 0, 0, 99, 99, 0xFFFFFF);
			/* $bgcolor = imagecolorallocate($new_canvas, 255, 255, 255);
			imagefill($new_canvas, 0, 0, $bgcolor); */
			switch ($t['mime']) { 
				case "image/gif": 
					$new_image = imagecreatefromgif($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagegif($new_canvas, $new_name);
					break; 
					
				case "image/jpeg": 
				
					$new_image = imagecreatefromjpeg($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagejpeg($new_canvas, $new_name);
					break; 
					
				case "image/png":
				
					$new_image = imagecreatefrompng($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagepng($new_canvas, $new_name);
					break;
					
				case "image/bmp": 
				
					$new_image = imagecreatefromwbmp($new_name);
					imagecopy($new_canvas, $new_image, 0, 0, $left, $top, $current_width, $current_height);
					imagewbmp($new_canvas, $new_name, 100);
					break; 
			} 
			
			
		
		} else {
			// Do Nothing
		}
	}
		return true;
	}
	
}



