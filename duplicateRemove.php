<?php
require_once "readFile.php";
require_once "fileGenerator.php";

$fileName = $argv[1];

if(!$fileName) exit('Informe o nome do arquivo.');

$readFile = file($fileName);

$readFile = array_unique($readFile);

$repetidos = [];

foreach ($readFile as $line) {
    $line = trim($line);

    if (in_array($line, $repetidos)){
        echo "$line já existe, ignorando...\n";
        continue;
    }

    $repetidos[] = $line;

    echo "$line sendo reescrito.\n";
    fileGenerator("sem_duplicacoes.txt", $line);
}
