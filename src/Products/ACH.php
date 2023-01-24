<?php

namespace Dapi\Products; 

class ACH
{
  private DapiRequester $client;
  function __construct(DapiRequester $client)
  {
    $this->client = $client;
  }

  public function createPull($accessToken, $userSecret, $transferData, $userInputs = [], $operationID = "")
  {
    $transferData['userInputs'] = $userInputs;
    $transferData['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/ach/pull/create', $accessToken, $userSecret, $transferData);
  }

}
