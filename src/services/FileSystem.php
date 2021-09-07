<?php
namespace App\Services;

use Generator;

class FileSystem
{
    public function createFileSync($fileName, $line): void {
        $file = fopen($fileName, 'a');
    
        fwrite($file, "$line\n");
    
        fclose($file);
    }
    
    public function createReadStream($fileName): Generator {
        $file = fopen($fileName, 'r');
    
        while (!feof($file)) {
            yield fgets($file);
        }
    
        fclose($file);
    }
    
}
