<?php
namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class Coupon
{
    private $env;
    private $fs;
    private $queueConn;
    private $queue;

    public function __construct(array $configEnv, FileSystem $fs)
    {
        $this->env = $configEnv;
        $this->fs = $fs;

        $this->queueConn = new AMQPStreamConnection(
            $configEnv['RABBITMQ_HOST'],
            $configEnv['RABBITMQ_PORT'],
            $configEnv['RABBITMQ_USER'],
            $configEnv['RABBITMQ_PASS']
        );

        $this->queue = $this->queueConn->channel();
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
      
            $payload = json_encode([
                $this->env['APP_ACCOUNT'],
                $this->env['APP_KEY'],
                $this->env['APP_TOKEN'],
                $this->env['APP_ENTITY_NAME'],
                "coupon=$coupon"
            ]);

            $this->queue->queue_declare('WorkerSearchCoupon', false, true, false, false);

            $msg = new AMQPMessage($payload);

            $this->queue->basic_publish($msg, '', 'WorkerSearchCoupon');
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

            $payload = json_encode([
                $this->env['APP_ACCOUNT'],
                $this->env['APP_KEY'],
                $this->env['APP_TOKEN'],
                $this->env['APP_ENTITY_NAME'],
                ["coupon" => $coupon, "ativo" => true]
            ]);

            $this->queue->queue_declare('WorkerSaveCoupon', false, true, false, false);

            $msg = new AMQPMessage($payload);

            $this->queue->basic_publish($msg, '', 'WorkerSaveCoupon');
        }
    }

    public function __destruct()
    {
        $this->queue->close();
        $this->queueConn->close();
        echo "\nMessage broker: conexão finalizada.\n";
    }
}
