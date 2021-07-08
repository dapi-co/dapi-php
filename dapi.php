<?php

include 'products/auth.php'; 
include 'products/data.php';
include_once 'products/requester.php'; 

require_once __DIR__.'/vendor/autoload.php'; 
class DapiClient
{
  private string $appSecret;

  private DapiRequester $requester; 

  public $auth; 
  public $data; 
  public $payment; 
  public $metadata; 
  public $operation; 

  function __construct($appSecret)
  {
    $this->appSecret = $appSecret;
    $this->requester = new DapiRequester($this->appSecret); 
    $this->auth = new Auth($this->requester);
    $this->data = new Data($this->requester);  
  }

  public function getAppSecret(){
    return $this->appSecret; 
  }

  public function handleSDKRequests()
  {
    $body['appSecret'] = $this->appSecret;
    $headers['host'] = 'dd.dapi.co';
    $headers['Host'] = 'dd.dapi.co';
    return $this->requester->makeRequest('', $body, $headers, true);
  }
}
