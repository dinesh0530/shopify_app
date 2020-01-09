<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('resize_your_image'))
{
	function resize_your_image($file_name,$width,$height,$productId,$thumb_marker)
	{
		/************ Resize the images **************/						
			$source_path = "./uploads/product$productId/" . $file_name;
			$target_path = "./uploads/product$productId/" . $thumb_marker.$file_name;
			$CI = &get_instance();				
			$CI->load->library('image_lib');
			$imageSize = $CI->image_lib->get_image_properties($source_path, TRUE);
			$newSize = min($imageSize);
			//echo "<pre>"; print_r($imageSize ); die;
			$config = array(
				'image_library' => 'gd2',
				'source_image' => $source_path,
				'new_image' => $target_path,
				'maintain_ratio' => FALSE,
				'create_thumb' => TRUE,
				'thumb_marker' => FALSE,
				'width' => 300,
				'height' => 300,
				'y_axis' => ($imageSize['height']) / 2,
				'x_axis' => ($imageSize['width']) / 2,
				
				
			);
			
			
			$CI->image_lib->initialize($config);				
			if(!$CI->image_lib->resize()) {
			   echo $CI->image_lib->display_errors();
			}
			
			$CI->image_lib->clear();						
		/*************** End Here *********************/
	}
}