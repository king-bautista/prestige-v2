<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Binaries
    |--------------------------------------------------------------------------
    |
    | Paths to ffmpeg nad ffprobe binaries
    |
    */

    'binaries' => [
        //'ffmpeg'  => env('FFMPEG', '/usr/bin/ffmpeg'),
        //'ffprobe' => env('FFPROBE', '/usr/bin/ffprobe')
        'ffmpeg'  => env('FFMPEG', 'C:/FFmpeg/bin/ffmpeg.exe'),
        'ffprobe' => env('FFPROBE', 'C:/FFmpeg/bin/ffprobe.exe')
    ]
];