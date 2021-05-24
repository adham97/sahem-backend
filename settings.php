<?php
		
	header("Access-Control_Allow_Origin: *");
	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Headers: application/json;charset=utf-8"); 
	header("Access-Control-Allow-Methods: GET");

	$data['main_color'] = '#003866';
	$data['main_dark_color'] = '#4C7393';
	$data['second_color'] = '#000000';
	$data['second_dark_color'] = '#ccccdd';
	$data['accent_color'] = '#8f8f96';
	$data['accent_dark_color'] = '#8f8f96';
	$data['scaffold_color'] = '#f9f9f9';
	$data['scaffold_dark_color'] = '#2a2c2b';
	//$data['googleMapsKey'] = '';
	$data['language'] = 'en';
	
	$response['data'] = $data;
	die(json_encode($response));exit;
?>