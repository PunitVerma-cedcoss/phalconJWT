<?php
// print_r(apache_get_modules());
// echo "<pre>"; print_r($_SERVER); die;
// $_SERVER["REQUEST_URI"] = str_replace("/phalt/","/",$_SERVER["REQUEST_URI"]);
// $_GET["_url"] = "/";

use JetBrains\PhpStorm\Internal\ReturnTypeContract;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream as AdaStream;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Enum;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Mvc\Router;


$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->registerNamespaces(
    [
        'App\Components' => APP_PATH . '/components',
        'App\Listeners' => APP_PATH . '/listeners'
    ]
);
$loader->register();

$loader->register();

$container = new FactoryDefault();


// setting logger 🎴
$adapter = new AdaStream('../storage/log/main.log');
$logger = new Logger(
    'messages',
    [
        'main' => $adapter,
    ]
);
$container->set('logger', $logger);

//setting listeners
$eventsManager = new EventsManager();
$eventsManager->attach('orderlistener', new App\Listeners\Orderlistener());
$eventsManager->attach('productlistener', new App\Listeners\Productlistener());
$eventsManager->attach('application:beforeHandleRequest', new App\Listeners\Notificationslistener());
$container->set(
    'EventsManager',
    $eventsManager
);


$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    'router',
    function () {
        $router = new Router();
        $router->handle($_GET['_url']);
        return $router;
    }
);

$container->set(
    'datetime',
    function () {
        $now = new DateTimeImmutable();
        return $now;
    }
);

$application = new Application($container);

$eventsManager->fire('application:beforeHandleRequest', $application);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'shop',
            ]
        );
    }
);

// $container->set(
//     'mongo',
//     function () {
//         $mongo = new MongoClient();

//         return $mongo->selectDB('phalt');
//     },
//     true
// );

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
