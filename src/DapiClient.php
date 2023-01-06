<?php

namespace Dapi; 

use Dapi\Products\Auth; 
use Dapi\Products\Data;
use Dapi\Products\Metadata;
use Dapi\Products\Operation;
use Dapi\Products\Payment;  
use Dapi\Products\ACH;
  
use Dapi\Products\DapiRequester; 

class DapiClient
{
  private string $appSecret;

  private DapiRequester $requester;

  public $auth;
  public $data;
  public $payment;
  public $metadata;
  public $operation;
  public $ach;

  function __construct($appSecret)
  {
    $this->appSecret = $appSecret;
    $this->requester = new DapiRequester($this->appSecret);

    $this->auth = new Auth($this->requester);
    $this->data = new Data($this->requester);
    $this->metadata = new Metadata($this->requester);
    $this->operation = new Operation($this->requester);
    $this->payment = new Payment($this->requester);
    $this->ach = new ACH($this->requester);
  }

  public function getAppSecret()
  {
    return $this->appSecret;
  }

  public function handleSDKRequests($body, $headers)
  {
    $body['appSecret'] = $this->appSecret;
    $headers['host'] = 'dd.dapi.com';
    $headers['Host'] = 'dd.dapi.com';
    return $this->requester->makeRequest('', $body, $headers, true);
  }
}
