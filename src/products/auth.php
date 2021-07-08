<?php

class Auth
{
  private $client;
  function __construct($client)
  {
    $this->$client = $client;
  }

  public function exchangeToken($accessCode, $connectionID)
  {
    $body['appSecret'] = $this->client->getAppSecret();
    $body['accessCode'] = $accessCode;
    $body['connectionID'] = $connectionID;

    return $this->client->makeRequest('/auth/ExchangeToken', $body, []);
  }

  public function delinkUser($accessToken, $userSecret)
  {
    return $this->client->makeAuthenicatedRequest('/data/DelinkUser', $accessToken, $userSecret, []);
  }
}
