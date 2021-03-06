<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$baseUrl = str_replace('/web', '', (new \yii\web\Request)->getBaseUrl());
$config = [
    'id' => 'basic',
    'homeUrl' => $baseUrl . "/",
    'name' => "TuneUp",
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hzJV8U73FbLMy1AWITd5rWYwHE1sCDRd',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                'dev'=> [   // Logs for 'dev' category only
                    // Usage: \Yii::trace("hi there", 'dev');  // log something
                    //        Yii::$app->log->targets['dev']->enabled = false;  // disable log target
                    'class'      => 'yii\log\FileTarget',
                    'levels'     => ['info', 'trace', 'error', 'warning'],
                    'categories' => ['dev'],
                    'logVars'    => [],
                    'logFile'    => '@runtime/logs/dev.log',
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'baseUrl'         => $baseUrl,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'authManager' => [
            // Using DbManager
            'class' => 'yii\rbac\DbManager',
            //'defaultRoles'   => ['employee'],  // Default roles for a user (added in addition to assigned roles)

            // Using PhpManager
            //'class' => 'yii\rbac\PhpManager',
            //'defaultRoles'   => ['registered'],  // Default roles for a user (added in addition to assigned roles)
            // // By default, yii\rbac\PhpManager stores RBAC data in files under @app/rbac/ directory.
            // // Make sure that directory is web writable.
            // 'itemFile'       => '@app/rbac/data/items.php',                // Default path to items.php
            // 'assignmentFile' => '@app/rbac/data/assignments.php',          // Default path to assignments.php
            // 'ruleFile'       => '@app/rbac/data/rules.php',                // Default path to rules.php
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '10.243.43.106'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
