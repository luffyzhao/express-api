<?php

include_once __DIR__ . '/vendor/autoload.php';

echo \Ramsey\Uuid\Uuid::uuid6()->toString();