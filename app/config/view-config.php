<?php
return [
    'viewConfig'=>[
        // View settings
        'template_path' => __DIR__ . '/../template',
        'twig' => [
            'cache' => __DIR__ . '/../../cache/twig',
            'debug' => true,
            'auto_reload' => true,
        ]
    ],
];
