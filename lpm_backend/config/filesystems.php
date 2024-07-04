<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Specify the default filesystem disk that should be used by the application.
    | Laravel supports several excellent filesystem drivers out of the box!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure the disks that should be used for file storage.
    | Laravel supports several disk based storage drivers as well as FTP based
    | storage drivers.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'), // Penyimpanan lokal di dalam direktori 'storage/app'
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'), // Penyimpanan publik di dalam direktori 'storage/app/public'
            'url' => env('APP_URL').'/storage', // URL untuk akses file di direktori 'storage/app/public'
            'visibility' => 'public', // Izin akses file (public atau private)
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'), // Membuat symlink dari 'public/storage' ke 'storage/app/public'
    ],

];
