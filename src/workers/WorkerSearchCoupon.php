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

$channel->queue_declare('WorkerSearchCoupon');

$callback = function($msg) {
    $payload = json_decode($msg->body);

    $coupon = str_replace('coupon=', '', $payload[4]);

    $path = __DIR__ . "/../";

    $fs = new FileSystem;

    try {
        $response = MasterData::searchDocument($payload);
        if(empty($response)) {
            $coupon = str_replace('%26', '&', $coupon);

            echo "$coupon não existe no BD.\n";

            $fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
        }
    } catch(\Exception $err) {
        echo $err->getMessage();
        echo "\n";

        $coupon = str_replace('%26', '&', $coupon);
        $fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
    }
};

$channel->basic_consume('WorkerSearchCoupon', '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}
 
$channel->close();
$connection->close();
echo "\nWorkerSearchCoupon finalizado.\n";