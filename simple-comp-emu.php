<?php

include_once('hardware.php');
include_once('screen.php');

$hardware = new Hardware(new Screen, json_decode(file_get_contents($argv[1])));
$halt = false;

while (!$halt) {
    usleep(100000);

    $hardware->instructionRegisterSet(
        $hardware->ramGet(
            $hardware->programCounterGet()
        )
    );
}
