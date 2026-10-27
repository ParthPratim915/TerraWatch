<?php
declare(strict_types=1);

/*
 * Copy this file to config.php and update the credentials.
 * Never commit config.php or real credentials to a public repository.
 */
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'terrawatch',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    'site' => [
        'name' => 'TerraWatch',
        // Leave empty when installed at the web root. Example subfolder: /TerraWatch/public
        'base_path' => '',
    ],
];
