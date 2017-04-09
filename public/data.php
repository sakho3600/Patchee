<?php

require_once "./library/SocrataDriver.php";

$app_token = "4m7W3ir7cqfZ6odkpL3LPc8tQ";
$co_root_url = "http://data.colorado.gov";

//Tables
$data = array(
    'hwy-curves-and-grades'  => 'gemu-wyf3', //https://data.colorado.gov/Transportation/Highway-Curves-and-Grades-in-Colorado/gemu-wyf3
    'road-curves-and-grades' => 'cdva-fnp3', //https://data.colorado.gov/Transportation/Highway-Curves-and-Grades-in-Colorado/cdva-fnp3
    'hwy-quality-2016'       => 'd6bh-7i9s', //https://data.colorado.gov/Transportation/Highway-Quality-In-Colorado-2016/d6bh-7i9s
    'surface-treatment'      => '9ghp-7fx6'  //https://data.colorado.gov/Transportation/Road-Surface-Treatment-Projects-In-Colorado-Four-20/9ghp-7fx6
);

//$select = "";
//$where = array();
//$params = array("\$limit" => "20");

$socrata = new SocrataDriver($co_root_url, $app_token);
$tables = array();

foreach($data as $key => $tableId)
{
    $tables[$key] = array('id' => $tableId, 'data' => $socrata->from($tableId)->get());
}

print_r($tables);

