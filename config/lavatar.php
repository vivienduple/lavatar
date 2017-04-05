<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Avatar Storage URL
    |--------------------------------------------------------------------------
    |
    | This value is the URL of the Avatar directory.
    */

    'avatarStorageURL' => substr(url('/'), 0, strlen(url('/'))-7).'/storage/app/public/images/',

    /*
    |--------------------------------------------------------------------------
    | Avatar Storage Path
    |--------------------------------------------------------------------------
    |
    | This value is the path of the Avatar directory.
    */
    'avatarStoragePath' => storage_path().'/app/public/images/',
    
];
