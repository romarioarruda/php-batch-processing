<?php
namespace App\Entities;

class MasterData
{

  static public function searchDocument(array ...$appData): array
  {
    [$appAccount, $appKey, $appToken, $entityName, $filter] = $appData[0];

    $url = "http://$appAccount.vtexcommercestable.com.br/api/dataentities/$entityName/search?$filter";

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "Accept: application/vnd.vtex.ds.v10+json",
        "Content-Type: application/json",
        "X-VTEX-API-AppKey: $appKey",
        "X-VTEX-API-AppToken: $appToken"
      ],
    ]);

    $response = json_decode(curl_exec($curl), true);
    $err = curl_error($curl);

    curl_close($curl);

    if($err) throw new \Exception('Request error: '.$err);

    return $response;
  }


  static public function saveDocument(array ...$appData): array
  {
    [$appAccount, $appKey, $appToken, $entityName, $payload] = $appData[0];

    $url = "http://$appAccount.vtexcommercestable.com.br/api/dataentities/$entityName/documents";

    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "PATCH",
      CURLOPT_POSTFIELDS => json_encode($payload),
      CURLOPT_HTTPHEADER => [
        "Accept: application/vnd.vtex.ds.v10+json",
        "Content-Type: application/json",
        "X-VTEX-API-AppKey: $appKey",
        "X-VTEX-API-AppToken: $appToken"
      ],
    ]);
        
    $response = json_decode(curl_exec($curl), true);
    $err = curl_error($curl);

    curl_close($curl);
        
    if($err) throw new \Exception('Request error: '.$err);

    return $response;
  }
}
