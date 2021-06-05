<?php
return [
    'files-path' => '/media/',
    /*
    |--------------------------------------------------------------------------
    | Specify all the allowed file extensions a user can upload on the server
    |--------------------------------------------------------------------------
    */
    'allowed-types' => '.jpg,.png,.pdf,.jpeg,.doc,.docx,.ppt,.pptx,.rar,.zip,.mp4,.mov',
    'allowed-mimetypes' => 'image/jpeg,image/png,image/jpg,image/gif,video/mp4,video/quicktime,video/x-matroska',
	'allowed-video-mimetypes' => [
		'video/mp4',
		'video/quicktime',
		'video/x-matroska'
	],
    /*
    |--------------------------------------------------------------------------
    | Determine the max file size upload rate
    | Defined in MB
    |--------------------------------------------------------------------------
    */
    'max-file-size' => 15,
    'max-image-size' => 5,
    'max-video-size' => 15,

    /*
    |--------------------------------------------------------------------------
    | Determine the max total media folder size
    |--------------------------------------------------------------------------
    | Expressed in bytes
    */
    'max-total-size' => 1000000000000,
];
