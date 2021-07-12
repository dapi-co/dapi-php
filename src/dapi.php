<?php

include_once 'products/auth.php';
include_once 'products/data.php';
include_once 'products/metadata.php';
include_once 'products/operation.php';
include_once 'products/payment.php';
include_once 'products/requester.php';

require_once __DIR__ . '/vendor/autoload.php';
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
    $this->metadata = new Metadata($this->requester);
    $this->operation = new Operation($this->requester);
    $this->payment = new Payment($this->requester);
  }

  public function getAppSecret()
  {
    return $this->appSecret;
  }

  public function handleSDKRequests($body, $headers)
  {
    $body['appSecret'] = $this->appSecret;
    $headers['host'] = 'dd.dapi.co';
    $headers['Host'] = 'dd.dapi.co';
    return $this->requester->makeRequest('', $body, $headers, true);
  }
}
