<?php

namespace Dapi\Products; 

class ACH
{
  private DapiRequester $client;
  function __construct(DapiRequester $client)
  {
    $this->client = $client;
  }

  public function createPull($accessToken, $userSecret, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/ach/pull/create', $accessToken, $userSecret, $body);
  }

}