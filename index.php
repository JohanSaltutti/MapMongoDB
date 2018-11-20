<?php
	$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
	var_dump($manager);

	$bulk = new MongoDB\Driver\BulkWrite;

    $bulk->insert([
        'test' => 'test2'
    ]);

    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $result       = $manager->executeBulkWrite('testeeee.colle1', $bulk, $writeConcern);

    var_dump($result);
