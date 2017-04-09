<?php

//ob_start();
//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
//header('Content-Type: application/json');
//http_response_code(200);

//$postData = isset($_POST['data']) ? $_POST['data'] : NULL;
$data = isset($_POST) ? $_POST: 'test';
print_r($data['data']);
file_put_contents('postdata.txt', print_r($_POST, true));

//Store pothole report
//Send message

//ob_flush(); 
?>