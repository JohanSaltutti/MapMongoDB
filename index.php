<?php
    require './vendor/autoload.php';

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use app\helpers\CurlClass;

    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

	$bulk = new MongoDB\Driver\BulkWrite;

    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $curl = CurlClass::get('https://geoservices.grand-nancy.org/arcgis/rest/services/public/VOIRIE_Parking/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=nom%2Cadresse%2Cplaces%2Ccapacite&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson');
    
    if ($curl['code'] == 200){
        $bulk->delete([]);
        foreach($curl['response']['features'] as $parking){
            if($parking['attributes']['PLACES'] == NULL)$parking['attributes']['PLACES'] = 0;
            $parking['attributes']['positions'] = $parking['geometry'];
            $bulk->insert($parking['attributes']);
        }
        $manager->executeBulkWrite('mapMongo.parking',$bulk);
    }

    $curl = CurlClass::get('https://geoservices.grand-nancy.org/arcgis/rest/services/public/DOPUB_Tourisme/MapServer/0/query?where=COMMUNE%3D%27Nancy%27&text=&objectIds=&time=&geometry=&geometryType=esriGeometryPolygon&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=NAME%2CDESCRIPTION%2CCOMMUNE%2CADRESSE%2CTYPE%2CLINK%2CPHOTOS&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson');
    $bulk = new MongoDB\Driver\BulkWrite;

    if ($curl['code'] == 200){
        $bulk->delete([]);
        foreach($curl['response']['features'] as $lieu){
            $lieu['attributes']['positions'] = $lieu['geometry'];
            $bulk->insert($lieu['attributes']);
        }
        $manager->executeBulkWrite('mapMongo.lieuTouristique',$bulk);
    }


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
        $curl = CurlClass::get('https://geoservices.grand-nancy.org/arcgis/rest/services/public/DOPUB_Tourisme/MapServer/0/query?where=COMMUNE%3D%27Nancy%27&text=&objectIds=&time=&geometry=&geometryType=esriGeometryPolygon&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=NAME%2CDESCRIPTION%2CCOMMUNE%2CADRESSE%2CTYPE%2CLINK%2CPHOTOS&returnGeometry=true&returnTrueCurves=false&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&resultOffset=&resultRecordCount=&queryByDistance=&returnExtentsOnly=false&datumTransformation=&parameterValues=&rangeValues=&f=pjson');
        if ($curl['code'] == 200)
            return $response->withJson($curl['response']['features']);
    });

    $app->run();