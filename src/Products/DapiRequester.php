<?php

namespace Dapi\Products; 
use GuzzleHttp; 
class DapiRequester
{
  private string $appSecret;
  private GuzzleHttp\Client $guzzleClient;
  private $API_BASE_URL;
  private $DD_HOST;
  private const USER_AGENT = 'Dapi Connect PHP';
  private const LibraryPlatform = "dapi-php";
  private const LibraryVersion = "1.3.0";

  function __construct($appSecret)
  {
    $this->API_BASE_URL = "https://api.dapi.com/v2";
    $this->DD_HOST = "https://dd.dapi.com";
    $this->appSecret = $appSecret;
    $this->guzzleClient = new GuzzleHttp\Client(['timeout' => 300]);
  }

  public function getAppSecret()
  {
    return $this->appSecret;
  }

  public function makeAuthenicatedRequest($endpoint, $accessToken, $userSecret, $data)
  {
    $data['appSecret'] = $this->appSecret;
    $data['userSecret'] = $userSecret;

    $headers['Authorization'] = 'Bearer ' . $accessToken;

    return $this->makeRequest($endpoint, $data, $headers);
  }

  public function makeRequest($endpoint, $body, $headers, $comingFromSdk = false)
  {

    $headers['User-Agent'] = self::USER_AGENT;
    $headers['Content-Type'] = 'application/json';
    $headers['X-Dapi-Library'] = self::LibraryPlatform;
    $headers['X-Dapi-Library-Version'] = self::LibraryVersion;
    $url = '';
    if ($comingFromSdk) {
      $url = $this->DD_HOST;
    } else {
      $url = $this->API_BASE_URL . $endpoint;
    }

    $response = $this->guzzleClient->post($url, ['headers' => $headers, 'body' => json_encode($body)]);
    return json_decode($response->getBody(), true);
  }
}
