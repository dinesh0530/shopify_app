<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* start of file my_helper.php */
function array_to_dropdown( $array , $val="" , $key="id" )
{
	$return = array("" => "Select");
	if(is_array($val))
	{
		foreach($array as $arr)
		{
			$value = array();
			foreach($val as $vas)
			{
				$value[] = $arr->{$vas};
			}
			$return[$arr->{$key}] = implode(" ", $value);
		}
	} else {
		foreach($array as $arr)
		{
			$return[$arr->{$key}] = $arr->{$val};
		}
	}
	return $return;
}
function printR( $array = array() )
{
	echo "<pre>" . print_r( $array , true ) . "</pre>" ;
}
function email_send($to, $subject, $message, $from="" )
{
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n";
	if(!empty($from))
	{
		$headers .= "From: $from\r\n";
	}
	return mail($to, $subject, $message, $headers);
}
function appointment_status($status = 0){
	$arr = array(
				"0"=>"Stage 1",
				"1"=>"Stage 1",
				"2"=>"Stage 2",
				"3"=>"Stage 3",
				"4"=>"Stage 4",
				"5"=>"Stage 5"
			);
	
	return $arr[$status] ; 
	
}
function location_name($id = 0){
		$CI =& get_instance();
		if($id > 0)
		{
			$CI->db->where(LOCATION.".id", $id);
		}
		$query = $CI->db->get(LOCATION);
			$result =  $query->row();
		
	
	
	return $result->unit_name; 
	
}
function dr_name($id = 0){
	$CI =& get_instance();
		if($id > 0)
		{
			$CI->db->where(USER.".id", $id);
		}
		$query = $CI->db->get(USER);
			$result =  $query->row();
		
	
	
	return $result->last_name.' '.$result->name;
	
}

function dr_shipto($id = 0){
	$CI =& get_instance();
		if($id > 0)
		{
			$CI->db->where(USER.".id", $id);
		}
		$query = $CI->db->get(USER);
			$result =  $query->row();
		
	
	if($result->first_street_address!=''){
		return $result->first_street_address.','.$result->first_address_city.' '.$result->first_address_state.' '.$result->first_address_zip;
	}
	else{
		return 'Not Available';
	}
	
}
function site_uri($url="")
{
	return site_url($url).($_SERVER['QUERY_STRING']? "?".$_SERVER['QUERY_STRING']: "");
}

/* End of file my_helper.php */
/* Location: ./application/helpers/my_helper.php */
?>