<?php
session_start();

define('DB_SERVER','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','tanvi');
define('DB_DATABASE','mavericks');
define("BASE_URL","http://localhost:81/mavericks");


function getDB(){

$dbhost = DB_SERVER;
$dbuser = DB_USERNAME;
$dbpass = DB_PASSWORD;
$dbname = DB_DATABASE;

try{

$dbconnection = new PDO ("mysql:host=$dbhost; dbname=$dbname",$dbuser, $dbpass);
$dbconnection->exec("set name utf8");
$dbconnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $dbconnection;

}catch(PDOException $e){

echo 'Connection failed : ' . $e->getMessage();

}

}

?>