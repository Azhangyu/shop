<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    //支持中文提示
    'language' => 'zh-CN',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
