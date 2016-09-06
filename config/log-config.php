<?php

return [
    'logConfig' => [
            'name' => 'app',
            //'path' => __DIR__ . '/../logs/'.date("Y-m-d").'.log'
            'path'        => __DIR__.'/../../logs/claims-app.log',
            'mailFrom'    => 'alex@kwiksure.com',
            'mailTo'      => 'alex@kwiksure.com',
            'mailSubject' => 'claims error log',
            'level'       => 100,
            //'level'       => 400,
    ],
];
