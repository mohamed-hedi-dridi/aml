<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'ssh' => [
            'driver' => 'sftp',
            'host' => '10.90.90.68',
            'port' => 22,
            'username' => 'viauser1',
            'password' => 'V!@mb&9048US',
            'root' => '/home/viauser1/KYC/imagesKYC', // répertoire racine sur le serveur
        ],

        'sftp_current_web' => [
            'driver' => 'sftp',
            'host' => '10.90.90.68',
            'port' => 22,
            'username' => 'viauser1',
            'password' => 'V!@mb&9048US',
            'root' => '/home/viauser1/KYC/imagesKYC/', // répertoire racine sur le serveur
        ],

        // SFTP for old files
        'sftp_old_web' => [
            'driver' => 'sftp',
            'host' => '10.90.90.100',
            'username' => 'user',
            'password' => 'testt', // stored in .env
            'root' => '/home/storage/KYCWeb/',
            'timeout' => 60,
        ],

        'sftp_current_mobile' => [
            'driver' => 'sftp',
            'host' => '10.90.90.68',
            'port' => 22,
            'username' => 'viauser1',
            'password' => 'V!@mb&9048US',
            'root' => '/home/viauser1/testCIN/images/', // répertoire racine sur le serveur
        ],

        // SFTP for old files
        'sftp_old_mobile' => [
            'driver' => 'sftp',
            'host' => '10.90.90.100',
            'username' => 'user',
            'password' => 'testt', // stored in .env
            'root' => '/home/storage/KYCMobile/',
            'timeout' => 60,
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
        public_path('storage') => storage_path('app/public'),
    ],

];
