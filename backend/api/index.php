<?php

$aivenCa = getenv('AIVEN_MYSQL_CA');

if (is_string($aivenCa) && $aivenCa !== '') {
    $caPath = '/tmp/aiven-mysql-ca.pem';

    if (file_put_contents($caPath, $aivenCa) === false) {
        http_response_code(500);
        exit('Unable to prepare the database TLS certificate.');
    }

    putenv('MYSQL_ATTR_SSL_CA='.$caPath);
    $_ENV['MYSQL_ATTR_SSL_CA'] = $caPath;
    $_SERVER['MYSQL_ATTR_SSL_CA'] = $caPath;
}

require __DIR__.'/../public/index.php';
