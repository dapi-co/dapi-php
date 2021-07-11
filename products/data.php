<?php

class Data
{

  private DapiRequester $client;
  function __construct(DapiRequester $client)
  {
    $this->client = $client;
  }

  public function getIdentity($accessToken, $userSecret, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/data/identity/get', $accessToken, $userSecret, $body);
  }

  public function getAccounts($accessToken, $userSecret, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/data/accounts/get', $accessToken, $userSecret, $body);
  }

  public function getBalance($accessToken, $userSecret, $accountID, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    $body['accountID'] = $accountID;
    return $this->client->makeAuthenicatedRequest('/data/balance/get', $accessToken, $userSecret, $body);
  }

  public function getTransactions($accessToken, $userSecret, $accountID, $fromDate, $toDate, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    $body['accountID'] = $accountID;
    $body['fromDate'] = $fromDate;
    $body['toDate'] = $toDate;
    return $this->client->makeAuthenicatedRequest('/data/transactions/get', $accessToken, $userSecret, $body);
  }

  public function getCards($accessToken, $userSecret, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/data/cards/get', $accessToken, $userSecret, $body);
  }

  public function getCardBalance($accessToken, $userSecret, $cardID, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    $body['cardID'] = $cardID;
    return $this->client->makeAuthenicatedRequest('/data/balance/get', $accessToken, $userSecret, $body);
  }

  public function getCardTransactions($accessToken, $userSecret, $cardID, $fromDate, $toDate, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    $body['accountID'] = $cardID;
    $body['fromDate'] = $fromDate;
    $body['toDate'] = $toDate;
    return $this->client->makeAuthenicatedRequest('/data/transactions/get', $accessToken, $userSecret, $body);
  }
}
