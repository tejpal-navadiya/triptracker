<?php 
return [
    'apps' => [
    [
        'id' => env('PUSHER_APP_ID'),
        'name' => 'your_app_name',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'path' => '',
        'capacity' => null,
        'host' => env('APP_URL'),
        'port' => 6001,  // Ensure this matches the port you're using
        'scheme' => 'http',  // or 'https' if you're using SSL
    ],
],

];