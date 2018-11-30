<?php
	/*$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

	$bulk = new MongoDB\Driver\BulkWrite;

    $bulk->insert([
        'test' => 'test2'
    ]);

    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result       = $manager->executeBulkWrite('testeeee.colle1', $bulk, $writeConcern);

    var_dump($result);*/

    require './vendor/autoload.php';

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use app\helpers\CurlClass;

    $app = new \Slim\App;
    $curl = CurlClass::getInstance();

    $app->get('/', function (Request $request, Response $response, array $args) {
        $response->getBody()->write("Hello");
        return $response;
    });

    $app->run();