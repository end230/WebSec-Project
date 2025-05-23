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
