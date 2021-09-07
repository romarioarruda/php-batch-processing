<?php
require_once 'vendor/autoload.php';

use Predis\Client;
use \Superbalist\PubSub\Redis\RedisPubSubAdapter;
use App\Entities\MasterData;
use App\Services\FileSystem;

$configEnv = parse_ini_file('env.ini');

$client = new Client([
    'host' => $configEnv['REDIS_HOST'],
    'port' => $configEnv['REDIS_PORT'],
    'read_write_timeout' => $configEnv['read_write_timeout'],
]);

$worker = new RedisPubSubAdapter($client);

$fs = new FileSystem;

$worker->subscribe('WorkerSaveCoupon', function($payload) use ($fs) {
    $coupon = $payload[4]['coupon'];

    $path = __DIR__ . "/../";

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
});