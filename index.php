<?php
require("./api.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get the uri segments 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// the URI has to be in the following structure 
// api/phonenumbers/ show all the numbers
// api/phonenumbers/customer/[customerId] show the numbers of customerId
// api/phonenumbers/[phoneNumber] activate the phoneNumber
// else show not found
if ($uri[2] !== 'phonenumbers') {
    header("HTTP/1.1 404 Not Found");
    exit();
} else {
	$customerId = null;
	$phoneNumber = null;
	$requestMethod = 'GET';
	
	if (isset($uri[3])) {
		if ($uri[3] == 'customer') {
			$customerId = (int) $uri[4];
		} else {
			$phoneNumber = (int) $uri[3];
		}
	}
	
	// Get the request method
	$requestMethod = $_SERVER["REQUEST_METHOD"];

	// Start proccessing the details to get the data in JSON
	$api = new Api($requestMethod, $customerId, $phoneNumber);
}

?>