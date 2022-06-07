<?php

//We need to use an autoloader to import PHPOnCouch classes
//I will use PHPOnCouch autloader for the demo
$autoloader = join(DIRECTORY_SEPARATOR,[__DIR__,'vendor','autoload.php']);
require $autoloader;

//We import the classes that we need
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions;

//We create a client to access the database
$client = new CouchClient('http://admin:password@10.0.0.4:5984','users');

//We create the database if required
if(!$client->databaseExists()){
    $client->createDatabase();
}

//We get the database info just for the demo
var_dump($client->getDatabaseInfos());

//Note:  Every request should be inside a try catch since CouchExceptions could be thrown.For example, let's try to get a unexisting document

try{
    $result = $client->getDoc('1');
    echo 'Document found ';
    var_dump($result);
}
catch(Exceptions\CouchNotFoundException $ex){
    if($ex->getCode() == 404)
        echo 'Document not found';
}
