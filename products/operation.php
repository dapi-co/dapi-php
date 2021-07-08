<?php

class Operation
{
  private DapiRequester $client;
  function __construct(DapiRequester $client)
  {
    $this->client = $client;
  }

  public function getOperationStatus($accessToken, $userSecret, $operationID, $appKey)
  {
    $body['appKey'] = $appKey;
    $body['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/operation/get', $accessToken, $userSecret, $body);
  }
}
