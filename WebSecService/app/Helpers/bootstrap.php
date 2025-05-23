<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;

// Ensure filesystem binding exists
if (!app()->bound('files')) {
    app()->singleton('files', function () {
        return new Filesystem;
    });
}

// Instead of trying to register providers manually, 
// which would require the Application class (not Container),
// we'll just ensure critical bindings exist
$bindings = [
    'config' => function () {
        return app('config');
    },
    'files' => function () {
        return new Filesystem;
    }
];

// Register core bindings safely
foreach ($bindings as $abstract => $concrete) {
    if (!app()->bound($abstract)) {
        app()->singleton($abstract, $concrete);
    }
}

if (!function_exists('emailFromLoginCertificate')) {
    function emailFromLoginCertificate()
        {
        if (!isset($_SERVER['SSL_CLIENT_CERT'])) return null;

        $clientCertPEM = $_SERVER['SSL_CLIENT_CERT'];
        $certResource = openssl_x509_read($clientCertPEM);
        if(!$certResource) return null;
        $subject = openssl_x509_parse($certResource, false);
        if(!isset($subject['subject']['emailAddress'])) return null;
        return $subject['subject']['emailAddress'];
        }
}