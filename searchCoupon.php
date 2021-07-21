<?php
require_once "readFile.php";
require_once "fileGenerator.php";
require_once "src/MasterData.php";

$fileName = $argv[1];
$configEnv = parse_ini_file('env.ini');

if(!$fileName) exit('Informe o nome do arquivo.');
if(!$configEnv) exit('Variáveis de ambiente não definidas.');

$readFile = readFilePerLine($fileName);

foreach($readFile as $key => $line) {
  $lineKey = ($key+1);
  $coupon = trim($line);
  echo "line: $lineKey - cupon: $coupon\n";
  $coupon = str_replace('&', '%26', $coupon);

  $payload = [
    $configEnv['APP_ACCOUNT'],
    $configEnv['APP_KEY'],
    $configEnv['APP_TOKEN'],
    $configEnv['APP_ENTITY_NAME'],
    "coupon=$coupon"
  ];

  $response = MasterData::searchDocument($payload);

  if(empty($response)) {
    $coupon = str_replace('%26', '&', $coupon);

    echo "$coupon não existe no BD.\n";
    fileGenerator("pendentes_$fileName", $coupon);
  }
}
