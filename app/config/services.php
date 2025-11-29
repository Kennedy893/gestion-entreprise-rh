<?php

use flight\Engine;
use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use Tracy\Debugger;

use app\models\CongeModel;
use app\models\HpresenceModel;



/** 
 * @var array $config This comes from the returned array at the bottom of the config.php file
 * @var Engine $app
 */

// uncomment the following line for MySQL
$dsn = 'pgsql:host=' . $config['database']['host'] . ';port=' . $config['database']['port'] . ';dbname=' . $config['database']['dbname'] . 
       ';user=' . $config['database']['user'] . ';password=' . $config['database']['password'];

// uncomment the following line for SQLite
// $dsn = 'sqlite:' . $config['database']['file_path'];

// Uncomment the below lines if you want to add a Flight::db() service
// In development, you'll want the class that captures the queries for you. In production, not so much.
 $pdoClass = Debugger::$showBar === true ? PdoQueryCapture::class : PdoWrapper::class;
 $app->register('db', $pdoClass, [ $dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null ]);

// Got google oauth stuff? You could register that here
// $app->register('google_oauth', Google_Client::class, [ $config['google_oauth'] ]);

// Redis? This is where you'd set that up
// $app->register('redis', Redis::class, [ $config['redis']['host'], $config['redis']['port'] ]);


// Flight::map('AdminModel', function() {
//     return new AdminModel(Flight::db());  
// });

Flight::map('HModel', function() {
    return new \app\models\HModel(Flight::db());  
});

Flight::map('HController', function() {
    return new \app\controllers\HController();  
});

Flight::map('HpresenceModel', function() {
    return new HpresenceModel(Flight::db());
});  
Flight::map('CongeModel', function() {
    return new CongeModel(Flight::db());
});
Flight::map('PaieModel', function () {
    return new \app\models\PaieModel(Flight::db());
});
Flight::map('DashboardModel', function () {
    return new \app\models\DashboardModel(Flight::db());
});

Flight::map('HdashModel', function () {
    return new \app\models\HdashModel(Flight::db());
});

