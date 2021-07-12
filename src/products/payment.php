<?php

class Payment
{
  private DapiRequester $client;
  function __construct(DapiRequester $client)
  {
    $this->client = $client;
  }

  public function getBeneficiaries($accessToken, $userSecret, $userInputs = [], $operationID = "")
  {
    $body['userInputs'] = $userInputs;
    $body['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/payment/beneficiaries/get', $accessToken, $userSecret, $body);
  }

  public function createTransfer($accessToken, $userSecret, $transferData, $userInputs = [], $operationID = "")
  {
    $transferData['userInputs'] = $userInputs;
    $transferData['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/payment/transfer/create', $accessToken, $userSecret, $transferData);
  }

  public function transferAutoflow($accessToken, $userSecret, $transferAutoFlowData, $userInputs = [], $operationID = "")
  {
    $transferAutoFlowData['userInputs'] = $userInputs;
    $transferAutoFlowData['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/payment/transfer/create', $accessToken, $userSecret, $transferAutoFlowData);
  }

  public function createBeneficiary($accessToken, $userSecret, $beneficiaryData, $userInputs = [], $operationID = "")
  {
    $beneficiaryData['userInputs'] = $userInputs;
    $beneficiaryData['operationID'] = $operationID;
    return $this->client->makeAuthenicatedRequest('/payment/transfer/create', $accessToken, $userSecret, $beneficiaryData);
  }
}
