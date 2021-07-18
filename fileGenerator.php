<?php

function fileGenerator($fileName, $line) {
    echo "Escrevendo no arquivo $fileName\n";
    $file = fopen("cupons/$fileName", 'a');

    fwrite($file, "$line\n");

    fclose($file);
}
