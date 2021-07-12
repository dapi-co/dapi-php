<?php

class Metadata
{
  private DapiRequester $client;
  function __construct(DapiRequester $client)
  {
    $this->client = $client;
  }

  public function getAccountsMetadata($accessToken, $userSecret, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/metadata/accounts/get', $accessToken, $userSecret, $body);
  }
}
