<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Test Application',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '-eiMjwNlb0ycRq_KfDwY4QR5ePovvh4A',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
            ],
        ],
        'db' => $db,
//        'urlManager' => [
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'rules' => [
//            ],
//        ],
		'authManager'  => [
			'class'        => 'Da\User\Component\AuthDbManagerComponent',
			'defaultRoles' => ['user'],
		],
        'view' => [
            'theme' => [
                'basePath' => '@app/views',
                'baseUrl' => '@web/views',
                'pathMap' => [
                    '@Da/User/resources/views/registration' => '@app/views/registration',
                    '@Da/User/resources/views/settings' => '@app/views/settings'
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'custom' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/i18n',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'custom' => 'custom.php',
                    ],
                ],
                'usuario*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@Da/User/resources/i18n',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'usuario' => 'usuario.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
	'modules' => [
		'user' => [
			'class' => Da\User\Module::class,
			// ...other configs from here: [Configuration Options](installation/configuration-options.md), e.g.
			// 'generatePasswords' => true,
			// 'switchIdentitySessionKey' => 'myown_usuario_admin_user_key',
            'enableEmailConfirmation' => false,
            'administrators' => [
                'admin'
            ],
            'classMap' => [
                'Profile' => 'app\models\Profile',
                'RegistrationForm' => 'app\forms\RegistrationForm'
            ],
            'controllerMap' => [
                'registration' => 'app\controllers\RegistrationController',
            ],
		]
	],
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationPath' => [
                '@app/migrations',
                '@yii/rbac/migrations', // Just in case you forgot to run it on console (see next note)
            ],
            'migrationNamespaces' => [
                'Da\User\Migration',
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        //'allowedIPs' => ['127.0.0.1', '::1'],
//    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
