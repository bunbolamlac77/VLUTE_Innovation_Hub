<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Excel Settings
    |--------------------------------------------------------------------------
    |
    | Configure the settings for Laravel Excel (Maatwebsite Excel)
    |
    */

    'exports' => [
        /*
        |--------------------------------------------------------------------------
        | Chunk size
        |--------------------------------------------------------------------------
        |
        | Define how many rows should be queried at the same time. This is
        | performance related. If you're exporting a large dataset, a smaller
        | chunk size will be better for the server, but slower for the export
        | process.
        |
        */
        'chunk_size'             => 1000,

        /*
        |--------------------------------------------------------------------------
        | Memory Limit
        |--------------------------------------------------------------------------
        |
        | Set the memory limit to be used during export.
        |
        */
        'memory_limit'           => '256M',
    ],

    'imports' => [
        'chunk_size'             => 1000,
        'memory_limit'           => '256M',
    ],

    /*
    |--------------------------------------------------------------------------
    | Extension detector
    |--------------------------------------------------------------------------
    |
    | Configure here which writer type should be used when the file extension
    | is not provided. Eg. when a user uploads a file, the extension could
    | not be detected.
    |
    */
    'extension_detector' => [
        'xlsx' => 'xlsx',
        'xlsm' => 'xlsx',
        'xls'  => 'xls',
        'csv'  => 'csv',
        'tsv'  => 'tsv',
        'html' => 'html',
        'ods'  => 'ods',
    ],

    /*
    |--------------------------------------------------------------------------
    | Value Binder
    |--------------------------------------------------------------------------
    |
    | PhpSpreadsheet has a value binder that is responsible for writing a
    | value to a cell. By default there is a default value binder, but you
    | can implement your own value binder.
    |
    */
    'value_binder' => [
        'formatter' => [
            'format' => [
                'xlsx' => 'General',
                'xlsm' => 'General',
            ],
        ],
    ],

    'cache' => [
        'only_cache' => false,
        'expiration' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Local Temporary Path
    |--------------------------------------------------------------------------
    |
    | When exporting and importing files, we use a temporary file, before
    | storing them on the actual disk. Here you can customize that path.
    |
    */
    'local_path'  => storage_path('framework/cache/laravel-excel/'),

    /*
    |--------------------------------------------------------------------------
    | Remote Temporary Path
    |--------------------------------------------------------------------------
    |
    | When dealing with a multi server setup with queues in which you
    | cannot rely on having a shared local temporary path, you might
    | want to store the temporary file on a remote disk. For this you
    | can change the temporary path. Currently supported is only s3.
    |
    | Supported: null, 's3'
    |
    */
    'remote_path'  => null,

    /*
    |--------------------------------------------------------------------------
    | Excel Storage Disk
    |--------------------------------------------------------------------------
    |
    | Define which disk you want to store your exports on.
    |
    */
    'disk' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Transaction Handler
    |--------------------------------------------------------------------------
    |
    | Define which transaction handler you want to use. By default the
    | transaction handler will wrap the import in a database transaction.
    |
    */
    'transaction_handler' => \Maatwebsite\Excel\Transactions\DbTransactionHandler::class,
];

