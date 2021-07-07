<?php
class DapiClient
{
  private string $appSecret;
  private GuzzleHttp\Client $guzzleClient;
  private $API_BASE_URL;
  private $DD_HOST;
  private const USER_AGENT = 'Dapi Connect PHP';

  function __construct($appSecret)
  {
    $this->API_BASE_URL = "https://api.dapi.co/v2";
    $this->DD_HOST = "https://dd.dapi.co";
    $this->appSecret = $appSecret;
    $this->guzzleClient = new GuzzleHttp\Client();
  }

  public function getAppSecret(){
    return $this->appSecret; 
  }

  public function handleSDKRequests()
  {
    $body['appSecret'] = $this->appSecret;
    $headers['host'] = 'dd.dapi.co';
    $headers['Host'] = 'dd.dapi.co';
    return $this->makeRequest('', $body, $headers, true);
  }

  public function makeAuthenicatedRequest($endpoint, $accessToken, $userSecret, $data)
  {
    $data['appSecret'] = $this->appSecret;
    $data['userSecret'] = $userSecret;

    $headers['Authorization'] = 'Bearer ' . $accessToken;

    return $this->makeRequest($endpoint, $data, $headers);
  }

  private function makeRequest($endpoint, $body, $headers, $comingFromSdk = false)
  {

    $headers['User-Agent'] = self::USER_AGENT;
    $headers['Content-Type'] = 'application/json';

    $url = '';
    if ($comingFromSdk) {
      $url = $this->API_BASE_URL . $endpoint;
    } else {
      $url = $this->DD_HOST;
    }

    $response = $this->guzzleClient->request('POST', $url, ['headers' => $headers, 'body' => json_encode($body)]);
    return json_decode($response->getBody(), true);
  }
}
