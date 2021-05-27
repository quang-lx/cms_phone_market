<?php
return [
    'files-path' => '/media/',
    /*
    |--------------------------------------------------------------------------
    | Specify all the allowed file extensions a user can upload on the server
    |--------------------------------------------------------------------------
    */
    'allowed-types' => '.jpg,.png,.pdf,.jpeg,.doc,.docx,.ppt,.pptx,.rar,.zip,.mp4,.mov,.heic',
    'allowed-mimetypes' => 'image/heic,image/heif,image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/svg,application/x-rar-compressed,application/zip,application/msword,application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,video/mp4,video/quicktime',
    /*
    |--------------------------------------------------------------------------
    | Determine the max file size upload rate
    | Defined in MB
    |--------------------------------------------------------------------------
    */
    'max-file-size' => '100',

    /*
    |--------------------------------------------------------------------------
    | Determine the max total media folder size
    |--------------------------------------------------------------------------
    | Expressed in bytes
    */
    'max-total-size' => 1000000000000,
];
