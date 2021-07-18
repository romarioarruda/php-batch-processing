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

foreach($readFile as $key => $line) {
    $lineKey = ($key+1);
    $coupon = trim($line);
    echo "line: $lineKey - cupon: $coupon\n";
    $coupon = str_replace('&', '%26', $coupon);

    $url = "http://$appAccount.vtexcommercestable.com.br/api/dataentities/$entityName/search?coupon=$coupon";
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

    if ($err) {
        echo "cURL Error #:" . $err;
        exit;
    }

    if(empty($response)) {
        $coupon = str_replace('%26', '&', $coupon);

        echo "$coupon não existe no BD.\n";
        fileGenerator("pendentes_$fileName", $coupon);
    }
}
