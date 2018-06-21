<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=mis',
            'username' => 'mis',
            'password' => 'RpnZ6UtrC1CSNm9g',
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_'
        ],
        'dbreg' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.74.100.61;dbname=reg_avsreg',
            'username' => 'nkc_connect',
            'password' => 'CampusN2K5C88',
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
