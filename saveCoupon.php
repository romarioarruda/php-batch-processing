<?php
require_once "readFile.php";
require_once "fileGenerator.php";

$fileName = $argv[1];
$configEnv = parse_ini_file('env.ini');

if(!$fileName) exit('Informe o nome do arquivo.');
if(!$configEnv) exit('Variáveis de ambiente não definidas.');

$appKey = $configEnv['APP_KEY'];
$appToken = $configEnv['APP_TOKEN'];
$appAccount = $configEnv['APP_ACCOUNT'];
$entityName = $configEnv['APP_ENTITY_NAME'];

$readFile = readFilePerLine($fileName);

$appKey = $appKey;
$appToken = $appToken;

foreach($readFile as $key => $line) {
  $lineKey = ($key+1);
  $coupon = trim($line);

  $url = "http://$appAccount.vtexcommercestable.com.br/api/dataentities/$entityName/documents";
  $curl = curl_init();

  $payload = ["coupon" => $coupon, "ativo" => true];

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
  
  $response = curl_exec($curl);
  $err = curl_error($curl);
  
  curl_close($curl);
  
  if($err) {
    echo "cURL Error #:" . $err;
    exit;
  }

  if(!empty($response)) {
    echo "cupon $coupon salvo n bd.\n";
  } else {
    echo "cupon $coupon n foi salvo n bd.\n";
    fileGenerator("pendentes_$fileName", $coupon);
  }
}
