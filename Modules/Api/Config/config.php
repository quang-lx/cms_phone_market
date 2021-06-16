<?php

return [
    'name' => 'Api',
	'allowed-mimetypes' => 'image/jpeg,image/png,image/jpg,image/gif,image/bmp,image/webp,video/mp4,video/x-matroska,video/x-msvideo,video/x-ms-wmv,video/3gpp,video/quicktime',
	'allowed-video-mimetypes' => [
		'video/mp4','video/x-matroska','video/x-msvideo','video/x-ms-wmv','video/3gpp','video/quicktime',
	],
	'max-image-size' => 5,
	'max-video-size' => 15,
];
