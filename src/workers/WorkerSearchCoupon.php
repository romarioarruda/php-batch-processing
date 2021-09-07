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

$worker->subscribe('WorkerSearchCoupon', function($payload) use ($fs) {
    $coupon = str_replace('coupon=', '', $payload[4]);

    $path = __DIR__ . "/../";

    try {
        $response = MasterData::searchDocument($payload);
        if(empty($response)) {
            $couponFilter[] = $coupon;
            $coupon = str_replace('%26', '&', $coupon);
            echo "$coupon não existe no BD.\n";
            $fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
        }
    } catch(\Exception $err) {
        echo $err->getMessage();
        echo "\n";
        
        $couponFilter[] = $coupon;
        $coupon = str_replace('%26', '&', $coupon);
        $fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
    }
});