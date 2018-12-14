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

    $app = new \Slim\App([
        'settings' => [
            'displayErrorDetails' => true,
        ],
    ]);   

    $app->get('/', function (Request $request, Response $response, array $args) {
        $file = 'views/map.html';
        if (file_exists($file))
            return $response->write(file_get_contents($file));
    });

    $app->get('/ajax/parking', function (Request $request, Response $response, array $args) {
        $curl = CurlClass::get('https://geoservices.grand-nancy.org/arcgis/rest/services/public/VOIRIE_Parking/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=nom%2Cadresse%2Cplaces%2Ccapacite&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson');
        if ($curl['code'] == 200)
            return $response->withJson($curl['response']['features']);
    });

    $app->get('/ajax/tourisme', function (Request $request, Response $response, array $args) {
        $curl = CurlClass::get('https://geoservices.grand-nancy.org/arcgis/rest/services/public/DOPUB_Tourisme/MapServer/0/query?where=COMMUNE%3D%27Nancy%27&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=NAME%2CDESCRIPTION%2CCOMMUNE%2CADRESSE%2CTYPE%2CLINK%2CPHOTOS&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson');
        if ($curl['code'] == 200)
            return $response->withJson($curl['response']['features']);
    });

    $app->run();