<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => [
        'db' => $db,
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
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
                'usuario*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@Da/User/resources/i18n',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'usuario' => 'usuario.php',
                    ],
                ],
                'custom' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages/i18n',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'custom' => 'custom.php',
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
            'administrators' => [
                'user'
            ],
            'classMap' => [
                'Profile' => 'app\models\Profile',
                'RegistrationForm' => 'app\forms\RegistrationForm'
            ],
            'controllerMap' => [
                'registration' => 'app\controllers\RegistrationController'
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
