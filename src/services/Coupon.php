<?php
namespace App\Services;

use Predis\Client;
use \Superbalist\PubSub\Redis\RedisPubSubAdapter;

class Coupon
{
    private $env;
    private $fs;
    private $queue;

    public function __construct(array $configEnv, FileSystem $fs)
    {
        $this->env = $configEnv;
        $this->fs = $fs;

        $client = new Client([
            'host' => $configEnv['REDIS_HOST'],
            'port' => $configEnv['REDIS_PORT'],
            'read_write_timeout' => $configEnv['read_write_timeout'],
        ]);

        $this->queue = new RedisPubSubAdapter($client);
    }

    public function searchCoupon(): void
    {
        $path = __DIR__ . "/../";
        $readFile = $this->fs->createReadStream($path."data/base_coupons.txt");
      
        foreach($readFile as $key => $line) {
            $lineKey = ($key+1);

            $coupon = trim($line);

            echo "line: $lineKey - cupon: $coupon\n";

            $coupon = str_replace('&', '%26', $coupon);
      
            $payload = [
                $this->env['APP_ACCOUNT'],
                $this->env['APP_KEY'],
                $this->env['APP_TOKEN'],
                $this->env['APP_ENTITY_NAME'],
                "coupon=$coupon"
            ];

            $this->queue->publish('WorkerSearchCoupon', $payload);
        }
    }

    public function saveCoupon(): void
    {
        $path = __DIR__ . "/../";
        $readFile = $this->fs->createReadStream($path."data/base_coupons.txt");

        foreach($readFile as $key => $line) {
            $lineKey = ($key+1);

            $coupon = trim($line);

            echo "line: $lineKey - cupon: $coupon\n";

            $payload = [
                $this->env['APP_ACCOUNT'],
                $this->env['APP_KEY'],
                $this->env['APP_TOKEN'],
                $this->env['APP_ENTITY_NAME'],
                ["coupon" => $coupon, "ativo" => true]
            ];

            $this->queue->publish('WorkerSaveCoupon', $payload);
        }
    }
}
