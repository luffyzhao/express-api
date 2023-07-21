<?php

$number = 866;

echo randomAlphanumeric(866);

function randomAlphanumeric(int $count){
    $char = '';
    for ($index = 0; $index < $count; $index++){
        $char .= chr(rand(100, 122));
    }
    return $char;
}