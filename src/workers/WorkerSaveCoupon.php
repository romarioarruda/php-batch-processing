<?php
require_once 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Entities\MasterData;
use App\Services\FileSystem;

$configEnv = parse_ini_file('env.ini');

$connection = new AMQPStreamConnection(
    $configEnv['RABBITMQ_HOST'],
    $configEnv['RABBITMQ_PORT'],
    $configEnv['RABBITMQ_USER'],
    $configEnv['RABBITMQ_PASS']
);

$channel = $connection->channel();

$channel->queue_declare('WorkerSaveCoupon');

$callback = function($msg) {
    $payload = json_decode($msg->body);

    $coupon = $payload[4]['coupon'];

    $path = __DIR__ . "/../";

    $fs = new FileSystem;

    try {
        $response = MasterData::saveDocument($payload);

        if(empty($response)) {
            echo "cupon $coupon n foi salvo n bd.\n";
            $fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
        }
    } catch(\Exception $err) {
        echo $err->getMessage();
        echo "\n";

        $fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
    }
};

$channel->basic_consume('WorkerSaveCoupon', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}
 
$channel->close();
$connection->close();
echo "\nWorkerSaveCoupon finalizado.\n";