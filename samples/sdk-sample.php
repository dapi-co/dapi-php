<?php

use Dapi; 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Initialize Dapi-Client with your appSecret here
$dapiClient = new Dapi\DapiClient('APP_SECRET');

$headers = getallheaders();
$body = json_decode(file_get_contents("php://input"), true);

// Make dapiClient automatically handle your SDK requests
if (!empty($body)) {
  // echo json_encode($body, JSON_PRETTY_PRINT);
  echo json_encode($dapiClient->handleSDKRequests($body, $headers)); 
} else {

  http_response_code(400);
  echo "Bad Request: No data sent or wrong request";
}
