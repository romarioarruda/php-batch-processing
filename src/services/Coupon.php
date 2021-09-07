<?php
namespace App\Services;

use App\Entities\MasterData;

class Coupon
{
    private $env;
    private $fs;

    public function __construct(array $configEnv, FileSystem $fs)
    {
        $this->env = $configEnv;
        $this->fs = $fs;
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
            
            try {
                $response = MasterData::searchDocument($payload);

                if(empty($response)) {
                    $coupon = str_replace('%26', '&', $coupon);
                    echo "$coupon não existe no BD.\n";
                    $this->fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
                }
            } catch(\Exception $err) {
                $coupon = str_replace('%26', '&', $coupon);
                echo "Falha na request, salvando como pendente.\n";
                $this->fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
            }
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

            try {
                $response = MasterData::saveDocument($payload);

                if(empty($response)) {
                    echo "cupon $coupon n foi salvo n bd.\n";
                    $this->fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
                }
            } catch(\Exception $err) {
                echo "Falha na request, salvando como pendente.\n";
                $this->fs->createFileSync($path."files/pendent_coupons.txt", $coupon);
            }
        }
    }
}
