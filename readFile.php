<?php

function readFilePerLine($fileName) {
    echo "Lendo o arquivo $fileName\n";
    $file = fopen($fileName, 'r');

    while (!feof($file)) {
        yield fgets($file);
    }

    fclose($file);
}


function readFilePerBytes($fileName) {
    echo "Lendo o arquivo $fileName por bytes.\n";
    $file = fopen($fileName, 'r');
    $bytes = 1000; //cerca de 91 linhas

    while (!feof($file)) {
        yield stream_get_line($file, $bytes);
    }

    fclose($file);
}
